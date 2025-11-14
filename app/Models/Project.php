<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'client_id',
        'plan_comercial_id',
        'sector_economico',
        'status',
        'fecha_inicio',
        'fecha_fin',
        'presupuesto',
        'moneda',
        'manager_id',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'presupuesto' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });
    }

    /**
     * Cliente del proyecto
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Plan comercial relacionado
     */
    public function planComercial(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_comercial_id');
    }

    /**
     * Manager del proyecto
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Tareas del proyecto
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Riesgos del proyecto
     */
    public function risks(): HasMany
    {
        return $this->hasMany(Risk::class);
    }

    /**
     * Obtener el estado en español
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'prospecto' => 'Prospecto',
            'en_negociacion' => 'En Negociación',
            'activo' => 'Activo',
            'en_pausa' => 'En Pausa',
            'completado' => 'Completado',
            'cancelado' => 'Cancelado',
            default => $this->status,
        };
    }
}
