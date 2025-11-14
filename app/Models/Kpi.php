<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kpi extends Model
{
    protected $fillable = [
        'name',
        'description',
        'plan_id',
        'area_id',
        'type',
        'unit',
        'target_value',
        'current_value',
        'calculation_method',
        'formula',
        'update_frequency',
        'last_updated_at',
        'status',
        'threshold_green',
        'threshold_yellow',
        'responsible_id',
        'kpi_type',
        'metadata',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'target_value' => 'decimal:4',
            'current_value' => 'decimal:4',
            'threshold_green' => 'decimal:2',
            'threshold_yellow' => 'decimal:2',
            'last_updated_at' => 'date',
            'is_active' => 'boolean',
            'metadata' => 'array',
        ];
    }

    /**
     * Plan del KPI
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Área del KPI
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Responsable del KPI
     */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    /**
     * Historial del KPI
     */
    public function history(): HasMany
    {
        return $this->hasMany(KpiHistory::class);
    }

    /**
     * Calcular el porcentaje de cumplimiento
     */
    public function getPercentageAttribute(): ?float
    {
        if (!$this->target_value || $this->target_value == 0) {
            return null;
        }

        return ($this->current_value / $this->target_value) * 100;
    }

    /**
     * Actualizar el estado basado en el porcentaje
     */
    public function updateStatus(): void
    {
        $percentage = $this->percentage;

        if ($percentage === null) {
            return;
        }

        if ($percentage >= $this->threshold_green) {
            $this->status = 'green';
        } elseif ($percentage >= $this->threshold_yellow) {
            $this->status = 'yellow';
        } else {
            $this->status = 'red';
        }

        $this->save();
    }

    /**
     * Obtener el color del estado
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'green' => 'green',
            'yellow' => 'yellow',
            'red' => 'red',
            default => 'gray',
        };
    }
}
