<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificationPlanCertification extends Model
{
    protected $fillable = [
        'certification_plan_id',
        'certification_id',
        'priority',
        'order',
        'target_months',
        'target_date',
        'is_flexible',
    ];

    protected $casts = [
        'priority' => 'integer',
        'order' => 'integer',
        'target_months' => 'integer',
        'target_date' => 'date',
        'is_flexible' => 'boolean',
    ];

    /**
     * Plan al que pertenece esta certificación
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(CertificationPlan::class, 'certification_plan_id');
    }

    /**
     * Certificación
     */
    public function certification(): BelongsTo
    {
        return $this->belongsTo(Certification::class);
    }

    /**
     * Obtener la descripción de la prioridad
     */
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            1 => 'Crítica',
            2 => 'Alta',
            3 => 'Media',
            4 => 'Baja',
            5 => 'Opcional',
            default => 'Media',
        };
    }

    /**
     * Verificar si es obligatoria (prioridad 1 o 2)
     */
    public function getIsRequiredAttribute(): bool
    {
        return $this->priority <= 2;
    }
}
