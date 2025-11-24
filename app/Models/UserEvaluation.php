<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEvaluation extends Model
{
    protected $fillable = [
        'user_id',
        'evaluator_id',
        'evaluation_date',
        'type',
        'status',
        'overall_score',
        'strengths',
        'areas_for_improvement',
        'achievements',
        'feedback',
        'goals_achieved',
        'goals_set',
        'career_development_plan',
        'competency_scores',
        'next_evaluation_date',
        'notes',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'evaluation_date' => 'date',
            'next_evaluation_date' => 'date',
            'goals_achieved' => 'array',
            'goals_set' => 'array',
            'career_development_plan' => 'array',
            'competency_scores' => 'array',
            'metadata' => 'array',
        ];
    }

    /**
     * Usuario evaluado
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Usuario que realiza la evaluación
     */
    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    /**
     * Obtener el tipo de evaluación en español
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'quarterly' => 'Trimestral',
            'biannual' => 'Semestral',
            'annual' => 'Anual',
            'probationary' => 'Prueba',
            'promotion' => 'Promoción',
            'custom' => 'Personalizada',
            default => $this->type,
        };
    }

    /**
     * Obtener el estado en español
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Borrador',
            'in_progress' => 'En Progreso',
            'completed' => 'Completada',
            'approved' => 'Aprobada',
            'rejected' => 'Rechazada',
            default => $this->status,
        };
    }

    /**
     * Verificar si la evaluación está vencida (next_evaluation_date pasada)
     */
    public function isOverdue(): bool
    {
        if (!$this->next_evaluation_date) {
            return false;
        }
        return $this->next_evaluation_date->isPast();
    }

    /**
     * Verificar si la evaluación está próxima (en los próximos 30 días)
     */
    public function isUpcoming(): bool
    {
        if (!$this->next_evaluation_date) {
            return false;
        }
        return $this->next_evaluation_date->isFuture() 
            && $this->next_evaluation_date->diffInDays(now()) <= 30;
    }
}
