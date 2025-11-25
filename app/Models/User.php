<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'manager_id',
        'internal_role_id',
        'area_id',
        'bio',
        'avatar_url',
        'phone',
        'joined_at',
        'last_evaluation_at',
        'availability_percent',
        'work_preferences',
        'career_goals',
        'profile_completion_percent',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'joined_at' => 'date',
            'last_evaluation_at' => 'date',
            'work_preferences' => 'array',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Roles del usuario
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Áreas que gestiona el usuario (para managers)
     */
    public function areas()
    {
        return $this->belongsToMany(Area::class);
    }

    /**
     * Rol interno actual del usuario
     */
    public function internalRole(): BelongsTo
    {
        return $this->belongsTo(InternalRole::class);
    }

    /**
     * Área principal del usuario
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Historial de roles internos del usuario
     */
    public function internalRoleHistory(): HasMany
    {
        return $this->hasMany(UserInternalRole::class);
    }

    /**
     * Líneas de servicio a las que pertenece el usuario
     */
    public function serviceLines(): BelongsToMany
    {
        return $this->belongsToMany(ServiceLine::class);
    }

    /**
     * Competencias del usuario (con niveles)
     */
    public function competencies(): BelongsToMany
    {
        return $this->belongsToMany(Competency::class, 'user_competencies')
            ->withPivot('current_level', 'target_level', 'last_assessed_at', 'assessed_by', 'notes')
            ->withTimestamps();
    }

    /**
     * Certificaciones del usuario
     */
    public function userCertifications(): HasMany
    {
        return $this->hasMany(UserCertification::class);
    }

    /**
     * Certificaciones del usuario (relación many-to-many)
     */
    public function certifications(): BelongsToMany
    {
        return $this->belongsToMany(Certification::class, 'user_certifications')
            ->withPivot(['obtained_at', 'expires_at', 'certificate_number', 'status', 'planned_date', 'priority', 'notes'])
            ->withTimestamps();
    }

    /**
     * Badges de certificaciones del usuario
     */
    public function certificationBadges(): HasMany
    {
        return $this->hasMany(CertificationBadge::class);
    }

    /**
     * Planes de certificación asignados al usuario
     */
    public function certificationPlans(): HasMany
    {
        return $this->hasMany(UserCertificationPlan::class);
    }

    /**
     * Planes de certificación activos del usuario
     */
    public function activeCertificationPlans(): HasMany
    {
        return $this->hasMany(UserCertificationPlan::class)
            ->whereIn('status', ['pending', 'in_progress']);
    }

    /**
     * Evaluaciones recibidas por el usuario
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(UserEvaluation::class);
    }

    /**
     * Evaluaciones realizadas por el usuario (como evaluador)
     */
    public function evaluationsAsEvaluator(): HasMany
    {
        return $this->hasMany(UserEvaluation::class, 'evaluator_id');
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    /**
     * Verificar si el usuario tiene alguno de los roles
     */
    public function hasAnyRole(array $roleSlugs): bool
    {
        return $this->roles()->whereIn('slug', $roleSlugs)->exists();
    }

    /**
     * Verificar si es director
     */
    public function isDirector(): bool
    {
        return $this->hasRole('director');
    }

    /**
     * Verificar si es manager
     */
    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    /**
     * Verificar si es técnico
     */
    public function isTecnico(): bool
    {
        return $this->hasRole('tecnico');
    }

    /**
     * Verificar si es visualización
     */
    public function isVisualizacion(): bool
    {
        return $this->hasRole('visualizacion');
    }

    /**
     * Planes donde el usuario es manager
     */
    public function managedPlans(): HasMany
    {
        return $this->hasMany(Plan::class, 'manager_id');
    }

    /**
     * Planes donde el usuario es director
     */
    public function directedPlans(): HasMany
    {
        return $this->hasMany(Plan::class, 'director_id');
    }

    /**
     * Tareas asignadas al usuario
     */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Tareas creadas por el usuario
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     * Compatible con Laravel's Gate system
     */
    public function can($ability, $arguments = [])
    {
        // Si es director, tiene todos los permisos
        if ($this->isDirector()) {
            return true;
        }

        // Delegar a Gate para verificar policies
        return app(\Illuminate\Contracts\Auth\Access\Gate::class)->forUser($this)->check($ability, $arguments);
    }

    /**
     * Scopes para filtrar usuarios por rol
     */
    public function scopeDirectors($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('slug', 'director');
        });
    }

    public function scopeManagers($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('slug', 'manager');
        });
    }

    public function scopeTecnicos($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('slug', 'tecnico');
        });
    }

    /**
     * Manager directo de este usuario (jefe).
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Personas que reportan directamente a este usuario.
     */
    public function directReports(): HasMany
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Verificar si el usuario puede acceder al panel de Filament
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['director', 'manager']);
    }
}
