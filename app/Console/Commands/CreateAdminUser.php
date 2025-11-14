<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--email=admin@strategos.local} {--name=Administrador} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un usuario administrador con rol Director y acceso total';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $name = $this->option('name');
        $password = $this->option('password') ?: Str::random(16);

        // Verificar si el usuario ya existe
        if (User::where('email', $email)->exists()) {
            $this->error("El usuario con email {$email} ya existe.");
            return 1;
        }

        // Obtener rol Director
        $directorRole = Role::where('slug', 'director')->first();
        
        if (!$directorRole) {
            $this->error("El rol 'director' no existe. Ejecuta primero: php artisan db:seed --class=RoleSeeder");
            return 1;
        }

        // Crear usuario
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        // Asignar rol Director
        $user->roles()->attach($directorRole);

        $this->info("✅ Usuario administrador creado exitosamente!");
        $this->newLine();
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("📧 Email: {$email}");
        $this->info("👤 Nombre: {$name}");
        $this->info("🔑 Contraseña: {$password}");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();
        $this->warn("⚠️  IMPORTANTE: Guarda estas credenciales en un lugar seguro.");
        $this->warn("⚠️  La contraseña no se mostrará nuevamente.");

        return 0;
    }
}
