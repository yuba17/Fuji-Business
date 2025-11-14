<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MilestoneDependency extends Model
{
    protected $fillable = [
        'predecessor_id',
        'successor_id',
        'type',
        'lag_days',
    ];

    protected function casts(): array
    {
        return [
            'lag_days' => 'integer',
        ];
    }

    /**
     * Hito predecesor
     */
    public function predecessor(): BelongsTo
    {
        return $this->belongsTo(Milestone::class, 'predecessor_id');
    }

    /**
     * Hito sucesor
     */
    public function successor(): BelongsTo
    {
        return $this->belongsTo(Milestone::class, 'successor_id');
    }

    /**
     * Obtener el tipo de dependencia en español
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'fs' => 'Finish-to-Start',
            'ss' => 'Start-to-Start',
            'ff' => 'Finish-to-Finish',
            'sf' => 'Start-to-Finish',
            default => $this->type,
        };
    }
}
