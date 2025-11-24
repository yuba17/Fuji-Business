<?php

namespace Database\Seeders;

use App\Models\InfrastructureAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InfrastructureAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $definitions = [
            'class' => [
                ['name' => 'Hardware', 'slug' => 'hardware'],
                ['name' => 'Licencia', 'slug' => 'license'],
            ],
            'acquisition_status' => [
                ['name' => 'Comprado', 'slug' => 'purchased'],
                ['name' => 'Por comprar', 'slug' => 'to_purchase'],
                ['name' => 'Planificado', 'slug' => 'planned'],
            ],
            'type' => ['Servidor', 'Portátil', 'Antena WiFi', 'Switch', 'Router', 'Firewall', 'Almacenamiento', 'Software'],
            'category' => ['Infraestructura', 'Seguridad', 'Networking', 'Productividad', 'Desarrollo'],
            'operational_status' => [
                ['name' => 'Activo', 'slug' => 'active'],
                ['name' => 'Mantenimiento', 'slug' => 'maintenance'],
                ['name' => 'Deprecado', 'slug' => 'deprecated'],
                ['name' => 'Planificado', 'slug' => 'planned'],
            ],
            'provider' => ['AWS', 'Azure', 'GCP', 'On-premise'],
        ];

        foreach ($definitions as $type => $items) {
            foreach ($items as $index => $item) {
                if (is_array($item)) {
                    // Para items con slug explícito (enums)
                    InfrastructureAttribute::firstOrCreate(
                        [
                            'attribute_type' => $type,
                            'slug' => $item['slug'],
                        ],
                        [
                            'name' => $item['name'],
                            'order' => $index,
                            'is_active' => true,
                        ]
                    );
                } else {
                    // Para items simples (solo nombre)
                    InfrastructureAttribute::firstOrCreate(
                        [
                            'attribute_type' => $type,
                            'slug' => Str::slug($item),
                        ],
                        [
                            'name' => $item,
                            'order' => $index,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}

