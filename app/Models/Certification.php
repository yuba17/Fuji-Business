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
        'validity_months',
        'cost',
        'currency',
        'difficulty_score',
        'value_score',
        'is_critical',
        'is_internal',
        'requirements',
        'exam_details',
        'badge_icon',
        'badge_color',
        'points_reward',
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
        'points_reward' => 'integer',
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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_certifications')
            ->withPivot(['obtained_at', 'expires_at', 'certificate_number', 'status', 'planned_date', 'priority', 'notes'])
            ->withTimestamps();
    }
}
