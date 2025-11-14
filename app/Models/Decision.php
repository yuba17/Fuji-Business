<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Decision extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'decision_date',
        'proponent_id',
        'impact_type',
        'status',
        'alternatives_considered',
        'rationale',
        'expected_impact',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'decision_date' => 'date',
            'metadata' => 'array',
        ];
    }

    /**
     * Proponente de la decisión
     */
    public function proponent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proponent_id');
    }

    /**
     * Planes relacionados
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class);
    }

    /**
     * Obtener el estado en español
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'proposed' => 'Propuesta',
            'discussion' => 'En Discusión',
            'pending_approval' => 'Pendiente de Aprobación',
            'approved' => 'Aprobada',
            'rejected' => 'Rechazada',
            'implemented' => 'Implementada',
            'reviewed' => 'Revisada',
            default => $this->status,
        };
    }
}
