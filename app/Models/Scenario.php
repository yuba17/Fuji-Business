<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scenario extends Model
{
    protected $fillable = [
        'name',
        'description',
        'plan_id',
        'area_id',
        'created_by',
        'simulation_params',
        'results',
        'is_applied',
    ];

    protected function casts(): array
    {
        return [
            'simulation_params' => 'array',
            'results' => 'array',
            'is_applied' => 'boolean',
        ];
    }

    /**
     * Plan del escenario
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Área del escenario
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Usuario que creó el escenario
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
