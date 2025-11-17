<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeders base
        $this->call([
            RoleSeeder::class,
            PlanTypeSeeder::class,
            AreaSeeder::class,
            TagSeeder::class,
            InternalRoleSeeder::class,
            ServiceLineSeeder::class,
            DefaultPlansSeeder::class,
            CompetencySeeder::class,
            CertificationSeeder::class,
        ]);

        // Usuarios de prueba conectados a roles y áreas
        $directorRole = Role::where('slug', 'director')->first();
        $managerRole = Role::where('slug', 'manager')->first();
        $tecnicoRole = Role::where('slug', 'tecnico')->first();

        $redTeamArea = \App\Models\Area::where('slug', 'red-team')->first();
        $cloudArea = \App\Models\Area::where('slug', 'cloud-security')->first();

        $directorInternal = \App\Models\InternalRole::where('slug', 'director-red-team')->first();
        $managerInternal = \App\Models\InternalRole::where('slug', 'manager-red-team')->first();
        $seniorInternal = \App\Models\InternalRole::where('slug', 'senior-red-team')->first();
        $juniorInternal = \App\Models\InternalRole::where('slug', 'junior-red-team')->first();
        $cloudInternal = \App\Models\InternalRole::where('slug', 'cloud-security-engineer')->first();

        // Director
        $director = User::factory()->create([
            'name' => 'Director Red Team',
            'email' => 'director@example.com',
            'area_id' => $redTeamArea?->id,
            'internal_role_id' => $directorInternal?->id,
        ]);
        if ($directorRole) {
            $director->roles()->attach($directorRole);
        }

        // Manager
        $manager = User::factory()->create([
            'name' => 'Manager Red Team',
            'email' => 'manager@example.com',
            'manager_id' => $director->id,
            'area_id' => $redTeamArea?->id,
            'internal_role_id' => $managerInternal?->id,
        ]);
        if ($managerRole) {
            $manager->roles()->attach($managerRole);
        }

        // Senior técnico
        $senior = User::factory()->create([
            'name' => 'Senior Red Team',
            'email' => 'senior@example.com',
            'manager_id' => $manager->id,
            'area_id' => $redTeamArea?->id,
            'internal_role_id' => $seniorInternal?->id,
        ]);
        if ($tecnicoRole) {
            $senior->roles()->attach($tecnicoRole);
        }

        // Junior técnico
        $junior = User::factory()->create([
            'name' => 'Junior Red Team',
            'email' => 'junior@example.com',
            'manager_id' => $manager->id,
            'area_id' => $redTeamArea?->id,
            'internal_role_id' => $juniorInternal?->id,
        ]);
        if ($tecnicoRole) {
            $junior->roles()->attach($tecnicoRole);
        }

        // Cloud engineer en otra área para probar diferenciación
        $cloud = User::factory()->create([
            'name' => 'Cloud Security Engineer',
            'email' => 'cloud@example.com',
            'manager_id' => $director->id,
            'area_id' => $cloudArea?->id,
            'internal_role_id' => $cloudInternal?->id,
        ]);
        if ($tecnicoRole) {
            $cloud->roles()->attach($tecnicoRole);
        }
    }
}
