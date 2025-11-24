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
        'infrastructure_class', // 'license' o 'hardware'
        'tracking_mode', // 'individual' o 'group'
        'acquisition_status', // 'purchased', 'to_purchase', 'planned'
        'status',
        'area_id',
        'plan_id',
        'owner_id',
        'cost_monthly',
        'cost_yearly',
        'tax_rate',
        'capacity',
        'utilization_percent',
        'provider',
        'location',
        'roadmap_date',
        'expires_at', // Para licencias
        'renewal_reminder_days', // Días antes de caducar para avisar
        'metadata',
        'order',
        'is_critical',
        'quantity', // total unidades (para tracking grupal)
        'quantity_in_use',
        'quantity_reserved',
        'is_template',
        'template_id',
        'identifier',
    ];

    protected function casts(): array
    {
        return [
            'cost_monthly' => 'decimal:2',
            'cost_yearly' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'utilization_percent' => 'integer',
            'tracking_mode' => 'string',
            'roadmap_date' => 'date',
            'expires_at' => 'date',
            'renewal_reminder_days' => 'integer',
            'metadata' => 'array',
            'is_critical' => 'boolean',
            'quantity' => 'integer',
            'quantity_in_use' => 'integer',
            'quantity_reserved' => 'integer',
            'is_template' => 'boolean',
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
     * Plantilla de la que proviene esta instancia
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Infrastructure::class, 'template_id');
    }

    /**
     * Instancias creadas desde esta plantilla
     */
    public function instances()
    {
        return $this->hasMany(Infrastructure::class, 'template_id');
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

    /**
     * Verificar si la licencia está próxima a caducar
     */
    public function isExpiringSoon(): bool
    {
        if (!$this->expires_at || $this->infrastructure_class !== 'license') {
            return false;
        }

        // Si ya caducó, no está "próxima a caducar"
        if ($this->expires_at->isPast()) {
            return false;
        }

        $reminderDays = $this->renewal_reminder_days ?? 30;
        // Está próxima a caducar si la fecha de caducidad está dentro de los días de aviso
        return $this->expires_at->isBefore(now()->addDays($reminderDays)) && $this->expires_at->isAfter(now());
    }

    /**
     * Verificar si la licencia ha caducado
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at || $this->infrastructure_class !== 'license') {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Días hasta la caducidad
     */
    public function daysUntilExpiry(): ?int
    {
        if (!$this->expires_at || $this->infrastructure_class !== 'license') {
            return null;
        }

        return now()->diffInDays($this->expires_at, false);
    }

    /**
     * Estado de caducidad para mostrar
     */
    public function getExpiryStatusAttribute(): string
    {
        if (!$this->expires_at || $this->infrastructure_class !== 'license') {
            return 'no_expiry';
        }

        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->isExpiringSoon()) {
            return 'expiring_soon';
        }

        return 'valid';
    }

    public function getTaxRateAttribute($value): float
    {
        return $value !== null ? (float) $value : 0.0;
    }

    public function getTaxMultiplierAttribute(): float
    {
        return 1 + ($this->tax_rate / 100);
    }

    public function getCostMonthlyWithTaxAttribute(): float
    {
        $net = $this->cost_monthly ?? 0;
        if (!$net && $this->cost_yearly) {
            $net = $this->cost_yearly / 12;
        }

        return round($net * $this->tax_multiplier, 2);
    }

    public function getCostYearlyWithTaxAttribute(): float
    {
        $net = $this->cost_yearly ?? 0;
        if (!$net && $this->cost_monthly) {
            $net = $this->cost_monthly * 12;
        }

        return round($net * $this->tax_multiplier, 2);
    }

    /**
     * Alias para el total de unidades (columna quantity)
     */
    public function getQuantityTotalAttribute(): int
    {
        return $this->quantity ?? 1;
    }

    /**
     * Unidades disponibles considerando uso y reservas
     */
    public function getQuantityAvailableAttribute(): int
    {
        $available = $this->quantity_total - ($this->quantity_in_use ?? 0) - ($this->quantity_reserved ?? 0);
        return max(0, $available);
    }

    /**
     * Número efectivo de unidades que representa este registro
     */
    public function getEffectiveUnitsAttribute(): int
    {
        return $this->tracking_mode === 'group'
            ? max(1, $this->quantity_total)
            : 1;
    }
}
