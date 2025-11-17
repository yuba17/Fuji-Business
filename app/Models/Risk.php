<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Risk extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'plan_id',
        'area_id',
        'project_id',
        'probability',
        'impact',
        'risk_level',
        'category',
        'strategy',
        'owner_id',
        'status',
        'identified_at',
        'target_mitigation_date',
        'mitigated_at',
        'mitigation_plan',
        'estimated_cost',
        'trend',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'probability' => 'integer',
            'impact' => 'integer',
            'risk_level' => 'integer',
            'identified_at' => 'date',
            'target_mitigation_date' => 'date',
            'mitigated_at' => 'date',
            'estimated_cost' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($risk) {
            // Calcular nivel de riesgo automáticamente
            $risk->risk_level = $risk->probability * $risk->impact;
            
            // Categorizar según nivel
            if ($risk->risk_level >= 21) {
                $risk->category = 'critico';
            } elseif ($risk->risk_level >= 13) {
                $risk->category = 'alto';
            } elseif ($risk->risk_level >= 6) {
                $risk->category = 'medio';
            } else {
                $risk->category = 'bajo';
            }
        });
    }

    /**
     * Plan del riesgo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Área del riesgo
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Proyecto del riesgo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Propietario del riesgo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Acciones de mitigación
     */
    public function mitigationActions(): HasMany
    {
        return $this->hasMany(RiskMitigationAction::class);
    }

    /**
     * Verificar si el riesgo es crítico
     */
    public function isCritical(): bool
    {
        return $this->category === 'critico';
    }

    /**
     * Obtener el color del riesgo según categoría
     */
    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'critico' => 'red',
            'alto' => 'orange',
            'medio' => 'yellow',
            'bajo' => 'green',
            default => 'gray',
        };
    }

    /**
     * Obtener la estrategia en español
     */
    public function getStrategyLabelAttribute(): string
    {
        return match($this->strategy) {
            'avoid' => 'Evitar',
            'mitigate' => 'Mitigar',
            'transfer' => 'Transferir',
            'accept' => 'Aceptar',
            default => $this->strategy ?? 'No definida',
        };
    }

    /**
     * Tags del riesgo
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
