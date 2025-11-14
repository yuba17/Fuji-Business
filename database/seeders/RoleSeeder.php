<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Director',
                'slug' => 'director',
                'description' => 'Director con acceso completo a todos los planes, áreas y funcionalidades. Puede aprobar planes y gestionar el sistema completo.',
                'permissions' => [
                    'plans.create',
                    'plans.edit',
                    'plans.delete',
                    'plans.approve',
                    'areas.manage',
                    'users.manage',
                    'scenarios.create',
                    'decisions.create',
                    'risks.view_all',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Manager responsable de un área. Puede crear y gestionar planes de su área, KPIs, tareas y riesgos.',
                'permissions' => [
                    'plans.create',
                    'plans.edit',
                    'kpis.manage',
                    'tasks.manage',
                    'risks.manage',
                    'roadmaps.manage',
                    'decisions.create',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Técnico',
                'slug' => 'tecnico',
                'description' => 'Técnico que puede ver planes asignados, gestionar sus tareas y añadir comentarios.',
                'permissions' => [
                    'plans.view',
                    'tasks.manage_own',
                    'tasks.comment',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Visualización',
                'slug' => 'visualizacion',
                'description' => 'Rol de solo lectura para visualización de dashboards y reportes. No puede crear ni editar contenido.',
                'permissions' => [
                    'dashboards.view',
                    'plans.view',
                    'reports.view',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
