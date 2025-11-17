<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Infrastructure extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'category',
        'status',
        'area_id',
        'plan_id',
        'owner_id',
        'cost_monthly',
        'cost_yearly',
        'capacity',
        'utilization_percent',
        'provider',
        'location',
        'roadmap_date',
        'metadata',
        'order',
        'is_critical',
    ];

    protected function casts(): array
    {
        return [
            'cost_monthly' => 'decimal:2',
            'cost_yearly' => 'decimal:2',
            'utilization_percent' => 'integer',
            'roadmap_date' => 'date',
            'metadata' => 'array',
            'is_critical' => 'boolean',
        ];
    }

    /**
     * Área asociada
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Plan asociado
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Propietario/responsable
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Calcular coste anual estimado si solo hay mensual
     */
    public function getEstimatedYearlyCostAttribute(): float
    {
        if ($this->cost_yearly) {
            return $this->cost_yearly;
        }
        if ($this->cost_monthly) {
            return $this->cost_monthly * 12;
        }
        return 0;
    }
}
