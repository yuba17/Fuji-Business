<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PlanSection extends Model
{
    protected $fillable = [
        'plan_id',
        'title',
        'slug',
        'content',
        'type',
        'order',
        'is_required',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'metadata' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($section) {
            if (empty($section->slug)) {
                $section->slug = Str::slug($section->title);
            }
        });
    }

    /**
     * Plan al que pertenece esta sección
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
