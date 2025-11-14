<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                'name' => 'Red Team',
                'slug' => 'red-team',
                'description' => 'Equipo de seguridad ofensiva especializado en simulaciones de ataque y ejercicios de red team.',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Pentest',
                'slug' => 'pentest',
                'description' => 'Equipo especializado en pruebas de penetración y auditorías de seguridad.',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Active Directory',
                'slug' => 'active-directory',
                'description' => 'Equipo especializado en seguridad de Active Directory y entornos Windows.',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'I+D',
                'slug' => 'i-d',
                'description' => 'Equipo de Investigación y Desarrollo en ciberseguridad ofensiva.',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Cloud Security',
                'slug' => 'cloud-security',
                'description' => 'Equipo especializado en seguridad de entornos cloud (AWS, Azure, GCP).',
                'is_active' => true,
                'order' => 5,
            ],
        ];

        foreach ($areas as $area) {
            Area::updateOrCreate(
                ['slug' => $area['slug']],
                $area
            );
        }
    }
}
