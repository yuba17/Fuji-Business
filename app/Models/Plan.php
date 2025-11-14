<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'plan_type_id',
        'area_id',
        'manager_id',
        'director_id',
        'parent_plan_id',
        'status',
        'start_date',
        'target_date',
        'review_date',
        'end_date',
        'version',
        'is_current_version',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'target_date' => 'date',
            'review_date' => 'date',
            'end_date' => 'date',
            'is_current_version' => 'boolean',
            'metadata' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($plan) {
            if (empty($plan->slug)) {
                $plan->slug = Str::slug($plan->name);
            }
        });
    }

    /**
     * Tipo de plan
     */
    public function planType(): BelongsTo
    {
        return $this->belongsTo(PlanType::class);
    }

    /**
     * Área del plan
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Manager responsable
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Director responsable
     */
    public function director(): BelongsTo
    {
        return $this->belongsTo(User::class, 'director_id');
    }

    /**
     * Plan padre (si es subplan)
     */
    public function parentPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'parent_plan_id');
    }

    /**
     * Subplanes
     */
    public function subPlans(): HasMany
    {
        return $this->hasMany(Plan::class, 'parent_plan_id');
    }

    /**
     * Versiones del plan
     */
    public function versions(): HasMany
    {
        return $this->hasMany(PlanVersion::class);
    }

    /**
     * Secciones del plan
     */
    public function sections(): HasMany
    {
        return $this->hasMany(PlanSection::class)->orderBy('order');
    }

    /**
     * KPIs del plan
     */
    public function kpis(): HasMany
    {
        return $this->hasMany(Kpi::class);
    }

    /**
     * Hitos del plan
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class)->orderBy('order');
    }

    /**
     * Tareas del plan
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Riesgos del plan
     */
    public function risks(): HasMany
    {
        return $this->hasMany(Risk::class);
    }

    /**
     * Decisiones relacionadas
     */
    public function decisions(): BelongsToMany
    {
        return $this->belongsToMany(Decision::class);
    }

    /**
     * Clientes relacionados (para planes comerciales)
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class);
    }

    /**
     * Proyectos relacionados (para planes comerciales)
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'plan_comercial_id');
    }

    /**
     * Tags del plan
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Verificar si el plan está en borrador
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Verificar si el plan está aprobado
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Verificar si el plan está en ejecución
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Obtener el estado en español
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Borrador',
            'internal_review' => 'En Revisión Interna',
            'director_review' => 'En Revisión Dirección',
            'approved' => 'Aprobado',
            'in_progress' => 'En Ejecución',
            'under_review' => 'En Revisión Periódica',
            'closed' => 'Cerrado',
            'archived' => 'Archivado',
            default => $this->status,
        };
    }
}
