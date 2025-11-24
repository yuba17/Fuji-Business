<?php

namespace Database\Seeders;

use App\Models\CertificationAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CertificationAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $definitions = [
            'provider' => [
                'AWS',
                'Microsoft',
                'Google Cloud',
                'Cisco',
                'CompTIA',
                'Offensive Security',
            ],
            'category' => [
                'Cloud',
                'Seguridad',
                'Networking',
                'Data & AI',
                'DevOps',
                'Project Management',
            ],
            'level' => [
                'Foundation',
                'Associate',
                'Professional',
                'Expert',
            ],
        ];

        foreach ($definitions as $type => $items) {
            foreach ($items as $index => $name) {
                CertificationAttribute::firstOrCreate(
                    [
                        'attribute_type' => $type,
                        'slug' => Str::slug($name),
                    ],
                    [
                        'name' => $name,
                        'order' => $index,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}

