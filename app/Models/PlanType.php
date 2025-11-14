<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PlanType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'template_sections',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'template_sections' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($planType) {
            if (empty($planType->slug)) {
                $planType->slug = Str::slug($planType->name);
            }
        });
    }

    /**
     * Planes de este tipo
     */
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * Verificar si es Plan de Negocio
     */
    public function isNegocio(): bool
    {
        return $this->slug === 'plan-de-negocio';
    }

    /**
     * Verificar si es Plan Comercial
     */
    public function isComercial(): bool
    {
        return $this->slug === 'plan-comercial';
    }

    /**
     * Verificar si es Plan de Desarrollo Interno
     */
    public function isDesarrolloInterno(): bool
    {
        return $this->slug === 'plan-de-desarrollo-interno';
    }
}
