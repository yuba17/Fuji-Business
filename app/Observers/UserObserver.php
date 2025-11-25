<?php

namespace App\Observers;

use App\Models\User;
use App\Services\CertificationPlanService;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function __construct(
        protected CertificationPlanService $certificationPlanService
    ) {}

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Asignar planes automáticamente cuando se crea un usuario con rol y líneas de servicio
        if ($user->internalRole && $user->serviceLines->isNotEmpty()) {
            try {
                $this->certificationPlanService->autoAssignPlansToUser($user);
            } catch (\Exception $e) {
                Log::error('Error al asignar planes de certificación automáticamente al crear usuario', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Verificar si cambió el rol interno o las líneas de servicio
        if ($user->wasChanged(['internal_role_id']) || $user->relationLoaded('serviceLines')) {
            try {
                $oldRoleId = $user->getOriginal('internal_role_id');
                $oldRole = $oldRoleId ? \App\Models\InternalRole::find($oldRoleId) : null;
                
                // Obtener líneas de servicio anteriores (si están cargadas)
                $oldServiceLines = null;
                if ($user->relationLoaded('serviceLines')) {
                    // Nota: Esto requiere que se carguen las relaciones antes de actualizar
                    // En la práctica, puede ser necesario manejar esto de otra manera
                }

                $this->certificationPlanService->reassignPlansOnRoleChange($user, $oldRole, $oldServiceLines);
            } catch (\Exception $e) {
                Log::error('Error al reasignar planes de certificación al actualizar usuario', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
