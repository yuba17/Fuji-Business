<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Certification extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'provider',
        'category',
        'level',
        'official_url',
        'url',
        'image_url',
        'validity_months',
        'cost',
        'currency',
        'difficulty_score',
        'value_score',
        'is_critical',
        'is_internal',
        'requirements',
        'exam_details',
        'order',
        'is_active',
    ];

    protected $casts = [
        'validity_months' => 'integer',
        'cost' => 'decimal:2',
        'difficulty_score' => 'integer',
        'value_score' => 'integer',
        'is_critical' => 'boolean',
        'is_internal' => 'boolean',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function userCertifications(): HasMany
    {
        return $this->hasMany(UserCertification::class);
    }

    public function badges(): HasMany
    {
        return $this->hasMany(CertificationBadge::class);
    }

    /**
     * Planes de certificación que incluyen esta certificación
     */
    public function certificationPlans(): BelongsToMany
    {
        return $this->belongsToMany(CertificationPlan::class, 'certification_plan_certifications')
            ->withPivot(['priority', 'order', 'target_months', 'target_date', 'is_flexible'])
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_certifications')
            ->withPivot(['obtained_at', 'expires_at', 'certificate_number', 'status', 'planned_date', 'priority', 'notes'])
            ->withTimestamps();
    }
}
