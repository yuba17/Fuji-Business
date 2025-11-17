<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\ServiceLine;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $redTeamArea = Area::where('slug', 'red-team')->first();
        $cloudArea = Area::where('slug', 'cloud-security')->first();

        if (! $redTeamArea && ! $cloudArea) {
            return;
        }

        $director = User::where('email', 'director@example.com')->first();
        $manager = User::where('email', 'manager@example.com')->first();
        $senior = User::where('email', 'senior@example.com')->first();
        $junior = User::where('email', 'junior@example.com')->first();
        $cloud = User::where('email', 'cloud@example.com')->first();

        // Líneas de servicio dentro de Seguridad Ofensiva (Red Team)
        if ($redTeamArea) {
            $poolLine = ServiceLine::updateOrCreate(
                ['slug' => 'pool'],
                [
                    'name' => 'Pool',
                    'area_id' => $redTeamArea->id,
                    'manager_id' => $manager?->id,
                    'description' => 'Pool de consultores ofensivos que se asignan dinámicamente a proyectos.',
                    'order' => 1,
                    'is_active' => true,
                ]
            );

            $redTeamLine = ServiceLine::updateOrCreate(
                ['slug' => 'red-team-line'],
                [
                    'name' => 'Red Team',
                    'area_id' => $redTeamArea->id,
                    'manager_id' => $manager?->id,
                    'description' => 'Línea de proyectos de Red Team end-to-end.',
                    'order' => 2,
                    'is_active' => true,
                ]
            );

            $ctemLine = ServiceLine::updateOrCreate(
                ['slug' => 'ctem'],
                [
                    'name' => 'CTEM',
                    'area_id' => $redTeamArea->id,
                    'manager_id' => $manager?->id,
                    'description' => 'Línea de Continuous Threat Exposure Management.',
                    'order' => 3,
                    'is_active' => true,
                ]
            );

            // Asignar personas a líneas (ejemplo simple)
            if ($senior) {
                $senior->serviceLines()->syncWithoutDetaching([$poolLine->id, $redTeamLine->id]);
            }

            if ($junior) {
                $junior->serviceLines()->syncWithoutDetaching([$poolLine->id]);
            }

            if ($manager) {
                $manager->serviceLines()->syncWithoutDetaching([$poolLine->id, $redTeamLine->id, $ctemLine->id]);
            }

            if ($director) {
                $director->serviceLines()->syncWithoutDetaching([$redTeamLine->id]);
            }
        }

        // Línea de servicio Cloud Security
        if ($cloudArea && $cloud) {
            $cloudLine = ServiceLine::updateOrCreate(
                ['slug' => 'cloud-security-line'],
                [
                    'name' => 'Cloud Security',
                    'area_id' => $cloudArea->id,
                    'manager_id' => $director?->id,
                    'description' => 'Proyectos de seguridad en entornos cloud.',
                    'order' => 1,
                    'is_active' => true,
                ]
            );

            $cloud->serviceLines()->syncWithoutDetaching([$cloudLine->id]);
        }
    }
}
