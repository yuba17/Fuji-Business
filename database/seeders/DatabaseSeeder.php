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
        ]);

        // Usuario de prueba con rol director
        $directorRole = Role::where('slug', 'director')->first();
        
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        if ($directorRole) {
            $user->roles()->attach($directorRole);
        }

        // Usuario manager de prueba
        $managerRole = Role::where('slug', 'manager')->first();
        
        $manager = User::factory()->create([
            'name' => 'Manager Test',
            'email' => 'manager@example.com',
        ]);

        if ($managerRole) {
            $manager->roles()->attach($managerRole);
        }
    }
}
