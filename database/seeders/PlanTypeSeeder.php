<?php

namespace Database\Seeders;

use App\Models\PlanType;
use Illuminate\Database\Seeder;

class PlanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planTypes = [
            [
                'name' => 'Plan de Negocio',
                'slug' => 'plan-de-negocio',
                'description' => 'Plan estratégico de negocio que establece la visión, objetivos y modelo económico del área.',
                'template_sections' => [
                    ['title' => 'Resumen Ejecutivo', 'slug' => 'resumen-ejecutivo', 'order' => 1, 'is_required' => true],
                    ['title' => 'Análisis de Mercado', 'slug' => 'analisis-de-mercado', 'order' => 2, 'is_required' => true],
                    ['title' => 'Propuesta de Valor', 'slug' => 'propuesta-de-valor', 'order' => 3, 'is_required' => true],
                    ['title' => 'Servicios Estratégicos', 'slug' => 'servicios-estrategicos', 'order' => 4, 'is_required' => true],
                    ['title' => 'Modelo de Ingresos', 'slug' => 'modelo-de-ingresos', 'order' => 5, 'is_required' => true],
                    ['title' => 'Previsiones Financieras', 'slug' => 'previsiones-financieras', 'order' => 6, 'is_required' => true],
                    ['title' => 'Riesgos Estratégicos', 'slug' => 'riesgos-estrategicos', 'order' => 7, 'is_required' => false],
                    ['title' => 'Roadmap 24-36 meses', 'slug' => 'roadmap', 'order' => 8, 'is_required' => true],
                ],
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Plan Comercial',
                'slug' => 'plan-comercial',
                'description' => 'Plan que define qué se vende, a quién, cómo y con qué objetivos comerciales.',
                'template_sections' => [
                    ['title' => 'Portafolio de Servicios', 'slug' => 'portafolio-servicios', 'order' => 1, 'is_required' => true],
                    ['title' => 'Sectores Objetivo', 'slug' => 'sectores-objetivo', 'order' => 2, 'is_required' => true],
                    ['title' => 'AS IS Sectorial', 'slug' => 'as-is-sectorial', 'order' => 3, 'is_required' => true],
                    ['title' => 'TO BE Sectorial', 'slug' => 'to-be-sectorial', 'order' => 4, 'is_required' => true],
                    ['title' => 'Pricing Estratégico', 'slug' => 'pricing-estrategico', 'order' => 5, 'is_required' => true],
                    ['title' => 'Go-To-Market', 'slug' => 'go-to-market', 'order' => 6, 'is_required' => true],
                    ['title' => 'Proceso Comercial', 'slug' => 'proceso-comercial', 'order' => 7, 'is_required' => true],
                    ['title' => 'Roadmap Comercial', 'slug' => 'roadmap-comercial', 'order' => 8, 'is_required' => true],
                    ['title' => 'KPIs Comerciales', 'slug' => 'kpis-comerciales', 'order' => 9, 'is_required' => true],
                ],
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Plan de Desarrollo Interno',
                'slug' => 'plan-de-desarrollo-interno',
                'description' => 'Plan que recoge cómo se organiza, escala y mejora internamente el área.',
                'template_sections' => [
                    ['title' => 'Estructura de Equipo', 'slug' => 'estructura-equipo', 'order' => 1, 'is_required' => true],
                    ['title' => 'Competencias', 'slug' => 'competencias', 'order' => 2, 'is_required' => true],
                    ['title' => 'Infraestructura Técnica', 'slug' => 'infraestructura-tecnica', 'order' => 3, 'is_required' => true],
                    ['title' => 'Procesos Operativos', 'slug' => 'procesos-operativos', 'order' => 4, 'is_required' => true],
                    ['title' => 'Calidad', 'slug' => 'calidad', 'order' => 5, 'is_required' => true],
                    ['title' => 'Formación', 'slug' => 'formacion', 'order' => 6, 'is_required' => true],
                    ['title' => 'I+D', 'slug' => 'i-d', 'order' => 7, 'is_required' => false],
                    ['title' => 'OPSEC', 'slug' => 'opsec', 'order' => 8, 'is_required' => false],
                    ['title' => 'Roadmap Operativo', 'slug' => 'roadmap-operativo', 'order' => 9, 'is_required' => true],
                ],
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Plan de Área',
                'slug' => 'plan-de-area',
                'description' => 'Plan específico de un área funcional (Red Team, Pentest, I+D, etc.).',
                'template_sections' => [
                    ['title' => 'Contexto y Objetivos', 'slug' => 'contexto-objetivos', 'order' => 1, 'is_required' => true],
                    ['title' => 'Estrategia', 'slug' => 'estrategia', 'order' => 2, 'is_required' => true],
                    ['title' => 'Alcance', 'slug' => 'alcance', 'order' => 3, 'is_required' => true],
                    ['title' => 'KPIs', 'slug' => 'kpis', 'order' => 4, 'is_required' => true],
                    ['title' => 'Roadmap', 'slug' => 'roadmap', 'order' => 5, 'is_required' => true],
                    ['title' => 'Riesgos', 'slug' => 'riesgos', 'order' => 6, 'is_required' => false],
                ],
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Plan de Equipo',
                'slug' => 'plan-de-equipo',
                'description' => 'Plan específico de un equipo dentro de un área.',
                'template_sections' => [
                    ['title' => 'Objetivos del Equipo', 'slug' => 'objetivos-equipo', 'order' => 1, 'is_required' => true],
                    ['title' => 'Actividades', 'slug' => 'actividades', 'order' => 2, 'is_required' => true],
                    ['title' => 'Tareas', 'slug' => 'tareas', 'order' => 3, 'is_required' => true],
                ],
                'is_active' => true,
                'order' => 5,
            ],
        ];

        foreach ($planTypes as $planType) {
            PlanType::updateOrCreate(
                ['slug' => $planType['slug']],
                $planType
            );
        }
    }
}
