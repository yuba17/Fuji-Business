<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tooling extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'status',
        'criticality',
        'started_at',
        'last_updated_at',
        'owner_id',
        'contact_reference',
        'benefits',
        'use_scenarios',
        'impact_metrics',
        'plan_id',
        'area_id',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'last_updated_at' => 'date',
            'use_scenarios' => 'array',
            'impact_metrics' => 'array',
            'metadata' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tooling) {
            if (empty($tooling->slug)) {
                $tooling->slug = Str::slug($tooling->name);
            }
        });
    }

    /**
     * Plan de desarrollo interno asociado
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Área propietaria
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Responsable principal
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Equipo implicado
     */
    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tooling_user')
            ->withTimestamps();
    }

    /**
     * Hitos de evolución
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(ToolingMilestone::class)->orderBy('target_year')->orderBy('target_quarter');
    }

    /**
     * Obtener el estado en español
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'idea' => 'Idea',
            'en_evaluacion' => 'En Evaluación',
            'en_desarrollo' => 'En Desarrollo',
            'beta' => 'Beta',
            'produccion' => 'Producción',
            'obsoleta' => 'Obsoleta',
            default => $this->status,
        };
    }

    /**
     * Obtener el tipo en español
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'ofensiva' => 'Ofensiva',
            'automatizacion' => 'Automatización',
            'laboratorio' => 'Laboratorio',
            'reporting' => 'Reporting',
            'soporte' => 'Soporte',
            'otro' => 'Otro',
            default => $this->type,
        };
    }

    /**
     * Obtener la criticidad en español
     */
    public function getCriticalityLabelAttribute(): string
    {
        return match($this->criticality) {
            'alta' => 'Alta',
            'media' => 'Media',
            'baja' => 'Baja',
            default => $this->criticality ?? 'Media',
        };
    }
}

