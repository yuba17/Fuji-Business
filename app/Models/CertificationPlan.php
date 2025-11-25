<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificationPlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_line_id',
        'internal_role_id',
        'name',
        'description',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Generar nombre automático si no se proporciona
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($plan) {
            if (empty($plan->name)) {
                $serviceLine = $plan->serviceLine->name ?? '';
                $internalRole = $plan->internalRole->name ?? '';
                $plan->name = "Plan {$internalRole} - {$serviceLine}";
            }
        });
    }

    /**
     * Línea de servicio del plan
     */
    public function serviceLine(): BelongsTo
    {
        return $this->belongsTo(ServiceLine::class);
    }

    /**
     * Rol interno del plan
     */
    public function internalRole(): BelongsTo
    {
        return $this->belongsTo(InternalRole::class);
    }

    /**
     * Usuario que creó el plan
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Certificaciones incluidas en el plan
     */
    public function certifications(): HasMany
    {
        return $this->hasMany(CertificationPlanCertification::class)->orderBy('order');
    }

    /**
     * Usuarios con este plan asignado
     */
    public function userPlans(): HasMany
    {
        return $this->hasMany(UserCertificationPlan::class);
    }

    /**
     * Usuarios activos con este plan
     */
    public function activeUserPlans(): HasMany
    {
        return $this->hasMany(UserCertificationPlan::class)
            ->whereIn('status', ['pending', 'in_progress']);
    }
}
