<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskMitigationAction extends Model
{
    protected $fillable = [
        'risk_id',
        'action',
        'description',
        'responsible_id',
        'target_date',
        'completed_at',
        'status',
        'cost',
        'expected_probability_reduction',
        'expected_impact_reduction',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'target_date' => 'date',
            'completed_at' => 'date',
            'cost' => 'decimal:2',
            'expected_probability_reduction' => 'integer',
            'expected_impact_reduction' => 'integer',
        ];
    }

    /**
     * Riesgo al que pertenece esta acción
     */
    public function risk(): BelongsTo
    {
        return $this->belongsTo(Risk::class);
    }

    /**
     * Responsable de la acción
     */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    /**
     * Obtener el estado en español
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'in_progress' => 'En Progreso',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            default => $this->status,
        };
    }
}
