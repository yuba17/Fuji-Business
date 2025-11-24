<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InfrastructureAttribute extends Model
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
        $options = static::query()
            ->ofType($type)
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (self $attr) => [
                // Usar el slug como valor (lo que se guarda en la BD) y el name como etiqueta (lo que se muestra)
                $attr->slug ?: \Illuminate\Support\Str::slug($attr->name) => $attr->name
            ])
            ->toArray();
        
        // Mapear valores de enum conocidos a sus valores correctos en la BD
        return static::mapEnumValues($type, $options);
    }
    
    /**
     * Mapea los valores de los atributos a los valores correctos del enum en la base de datos
     */
    protected static function mapEnumValues(string $type, array $options): array
    {
        $enumMappings = [
            'class' => [
                'hardware' => 'hardware',
                'licencia' => 'license',
                'license' => 'license',
            ],
            'acquisition_status' => [
                'comprado' => 'purchased',
                'purchased' => 'purchased',
                'por-comprar' => 'to_purchase',
                'to-purchase' => 'to_purchase',
                'to_purchase' => 'to_purchase',
                'planificado' => 'planned',
                'planned' => 'planned',
            ],
            'operational_status' => [
                'activo' => 'active',
                'active' => 'active',
                'mantenimiento' => 'maintenance',
                'maintenance' => 'maintenance',
                'deprecado' => 'deprecated',
                'deprecated' => 'deprecated',
                'planificado' => 'planned',
                'planned' => 'planned',
            ],
        ];
        
        // Si hay un mapeo para este tipo, aplicarlo
        if (isset($enumMappings[$type])) {
            $mapped = [];
            foreach ($options as $key => $label) {
                $normalizedKey = strtolower($key);
                // Buscar el valor mapeado, o usar el original si no hay mapeo
                $mappedKey = $enumMappings[$type][$normalizedKey] ?? $key;
                $mapped[$mappedKey] = $label;
            }
            return $mapped;
        }
        
        // Para tipos sin enum (type, category, provider), devolver tal cual
        return $options;
    }
}
