<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Milestone extends Model
{
    protected $fillable = [
        'name',
        'description',
        'plan_id',
        'start_date',
        'end_date',
        'target_date',
        'status',
        'responsible_id',
        'progress_percentage',
        'metadata',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'target_date' => 'date',
            'progress_percentage' => 'integer',
            'metadata' => 'array',
        ];
    }

    /**
     * Plan del hito
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Responsable del hito
     */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    /**
     * Dependencias donde este hito es predecesor
     */
    public function predecessorDependencies(): HasMany
    {
        return $this->hasMany(MilestoneDependency::class, 'predecessor_id');
    }

    /**
     * Dependencias donde este hito es sucesor
     */
    public function successorDependencies(): HasMany
    {
        return $this->hasMany(MilestoneDependency::class, 'successor_id');
    }

    /**
     * Tareas del hito
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Verificar si el hito está retrasado
     */
    public function isDelayed(): bool
    {
        if ($this->status === 'completed' || $this->status === 'cancelled') {
            return false;
        }

        return $this->target_date < now()->toDateString() && $this->status !== 'completed';
    }

    /**
     * Obtener el estado en español
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'not_started' => 'No Iniciado',
            'in_progress' => 'En Progreso',
            'completed' => 'Completado',
            'delayed' => 'Retrasado',
            'cancelled' => 'Cancelado',
            default => $this->status,
        };
    }
}
