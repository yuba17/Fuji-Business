<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CertificationAttribute extends Model
{
    protected $fillable = [
        'attribute_type',
        'name',
        'slug',
        'description',
        'order',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('attribute_type', $type);
    }

    public static function optionsFor(string $type): array
    {
        return Cache::remember("cert_attr_options_{$type}", 600, function () use ($type) {
            return static::query()
                ->ofType($type)
                ->where('is_active', true)
                ->orderBy('order')
                ->orderBy('name')
                ->get()
                ->mapWithKeys(fn (self $attr) => [
                    $attr->slug ?: Str::slug($attr->name) => $attr->name,
                ])
                ->toArray();
        });
    }

    public static function labelFor(string $type, ?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $options = static::optionsFor($type);

        return $options[$value] ?? $value;
    }

    public static function forgetCache(?string $type = null): void
    {
        if ($type) {
            Cache::forget("cert_attr_options_{$type}");
            return;
        }

        foreach (array_keys(static::attributeTypes()) as $attributeType) {
            Cache::forget("cert_attr_options_{$attributeType}");
        }
    }

    public static function attributeTypes(): array
    {
        return [
            'provider' => 'Proveedor',
            'category' => 'Categoría',
            'level' => 'Nivel',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $attribute) {
            if (empty($attribute->slug)) {
                $attribute->slug = Str::slug($attribute->name);
            }
        });

        static::saved(function (self $attribute) {
            static::forgetCache($attribute->attribute_type);
        });

        static::deleted(function (self $attribute) {
            static::forgetCache($attribute->attribute_type);
        });
    }
}

