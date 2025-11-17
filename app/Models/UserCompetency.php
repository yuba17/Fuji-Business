<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCompetency extends Model
{
    protected $fillable = [
        'user_id',
        'competency_id',
        'current_level',
        'target_level',
        'last_assessed_at',
        'assessed_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'last_assessed_at' => 'date',
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
     * Competencia evaluada
     */
    public function competency(): BelongsTo
    {
        return $this->belongsTo(Competency::class);
    }

    /**
     * Usuario que realizó la evaluación
     */
    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    /**
     * Calcular gap (diferencia entre objetivo y actual)
     */
    public function getGapAttribute(): int
    {
        if (!$this->target_level) {
            return 0;
        }
        return $this->target_level - $this->current_level;
    }

    /**
     * Verificar si está alineado (actual >= objetivo)
     */
    public function isAligned(): bool
    {
        if (!$this->target_level) {
            return true;
        }
        return $this->current_level >= $this->target_level;
    }
}
