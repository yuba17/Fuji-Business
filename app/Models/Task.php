<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'plan_id',
        'area_id',
        'milestone_id',
        'project_id',
        'parent_task_id',
        'assigned_to',
        'created_by',
        'status',
        'priority',
        'due_date',
        'estimated_hours',
        'actual_hours',
        'order',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'estimated_hours' => 'integer',
            'actual_hours' => 'integer',
            'metadata' => 'array',
        ];
    }

    /**
     * Plan de la tarea
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Área de la tarea
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Hito de la tarea
     */
    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    /**
     * Proyecto de la tarea
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Tarea padre (si es subtarea)
     */
    public function parentTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    /**
     * Subtareas
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    /**
     * Usuario asignado
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Usuario creador
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Dependencias donde esta tarea es predecesora
     */
    public function predecessorDependencies(): HasMany
    {
        return $this->hasMany(TaskDependency::class, 'predecessor_id');
    }

    /**
     * Dependencias donde esta tarea es sucesora
     */
    public function successorDependencies(): HasMany
    {
        return $this->hasMany(TaskDependency::class, 'successor_id');
    }

    /**
     * Verificar si la tarea está vencida
     */
    public function isOverdue(): bool
    {
        if (!$this->due_date || $this->status === 'done' || $this->status === 'cancelled') {
            return false;
        }

        return $this->due_date < now()->toDateString();
    }

    /**
     * Obtener el estado en español
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'todo' => 'Por Hacer',
            'in_progress' => 'En Progreso',
            'review' => 'En Revisión',
            'done' => 'Completada',
            'cancelled' => 'Cancelada',
            default => $this->status,
        };
    }

    /**
     * Obtener la prioridad en español
     */
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            'critical' => 'Crítica',
            default => $this->priority,
        };
    }
}
