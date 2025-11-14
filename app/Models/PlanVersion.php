<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanVersion extends Model
{
    protected $fillable = [
        'plan_id',
        'version_number',
        'snapshot',
        'created_by',
        'change_summary',
    ];

    protected function casts(): array
    {
        return [
            'snapshot' => 'array',
        ];
    }

    /**
     * Plan al que pertenece esta versión
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Usuario que creó esta versión
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
