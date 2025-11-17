<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competency extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'level_type',
        'is_critical',
        'area_id',
        'internal_role_id',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_critical' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Área asociada a esta competencia
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Rol interno asociado a esta competencia
     */
    public function internalRole(): BelongsTo
    {
        return $this->belongsTo(InternalRole::class);
    }

    /**
     * Usuarios que tienen esta competencia (con niveles)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_competencies')
            ->withPivot('current_level', 'target_level', 'last_assessed_at', 'assessed_by', 'notes')
            ->withTimestamps();
    }

    /**
     * Evaluaciones de esta competencia
     */
    public function userCompetencies(): HasMany
    {
        return $this->hasMany(UserCompetency::class);
    }
}
