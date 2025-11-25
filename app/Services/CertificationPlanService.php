<?php

namespace App\Services;

use App\Models\User;
use App\Models\CertificationPlan;
use App\Models\UserCertificationPlan;
use App\Models\UserCertification;
use App\Models\ServiceLine;
use App\Models\InternalRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificationPlanService
{
    /**
     * Asignar un plan de certificaciones a un usuario
     */
    public function assignPlanToUser(
        CertificationPlan $plan,
        User $user,
        ?User $assignedBy = null,
        string $source = 'manual'
    ): UserCertificationPlan {
        // Verificar si el usuario ya tiene este plan asignado y activo
        $existingPlan = UserCertificationPlan::where('user_id', $user->id)
            ->where('certification_plan_id', $plan->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->first();

        if ($existingPlan) {
            return $existingPlan;
        }

        // Crear la asignación del plan
        $userPlan = UserCertificationPlan::create([
            'user_id' => $user->id,
            'certification_plan_id' => $plan->id,
            'assigned_at' => now(),
            'assigned_by' => $assignedBy?->id,
            'source' => $source,
            'status' => 'pending',
        ]);

        // Sincronizar las certificaciones del plan con el usuario
        $this->syncPlanCertificationsToUser($plan, $user);

        return $userPlan;
    }

    /**
     * Sincronizar las certificaciones de un plan con un usuario
     * Crea registros en user_certifications para las certificaciones del plan que aún no tiene
     */
    public function syncPlanCertificationsToUser(CertificationPlan $plan, User $user): void
    {
        $planCertifications = $plan->certifications;

        foreach ($planCertifications as $planCert) {
            // Verificar si el usuario ya tiene esta certificación
            $existingUserCert = UserCertification::where('user_id', $user->id)
                ->where('certification_id', $planCert->certification_id)
                ->first();

            if (!$existingUserCert) {
                // Calcular fecha planificada basada en target_months o target_date
                $plannedDate = null;
                if ($planCert->target_date) {
                    $plannedDate = $planCert->target_date;
                } elseif ($planCert->target_months) {
                    $userPlan = UserCertificationPlan::where('user_id', $user->id)
                        ->where('certification_plan_id', $plan->id)
                        ->first();
                    
                    if ($userPlan) {
                        $plannedDate = $userPlan->assigned_at->copy()->addMonths($planCert->target_months);
                    }
                }

                // Crear registro de certificación planificada
                UserCertification::create([
                    'user_id' => $user->id,
                    'certification_id' => $planCert->certification_id,
                    'certification_plan_id' => $plan->id,
                    'assigned_from_plan_at' => now(),
                    'status' => 'planned',
                    'planned_date' => $plannedDate,
                    'priority' => $planCert->priority,
                ]);
            } else {
                // Si ya existe, actualizar para vincularlo al plan si no está vinculado
                if (!$existingUserCert->certification_plan_id) {
                    $existingUserCert->update([
                        'certification_plan_id' => $plan->id,
                        'assigned_from_plan_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reasignar planes cuando un usuario cambia de rol o línea de servicio
     */
    public function reassignPlansOnRoleChange(User $user, ?InternalRole $oldRole = null, ?ServiceLine $oldServiceLine = null): void
    {
        $currentRole = $user->internalRole;
        $currentServiceLines = $user->serviceLines;

        if (!$currentRole) {
            return;
        }

        // Cancelar planes antiguos que ya no aplican
        if ($oldRole || $oldServiceLine) {
            $this->cancelOutdatedPlans($user, $currentRole, $currentServiceLines);
        }

        // Asignar nuevos planes basados en el rol y líneas de servicio actuales
        foreach ($currentServiceLines as $serviceLine) {
            $plan = CertificationPlan::where('service_line_id', $serviceLine->id)
                ->where('internal_role_id', $currentRole->id)
                ->where('is_active', true)
                ->first();

            if ($plan) {
                $this->assignPlanToUser($plan, $user, null, 'automatic');
            }
        }
    }

    /**
     * Cancelar planes que ya no aplican al usuario
     */
    protected function cancelOutdatedPlans(User $user, InternalRole $currentRole, $currentServiceLines): void
    {
        $currentServiceLineIds = $currentServiceLines->pluck('id')->toArray();

        $outdatedPlans = UserCertificationPlan::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->with('plan')
            ->get()
            ->filter(function ($userPlan) use ($currentRole, $currentServiceLineIds) {
                $plan = $userPlan->plan;
                return $plan->internal_role_id !== $currentRole->id 
                    || !in_array($plan->service_line_id, $currentServiceLineIds);
            });

        foreach ($outdatedPlans as $outdatedPlan) {
            $outdatedPlan->update([
                'status' => 'cancelled',
                'auto_reassigned' => true,
                'previous_plan_id' => $outdatedPlan->certification_plan_id,
                'reassigned_at' => now(),
            ]);
        }
    }

    /**
     * Calcular el progreso de un plan para un usuario
     */
    public function calculatePlanProgress(UserCertificationPlan $userPlan): array
    {
        return [
            'percentage' => $userPlan->progress_percentage,
            'completed' => $userPlan->completed_certifications_count,
            'pending' => $userPlan->pending_certifications_count,
            'overdue' => $userPlan->overdue_certifications_count,
            'total' => $userPlan->plan->certifications->count(),
        ];
    }

    /**
     * Buscar o crear un plan para una combinación de ServiceLine e InternalRole
     */
    public function findOrCreatePlan(ServiceLine $serviceLine, InternalRole $internalRole, ?string $name = null): CertificationPlan
    {
        $plan = CertificationPlan::where('service_line_id', $serviceLine->id)
            ->where('internal_role_id', $internalRole->id)
            ->where('is_active', true)
            ->first();

        if (!$plan) {
            $plan = CertificationPlan::create([
                'service_line_id' => $serviceLine->id,
                'internal_role_id' => $internalRole->id,
                'name' => $name,
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);
        }

        return $plan;
    }

    /**
     * Asignar automáticamente planes a usuarios basados en su rol y líneas de servicio
     */
    public function autoAssignPlansToUser(User $user): void
    {
        if (!$user->internalRole) {
            return;
        }

        $serviceLines = $user->serviceLines;

        foreach ($serviceLines as $serviceLine) {
            $plan = CertificationPlan::where('service_line_id', $serviceLine->id)
                ->where('internal_role_id', $user->internal_role_id)
                ->where('is_active', true)
                ->first();

            if ($plan) {
                // Verificar si ya tiene este plan asignado
                $existingPlan = UserCertificationPlan::where('user_id', $user->id)
                    ->where('certification_plan_id', $plan->id)
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->first();

                if (!$existingPlan) {
                    $this->assignPlanToUser($plan, $user, null, 'automatic');
                }
            }
        }
    }

    /**
     * Verificar y actualizar el estado del plan basado en el progreso
     */
    public function updatePlanStatus(UserCertificationPlan $userPlan): void
    {
        $progress = $this->calculatePlanProgress($userPlan);

        // Si hay al menos una certificación completada, marcar como en progreso
        if ($progress['completed'] > 0 && $userPlan->status === 'pending') {
            $userPlan->markAsInProgress();
        }

        // Si todas las certificaciones están completadas, marcar como completado
        if ($progress['completed'] === $progress['total'] && $progress['total'] > 0) {
            $userPlan->markAsCompleted();
        }
    }
}

