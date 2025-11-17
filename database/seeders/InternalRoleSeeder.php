<?php

namespace Database\Seeders;

use App\Models\InternalRole;
use Illuminate\Database\Seeder;

class InternalRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Director Red Team',
                'slug' => 'director-red-team',
                'track' => 'red_team',
                'level' => 'director',
                'role_type' => 'director',
                'is_critical' => true,
                'avg_cost_year' => 95000,
                'billable_ratio' => 0.4,
                'max_direct_reports' => 8,
                'can_see_all_teams' => true,
                'can_approve_plans' => true,
                'can_approve_certifications' => true,
            ],
            [
                'name' => 'Manager Red Team',
                'slug' => 'manager-red-team',
                'track' => 'red_team',
                'level' => 'lead',
                'role_type' => 'manager',
                'is_critical' => true,
                'avg_cost_year' => 80000,
                'billable_ratio' => 0.6,
                'max_direct_reports' => 6,
                'can_see_all_teams' => false,
                'can_approve_plans' => false,
                'can_approve_certifications' => true,
            ],
            [
                'name' => 'Senior Red Team',
                'slug' => 'senior-red-team',
                'track' => 'red_team',
                'level' => 'senior',
                'role_type' => 'individual_contributor',
                'is_critical' => false,
                'avg_cost_year' => 65000,
                'billable_ratio' => 0.75,
                'max_direct_reports' => null,
                'can_see_all_teams' => false,
                'can_approve_plans' => false,
                'can_approve_certifications' => false,
            ],
            [
                'name' => 'Junior Red Team',
                'slug' => 'junior-red-team',
                'track' => 'red_team',
                'level' => 'junior',
                'role_type' => 'individual_contributor',
                'is_critical' => false,
                'avg_cost_year' => 38000,
                'billable_ratio' => 0.7,
                'max_direct_reports' => null,
                'can_see_all_teams' => false,
                'can_approve_plans' => false,
                'can_approve_certifications' => false,
            ],
            [
                'name' => 'Cloud Security Engineer',
                'slug' => 'cloud-security-engineer',
                'track' => 'cloud_security',
                'level' => 'senior',
                'role_type' => 'individual_contributor',
                'is_critical' => true,
                'avg_cost_year' => 70000,
                'billable_ratio' => 0.75,
                'max_direct_reports' => null,
                'can_see_all_teams' => false,
                'can_approve_plans' => false,
                'can_approve_certifications' => false,
            ],
        ];

        foreach ($roles as $data) {
            InternalRole::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
