<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Area extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_area_id',
        'director_id',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($area) {
            if (empty($area->slug)) {
                $area->slug = Str::slug($area->name);
            }
        });
    }

    /**
     * Área padre (si es subárea)
     */
    public function parentArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'parent_area_id');
    }

    /**
     * Subáreas
     */
    public function subAreas(): HasMany
    {
        return $this->hasMany(Area::class, 'parent_area_id');
    }

    /**
     * Director del área
     */
    public function director(): BelongsTo
    {
        return $this->belongsTo(User::class, 'director_id');
    }

    /**
     * Managers del área
     */
    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Planes del área
     */
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * KPIs del área
     */
    public function kpis(): HasMany
    {
        return $this->hasMany(Kpi::class);
    }

    /**
     * Riesgos del área
     */
    public function risks(): HasMany
    {
        return $this->hasMany(Risk::class);
    }

    /**
     * Tareas del área
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
