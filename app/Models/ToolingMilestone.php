<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolingMilestone extends Model
{
    protected $fillable = [
        'tooling_id',
        'title',
        'description',
        'milestone_type',
        'target_quarter',
        'target_year',
        'priority',
        'status',
        'assigned_to_id',
        'completed_at',
        'notes',
        'checklist',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'date',
            'checklist' => 'array',
        ];
    }

    /**
     * Herramienta asociada
     */
    public function tooling(): BelongsTo
    {
        return $this->belongsTo(Tooling::class);
    }

    /**
     * Responsable del hito
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    /**
     * Obtener el tipo de hito en español
     */
    public function getMilestoneTypeLabelAttribute(): string
    {
        return match($this->milestone_type) {
            'nueva_funcionalidad' => 'Nueva Funcionalidad',
            'mejora_estabilidad' => 'Mejora de Estabilidad',
            'mejora_rendimiento' => 'Mejora de Rendimiento',
            'ampliacion_escenarios' => 'Ampliación de Escenarios',
            'integracion' => 'Integración',
            'otro' => 'Otro',
            default => $this->milestone_type,
        };
    }

    /**
     * Obtener la prioridad en español
     */
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'alta' => 'Alta',
            'media' => 'Media',
            'baja' => 'Baja',
            default => $this->priority ?? 'Media',
        };
    }

    /**
     * Obtener el estado en español
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'planificado' => 'Planificado',
            'en_curso' => 'En Curso',
            'completado' => 'Completado',
            'bloqueado' => 'Bloqueado',
            default => $this->status,
        };
    }

    /**
     * Verificar si el hito está próximo a vencer
     */
    public function isUpcoming(): bool
    {
        if (!$this->target_year || !$this->target_quarter) {
            return false;
        }

        $currentYear = now()->year;
        $currentQuarter = ceil(now()->month / 3);

        $targetQuarter = (int) str_replace('Q', '', $this->target_quarter);

        if ($this->target_year > $currentYear) {
            return true;
        }

        if ($this->target_year == $currentYear && $targetQuarter >= $currentQuarter) {
            return true;
        }

        return false;
    }

    /**
     * Obtener el porcentaje de completado del checklist
     */
    public function getChecklistProgressAttribute(): int
    {
        if (!$this->checklist || !is_array($this->checklist) || count($this->checklist) === 0) {
            return 0;
        }

        $completed = collect($this->checklist)->where('completed', true)->count();
        $total = count($this->checklist);

        return $total > 0 ? (int) round(($completed / $total) * 100) : 0;
    }
}

