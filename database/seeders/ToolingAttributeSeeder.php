<?php

namespace Database\Seeders;

use App\Models\ToolingAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ToolingAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $definitions = [
            'type' => [
                ['name' => 'Ofensiva', 'slug' => 'ofensiva'],
                ['name' => 'Automatización', 'slug' => 'automatizacion'],
                ['name' => 'Laboratorio', 'slug' => 'laboratorio'],
                ['name' => 'Reporting', 'slug' => 'reporting'],
                ['name' => 'Soporte', 'slug' => 'soporte'],
                ['name' => 'Otro', 'slug' => 'otro'],
            ],
            'status' => [
                ['name' => 'Idea', 'slug' => 'idea'],
                ['name' => 'En Evaluación', 'slug' => 'en_evaluacion'],
                ['name' => 'En Desarrollo', 'slug' => 'en_desarrollo'],
                ['name' => 'Beta', 'slug' => 'beta'],
                ['name' => 'Producción', 'slug' => 'produccion'],
                ['name' => 'Obsoleta', 'slug' => 'obsoleta'],
            ],
            'criticality' => [
                ['name' => 'Alta', 'slug' => 'alta'],
                ['name' => 'Media', 'slug' => 'media'],
                ['name' => 'Baja', 'slug' => 'baja'],
            ],
            'milestone_type' => [
                ['name' => 'Nueva Funcionalidad', 'slug' => 'nueva_funcionalidad'],
                ['name' => 'Mejora de Estabilidad', 'slug' => 'mejora_estabilidad'],
                ['name' => 'Mejora de Rendimiento', 'slug' => 'mejora_rendimiento'],
                ['name' => 'Ampliación a Nuevos Escenarios', 'slug' => 'ampliacion_escenarios'],
                ['name' => 'Integración', 'slug' => 'integracion'],
                ['name' => 'Otro', 'slug' => 'otro'],
            ],
            'milestone_priority' => [
                ['name' => 'Alta', 'slug' => 'alta'],
                ['name' => 'Media', 'slug' => 'media'],
                ['name' => 'Baja', 'slug' => 'baja'],
            ],
            'milestone_status' => [
                ['name' => 'Planificado', 'slug' => 'planificado'],
                ['name' => 'En Curso', 'slug' => 'en_curso'],
                ['name' => 'Completado', 'slug' => 'completado'],
                ['name' => 'Bloqueado', 'slug' => 'bloqueado'],
            ],
        ];

        foreach ($definitions as $type => $items) {
            foreach ($items as $index => $item) {
                if (is_array($item)) {
                    // Para items con slug explícito (enums)
                    ToolingAttribute::firstOrCreate(
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
                    ToolingAttribute::firstOrCreate(
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

