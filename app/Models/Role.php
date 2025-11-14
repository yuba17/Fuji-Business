<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Usuarios con este rol
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Verificar si el rol es director
     */
    public function isDirector(): bool
    {
        return $this->slug === 'director';
    }

    /**
     * Verificar si el rol es manager
     */
    public function isManager(): bool
    {
        return $this->slug === 'manager';
    }

    /**
     * Verificar si el rol es técnico
     */
    public function isTecnico(): bool
    {
        return $this->slug === 'tecnico';
    }

    /**
     * Verificar si el rol es visualización
     */
    public function isVisualizacion(): bool
    {
        return $this->slug === 'visualizacion';
    }
}
