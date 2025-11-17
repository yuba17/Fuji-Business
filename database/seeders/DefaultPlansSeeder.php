<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanType;
use App\Models\Area;
use App\Models\User;
use App\Services\PlanTemplateService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DefaultPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templateService = new PlanTemplateService();
        
        // Obtener los tres tipos de planes principales
        $planNegocio = PlanType::where('slug', 'plan-de-negocio')->first();
        $planComercial = PlanType::where('slug', 'plan-comercial')->first();
        $planDesarrollo = PlanType::where('slug', 'plan-de-desarrollo-interno')->first();
        
        if (!$planNegocio || !$planComercial || !$planDesarrollo) {
            $this->command->warn('Los tipos de planes no existen. Ejecuta primero PlanTypeSeeder.');
            return;
        }
        
        // Obtener el primer área activa o crear una por defecto
        $area = Area::where('is_active', true)->first();
        if (!$area) {
            $area = Area::create([
                'name' => 'Área Principal',
                'slug' => 'area-principal',
                'description' => 'Área principal por defecto',
                'is_active' => true,
                'order' => 1,
            ]);
        }
        
        // Obtener el primer usuario administrador o el primer usuario
        $user = User::whereHas('roles', function($q) {
            $q->where('name', 'director');
        })->first() ?? User::first();
        
        if (!$user) {
            $this->command->warn('No hay usuarios en el sistema. Crea un usuario primero.');
            return;
        }
        
        $currentYear = date('Y');
        
        // 1. Plan de Negocio
        $planNegocioInstance = Plan::firstOrCreate(
            [
                'slug' => 'plan-de-negocio-' . $currentYear,
            ],
            [
                'name' => 'Plan de Negocio ' . $currentYear,
                'description' => 'Plan estratégico de negocio para el año ' . $currentYear . '. Define la visión, objetivos y modelo económico del área.',
                'plan_type_id' => $planNegocio->id,
                'area_id' => $area->id,
                'manager_id' => $user->id,
                'director_id' => $user->id,
                'status' => 'draft',
                'start_date' => $currentYear . '-01-01',
                'target_date' => $currentYear . '-12-31',
                'version' => 1,
                'is_current_version' => true,
            ]
        );
        
        // Crear secciones si no existen
        if ($planNegocioInstance->sections()->count() === 0) {
            $templateService->createSectionsFromTemplate($planNegocioInstance, $planNegocio);
        }
        
        // 2. Plan Comercial
        $planComercialInstance = Plan::firstOrCreate(
            [
                'slug' => 'plan-comercial-' . $currentYear,
            ],
            [
                'name' => 'Plan Comercial ' . $currentYear,
                'description' => 'Plan comercial para el año ' . $currentYear . '. Define qué se vende, a quién, cómo y con qué objetivos comerciales.',
                'plan_type_id' => $planComercial->id,
                'area_id' => $area->id,
                'manager_id' => $user->id,
                'director_id' => $user->id,
                'status' => 'draft',
                'start_date' => $currentYear . '-01-01',
                'target_date' => $currentYear . '-12-31',
                'version' => 1,
                'is_current_version' => true,
            ]
        );
        
        // Crear secciones si no existen
        if ($planComercialInstance->sections()->count() === 0) {
            $templateService->createSectionsFromTemplate($planComercialInstance, $planComercial);
        }
        
        // 3. Plan de Desarrollo Interno
        $planDesarrolloInstance = Plan::firstOrCreate(
            [
                'slug' => 'plan-de-desarrollo-interno-' . $currentYear,
            ],
            [
                'name' => 'Plan de Desarrollo Interno ' . $currentYear,
                'description' => 'Plan de desarrollo interno para el año ' . $currentYear . '. Recoge cómo se organiza, escala y mejora internamente el área.',
                'plan_type_id' => $planDesarrollo->id,
                'area_id' => $area->id,
                'manager_id' => $user->id,
                'director_id' => $user->id,
                'status' => 'draft',
                'start_date' => $currentYear . '-01-01',
                'target_date' => $currentYear . '-12-31',
                'version' => 1,
                'is_current_version' => true,
            ]
        );
        
        // Crear secciones si no existen
        if ($planDesarrolloInstance->sections()->count() === 0) {
            $templateService->createSectionsFromTemplate($planDesarrolloInstance, $planDesarrollo);
        }
        
        $this->command->info('Planes por defecto creados/verificados correctamente.');
    }
}

