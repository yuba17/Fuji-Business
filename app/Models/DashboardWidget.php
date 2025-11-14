<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardWidget extends Model
{
    protected $fillable = [
        'dashboard_id',
        'widget_type',
        'title',
        'config',
        'position_x',
        'position_y',
        'width',
        'height',
        'is_visible',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'array',
            'position_x' => 'integer',
            'position_y' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'is_visible' => 'boolean',
            'order' => 'integer',
        ];
    }

    /**
     * Dashboard al que pertenece el widget
     */
    public function dashboard(): BelongsTo
    {
        return $this->belongsTo(Dashboard::class);
    }
}
