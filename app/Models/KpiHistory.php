<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiHistory extends Model
{
    protected $fillable = [
        'kpi_id',
        'value',
        'recorded_at',
        'updated_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:4',
            'recorded_at' => 'date',
        ];
    }

    /**
     * KPI al que pertenece este historial
     */
    public function kpi(): BelongsTo
    {
        return $this->belongsTo(Kpi::class);
    }

    /**
     * Usuario que actualizó el valor
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
