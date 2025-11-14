<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dashboard extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'type',
        'is_default',
        'layout_config',
        'is_shared',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_shared' => 'boolean',
            'layout_config' => 'array',
        ];
    }

    /**
     * Usuario propietario del dashboard
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Widgets del dashboard
     */
    public function widgets(): HasMany
    {
        return $this->hasMany(DashboardWidget::class)->orderBy('order');
    }
}
