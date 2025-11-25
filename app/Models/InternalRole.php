<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class InternalRole extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'track',
        'level',
        'role_type',
        'is_critical',
        'avg_cost_year',
        'billable_ratio',
        'max_direct_reports',
        'can_see_all_teams',
        'can_approve_plans',
        'can_approve_certifications',
        'key_responsibilities',
        'expected_focus',
    ];

    protected function casts(): array
    {
        return [
            'is_critical' => 'boolean',
            'avg_cost_year' => 'decimal:2',
            'billable_ratio' => 'decimal:2',
            'can_see_all_teams' => 'boolean',
            'can_approve_plans' => 'boolean',
            'can_approve_certifications' => 'boolean',
            'key_responsibilities' => 'array',
            'expected_focus' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (InternalRole $role) {
            if (empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Competencias asociadas a este rol interno
     */
    public function competencies(): HasMany
    {
        return $this->hasMany(Competency::class);
    }

    /**
     * Planes de certificación para este rol interno
     */
    public function certificationPlans(): HasMany
    {
        return $this->hasMany(CertificationPlan::class);
    }
}
