<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
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
    public function managedPlans()
    {
        return $this->hasMany(Plan::class, 'manager_id');
    }

    /**
     * Planes donde el usuario es director
     */
    public function directedPlans()
    {
        return $this->hasMany(Plan::class, 'director_id');
    }

    /**
     * Tareas asignadas al usuario
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Tareas creadas por el usuario
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }
}
