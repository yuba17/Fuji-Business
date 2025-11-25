<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class UserCertificationPlan extends Model
{
    protected $fillable = [
        'user_id',
        'certification_plan_id',
        'assigned_at',
        'assigned_by',
        'source',
        'status',
        'completed_at',
        'auto_reassigned',
        'previous_plan_id',
        'reassigned_at',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'auto_reassigned' => 'boolean',
        'reassigned_at' => 'datetime',
    ];

    /**
     * Usuario al que se asignó el plan
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Plan de certificaciones
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(CertificationPlan::class, 'certification_plan_id');
    }

    /**
     * Usuario que asignó el plan
     */
    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Plan anterior (si fue reasignado)
     */
    public function previousPlan(): BelongsTo
    {
        return $this->belongsTo(CertificationPlan::class, 'previous_plan_id');
    }

    /**
     * Certificaciones del usuario relacionadas con este plan
     */
    public function userCertifications(): HasMany
    {
        return $this->hasMany(UserCertification::class, 'certification_plan_id', 'certification_plan_id')
            ->where('user_id', $this->user_id);
    }

    /**
     * Calcular el porcentaje de progreso del plan
     */
    public function getProgressPercentageAttribute(): float
    {
        $planCertifications = $this->plan->certifications;
        if ($planCertifications->isEmpty()) {
            return 0;
        }

        $completedCount = 0;
        foreach ($planCertifications as $planCert) {
            $userCert = $this->user->userCertifications()
                ->where('certification_id', $planCert->certification_id)
                ->where('status', 'active')
                ->first();
            
            if ($userCert && $userCert->obtained_at) {
                $completedCount++;
            }
        }

        return round(($completedCount / $planCertifications->count()) * 100, 2);
    }

    /**
     * Obtener el número de certificaciones completadas
     */
    public function getCompletedCertificationsCountAttribute(): int
    {
        $planCertifications = $this->plan->certifications;
        $completedCount = 0;
        
        foreach ($planCertifications as $planCert) {
            $userCert = $this->user->userCertifications()
                ->where('certification_id', $planCert->certification_id)
                ->where('status', 'active')
                ->first();
            
            if ($userCert && $userCert->obtained_at) {
                $completedCount++;
            }
        }

        return $completedCount;
    }

    /**
     * Obtener el número de certificaciones pendientes
     */
    public function getPendingCertificationsCountAttribute(): int
    {
        return $this->plan->certifications->count() - $this->completed_certifications_count;
    }

    /**
     * Obtener el número de certificaciones vencidas o próximas a vencer
     */
    public function getOverdueCertificationsCountAttribute(): int
    {
        $planCertifications = $this->plan->certifications;
        $overdueCount = 0;
        
        foreach ($planCertifications as $planCert) {
            $userCert = $this->user->userCertifications()
                ->where('certification_id', $planCert->certification_id)
                ->where('status', 'active')
                ->first();
            
            if ($userCert && $userCert->expires_at) {
                $daysUntilExpiry = now()->diffInDays($userCert->expires_at, false);
                if ($daysUntilExpiry < 0 || $daysUntilExpiry <= 90) {
                    $overdueCount++;
                }
            }
        }

        return $overdueCount;
    }

    /**
     * Marcar el plan como completado
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Marcar el plan como en progreso
     */
    public function markAsInProgress(): void
    {
        if ($this->status === 'pending') {
            $this->update(['status' => 'in_progress']);
        }
    }
}
