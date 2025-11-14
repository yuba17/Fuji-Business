<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'category',
        'is_predefined',
        'usage_count',
    ];

    protected function casts(): array
    {
        return [
            'is_predefined' => 'boolean',
            'usage_count' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Relación polimórfica con modelos etiquetables
     */
    public function plans()
    {
        return $this->morphedByMany(Plan::class, 'taggable');
    }

    public function tasks()
    {
        return $this->morphedByMany(Task::class, 'taggable');
    }

    public function risks()
    {
        return $this->morphedByMany(Risk::class, 'taggable');
    }

    public function decisions()
    {
        return $this->morphedByMany(Decision::class, 'taggable');
    }
}
