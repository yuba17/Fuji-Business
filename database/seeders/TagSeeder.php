<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Tags por dominio
            ['name' => 'Estrategia', 'slug' => 'estrategia', 'category' => 'domain', 'color' => '#E11D48', 'is_predefined' => true],
            ['name' => 'Operación', 'slug' => 'operacion', 'category' => 'domain', 'color' => '#0EA5E9', 'is_predefined' => true],
            ['name' => 'Comercial', 'slug' => 'comercial', 'category' => 'domain', 'color' => '#F97316', 'is_predefined' => true],
            ['name' => 'RRHH', 'slug' => 'rrhh', 'category' => 'domain', 'color' => '#6366F1', 'is_predefined' => true],
            ['name' => 'Tooling', 'slug' => 'tooling', 'category' => 'domain', 'color' => '#10B981', 'is_predefined' => true],
            ['name' => 'Innovación', 'slug' => 'innovacion', 'category' => 'domain', 'color' => '#F59E0B', 'is_predefined' => true],
            ['name' => 'Compliance', 'slug' => 'compliance', 'category' => 'domain', 'color' => '#8B5CF6', 'is_predefined' => true],
            ['name' => 'Riesgo', 'slug' => 'riesgo', 'category' => 'domain', 'color' => '#EF4444', 'is_predefined' => true],
            
            // Tags por prioridad
            ['name' => 'Crítico', 'slug' => 'critico', 'category' => 'priority', 'color' => '#DC2626', 'is_predefined' => true],
            ['name' => 'Alto', 'slug' => 'alto', 'category' => 'priority', 'color' => '#F97316', 'is_predefined' => true],
            ['name' => 'Medio', 'slug' => 'medio', 'category' => 'priority', 'color' => '#F59E0B', 'is_predefined' => true],
            ['name' => 'Bajo', 'slug' => 'bajo', 'category' => 'priority', 'color' => '#10B981', 'is_predefined' => true],
            
            // Tags por estado
            ['name' => 'Pendiente', 'slug' => 'pendiente', 'category' => 'status', 'color' => '#6B7280', 'is_predefined' => true],
            ['name' => 'En Progreso', 'slug' => 'en-progreso', 'category' => 'status', 'color' => '#0EA5E9', 'is_predefined' => true],
            ['name' => 'Bloqueado', 'slug' => 'bloqueado', 'category' => 'status', 'color' => '#EF4444', 'is_predefined' => true],
            ['name' => 'Completado', 'slug' => 'completado', 'category' => 'status', 'color' => '#10B981', 'is_predefined' => true],
            
            // Tags por tipo
            ['name' => 'Técnico', 'slug' => 'tecnico', 'category' => 'type', 'color' => '#6366F1', 'is_predefined' => true],
            ['name' => 'Proceso', 'slug' => 'proceso', 'category' => 'type', 'color' => '#8B5CF6', 'is_predefined' => true],
            ['name' => 'Organizativo', 'slug' => 'organizativo', 'category' => 'type', 'color' => '#EC4899', 'is_predefined' => true],
            ['name' => 'Financiero', 'slug' => 'financiero', 'category' => 'type', 'color' => '#10B981', 'is_predefined' => true],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['slug' => $tag['slug']],
                $tag
            );
        }
    }
}
