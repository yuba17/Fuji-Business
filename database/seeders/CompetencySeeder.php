<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Competency;
use App\Models\InternalRole;
use App\Models\User;
use App\Models\UserCompetency;
use Illuminate\Database\Seeder;

class CompetencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar áreas por slug o nombre
        $seguridadOfensivaArea = Area::where('slug', 'seguridad-ofensiva')
            ->orWhere('slug', 'red-team')
            ->orWhere('name', 'like', '%Seguridad Ofensiva%')
            ->orWhere('name', 'like', '%Red Team%')
            ->first();
        
        $cloudArea = Area::where('slug', 'cloud-security')->first();
        
        // Si no hay áreas, obtener todas las activas
        $allAreas = Area::where('is_active', true)->get();
        
        if ($allAreas->isEmpty()) {
            return;
        }

        // Obtener roles internos (buscar por varios slugs posibles)
        $directorInternal = InternalRole::where('slug', 'director-red-team')
            ->orWhere('slug', 'like', '%director%')
            ->first();
        $managerInternal = InternalRole::where('slug', 'manager-red-team')
            ->orWhere('slug', 'like', '%manager%')
            ->first();
        $seniorInternal = InternalRole::where('slug', 'senior-red-team')
            ->orWhere('slug', 'like', '%senior%')
            ->first();
        $juniorInternal = InternalRole::where('slug', 'junior-red-team')
            ->orWhere('slug', 'like', '%junior%')
            ->first();

        // Obtener usuarios del área de seguridad ofensiva (o primera área disponible)
        $targetArea = $seguridadOfensivaArea ?? $allAreas->first();
        
        if (!$targetArea) {
            return;
        }
        
        $users = User::where('area_id', $targetArea->id)->with('roles')->get();
        $director = $users->where('email', 'director@example.com')->first();
        if (!$director) {
            $director = $users->filter(function($user) {
                return $user->roles->contains('slug', 'director');
            })->first();
        }
        
        $manager = $users->where('email', 'manager@example.com')->first();
        if (!$manager) {
            $manager = $users->filter(function($user) {
                return $user->roles->contains('slug', 'manager');
            })->first();
        }
        
        $senior = $users->where('email', 'senior@example.com')->first();
        if (!$senior) {
            $senior = $users->filter(function($user) use ($director) {
                return $user->id !== $director?->id && 
                       (stripos($user->email, 'senior') !== false || stripos($user->name, 'senior') !== false);
            })->first();
        }
        
        $junior = $users->where('email', 'junior@example.com')->first();
        if (!$junior) {
            $junior = $users->filter(function($user) use ($director, $manager) {
                return $user->id !== $director?->id && 
                       $user->id !== $manager?->id &&
                       (stripos($user->email, 'junior') !== false || stripos($user->name, 'junior') !== false);
            })->first();
        }

        // ============================================
        // COMPETENCIAS PARA SEGURIDAD OFENSIVA
        // ============================================
        if ($targetArea) {
            // === COMPETENCIAS TÉCNICAS ===
            $laravel = Competency::updateOrCreate(
                ['name' => 'Laravel', 'area_id' => $targetArea->id],
                [
                    'description' => 'Framework PHP para desarrollo de aplicaciones web. Dominio de Eloquent, Blade, Livewire, y arquitectura MVC.',
                    'category' => 'Técnica',
                    'level_type' => 'numeric',
                    'is_critical' => true,
                    'internal_role_id' => $seniorInternal?->id,
                    'order' => 1,
                    'is_active' => true,
                ]
            );

            $docker = Competency::updateOrCreate(
                ['name' => 'Docker', 'area_id' => $targetArea->id],
                [
                    'description' => 'Containerización y orquestación de aplicaciones. Docker Compose, Dockerfiles, y gestión de imágenes.',
                    'category' => 'Técnica',
                    'level_type' => 'numeric',
                    'is_critical' => true,
                    'internal_role_id' => $seniorInternal?->id,
                    'order' => 2,
                    'is_active' => true,
                ]
            );

            $react = Competency::updateOrCreate(
                ['name' => 'React', 'area_id' => $targetArea->id],
                [
                    'description' => 'Biblioteca JavaScript para interfaces de usuario. Hooks, Context API, y gestión de estado.',
                    'category' => 'Técnica',
                    'level_type' => 'numeric',
                    'is_critical' => false,
                    'internal_role_id' => $seniorInternal?->id,
                    'order' => 3,
                    'is_active' => true,
                ]
            );

            $aws = Competency::updateOrCreate(
                ['name' => 'AWS', 'area_id' => $targetArea->id],
                [
                    'description' => 'Servicios cloud de Amazon Web Services. EC2, S3, RDS, Lambda, y arquitectura serverless.',
                    'category' => 'Técnica',
                    'level_type' => 'numeric',
                    'is_critical' => true,
                    'internal_role_id' => $seniorInternal?->id,
                    'order' => 4,
                    'is_active' => true,
                ]
            );

            $python = Competency::updateOrCreate(
                ['name' => 'Python', 'area_id' => $targetArea->id],
                [
                    'description' => 'Lenguaje de programación para automatización, scripting y desarrollo backend.',
                    'category' => 'Técnica',
                    'level_type' => 'numeric',
                    'is_critical' => false,
                    'internal_role_id' => $seniorInternal?->id,
                    'order' => 5,
                    'is_active' => true,
                ]
            );

            // === COMPETENCIAS METODOLÓGICAS ===
            $scrum = Competency::updateOrCreate(
                ['name' => 'Scrum', 'area_id' => $targetArea->id],
                [
                    'description' => 'Metodología ágil. Sprint planning, daily standups, retrospectivas, y gestión de backlog.',
                    'category' => 'Metodología',
                    'level_type' => 'numeric',
                    'is_critical' => false,
                    'internal_role_id' => $managerInternal?->id,
                    'order' => 6,
                    'is_active' => true,
                ]
            );

            $devops = Competency::updateOrCreate(
                ['name' => 'DevOps', 'area_id' => $targetArea->id],
                [
                    'description' => 'Prácticas de integración continua y despliegue continuo. CI/CD, automatización, y monitoreo.',
                    'category' => 'Metodología',
                    'level_type' => 'numeric',
                    'is_critical' => true,
                    'internal_role_id' => $seniorInternal?->id,
                    'order' => 7,
                    'is_active' => true,
                ]
            );

            $git = Competency::updateOrCreate(
                ['name' => 'Git', 'area_id' => $targetArea->id],
                [
                    'description' => 'Control de versiones. Branching strategies, merge, rebase, y workflows colaborativos.',
                    'category' => 'Metodología',
                    'level_type' => 'numeric',
                    'is_critical' => false,
                    'internal_role_id' => null,
                    'order' => 8,
                    'is_active' => true,
                ]
            );

            // === SOFT SKILLS ===
            $liderazgo = Competency::updateOrCreate(
                ['name' => 'Liderazgo', 'area_id' => $targetArea->id],
                [
                    'description' => 'Capacidad de guiar, motivar y desarrollar equipos. Toma de decisiones y gestión de conflictos.',
                    'category' => 'Soft Skills',
                    'level_type' => 'numeric',
                    'is_critical' => false,
                    'internal_role_id' => $managerInternal?->id,
                    'order' => 9,
                    'is_active' => true,
                ]
            );

            $comunicacion = Competency::updateOrCreate(
                ['name' => 'Comunicación', 'area_id' => $targetArea->id],
                [
                    'description' => 'Comunicación efectiva con stakeholders, presentaciones técnicas, y documentación clara.',
                    'category' => 'Soft Skills',
                    'level_type' => 'numeric',
                    'is_critical' => false,
                    'internal_role_id' => null,
                    'order' => 10,
                    'is_active' => true,
                ]
            );

            $resolucionProblemas = Competency::updateOrCreate(
                ['name' => 'Resolución de Problemas', 'area_id' => $targetArea->id],
                [
                    'description' => 'Análisis de problemas complejos, pensamiento crítico, y generación de soluciones innovadoras.',
                    'category' => 'Soft Skills',
                    'level_type' => 'numeric',
                    'is_critical' => false,
                    'internal_role_id' => null,
                    'order' => 11,
                    'is_active' => true,
                ]
            );

            // === CERTIFICACIONES ===
            $awsCertified = Competency::updateOrCreate(
                ['name' => 'AWS Certified', 'area_id' => $targetArea->id],
                [
                    'description' => 'Certificación oficial de AWS (Solutions Architect, Developer, SysOps, etc.).',
                    'category' => 'Certificación',
                    'level_type' => 'numeric',
                    'is_critical' => false,
                    'internal_role_id' => $seniorInternal?->id,
                    'order' => 12,
                    'is_active' => true,
                ]
            );

            // ============================================
            // ASIGNAR COMPETENCIAS A USUARIOS
            // ============================================

            // Asignar competencias a usuarios del área
            // Si no hay usuarios específicos, asignar a todos los usuarios del área
            $areaUsers = $users->count() > 0 ? $users : User::where('area_id', $targetArea->id)->get();
            
            // Determinar quién evalúa (director, manager, o primer usuario)
            $assessor = $director ?? $manager ?? $areaUsers->first();
            $assessorId = $assessor?->id ?? 1; // Fallback a ID 1 si no hay nadie
            
            // Director - Niveles altos en liderazgo y comunicación, moderados en técnico
            if ($director) {
                UserCompetency::updateOrCreate(
                    ['user_id' => $director->id, 'competency_id' => $laravel->id],
                    ['current_level' => 3, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $assessorId, 'notes' => 'Conocimiento sólido para supervisión técnica']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $director->id, 'competency_id' => $docker->id],
                    ['current_level' => 2, 'target_level' => 3, 'last_assessed_at' => now(), 'assessed_by' => $assessorId, 'notes' => 'Necesita mejorar para liderar proyectos cloud']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $director->id, 'competency_id' => $liderazgo->id],
                    ['current_level' => 5, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $assessorId, 'notes' => 'Excelente liderazgo demostrado']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $director->id, 'competency_id' => $comunicacion->id],
                    ['current_level' => 5, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $assessorId]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $director->id, 'competency_id' => $scrum->id],
                    ['current_level' => 4, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $assessorId]
                );
            }

            // Si no hay manager específico, usar el primer usuario que no sea director
            if (!$manager && $areaUsers->count() > 1) {
                $manager = $areaUsers->where('id', '!=', $director?->id)->first();
            }
            
            // Manager - Buen balance técnico y de gestión
            if ($manager) {
                UserCompetency::updateOrCreate(
                    ['user_id' => $manager->id, 'competency_id' => $laravel->id],
                    ['current_level' => 4, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $assessorId, 'notes' => 'Muy competente, objetivo: experto']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $manager->id, 'competency_id' => $docker->id],
                    ['current_level' => 3, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $assessorId, 'notes' => 'Gap crítico para proyectos cloud']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $manager->id, 'competency_id' => $react->id],
                    ['current_level' => 2, 'target_level' => 3, 'last_assessed_at' => now(), 'assessed_by' => $assessorId]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $manager->id, 'competency_id' => $aws->id],
                    ['current_level' => 2, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $assessorId, 'notes' => 'Gap importante - formación prioritaria']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $manager->id, 'competency_id' => $devops->id],
                    ['current_level' => 3, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $assessorId]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $manager->id, 'competency_id' => $scrum->id],
                    ['current_level' => 4, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $assessorId]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $manager->id, 'competency_id' => $liderazgo->id],
                    ['current_level' => 4, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $assessorId]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $manager->id, 'competency_id' => $git->id],
                    ['current_level' => 4, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $assessorId]
                );
            }

            // Si no hay senior específico, usar el segundo usuario
            if (!$senior && $areaUsers->count() > 2) {
                $senior = $areaUsers->where('id', '!=', $director?->id)
                    ->where('id', '!=', $manager?->id)
                    ->first();
            }
            
            // Senior - Fuerte técnicamente, gaps en algunas áreas
            if ($senior) {
                UserCompetency::updateOrCreate(
                    ['user_id' => $senior->id, 'competency_id' => $laravel->id],
                    ['current_level' => 5, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $manager->id, 'notes' => 'Experto confirmado']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $senior->id, 'competency_id' => $docker->id],
                    ['current_level' => 1, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $manager->id, 'notes' => 'GAP CRÍTICO - Formación urgente requerida']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $senior->id, 'competency_id' => $react->id],
                    ['current_level' => 3, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $manager->id]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $senior->id, 'competency_id' => $aws->id],
                    ['current_level' => 1, 'target_level' => 3, 'last_assessed_at' => now(), 'assessed_by' => $manager->id, 'notes' => 'Gap importante - necesita formación']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $senior->id, 'competency_id' => $python->id],
                    ['current_level' => 4, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $manager->id]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $senior->id, 'competency_id' => $devops->id],
                    ['current_level' => 2, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $manager->id, 'notes' => 'Gap moderado']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $senior->id, 'competency_id' => $git->id],
                    ['current_level' => 4, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $manager->id]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $senior->id, 'competency_id' => $comunicacion->id],
                    ['current_level' => 3, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $manager->id]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $senior->id, 'competency_id' => $resolucionProblemas->id],
                    ['current_level' => 4, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $manager->id]
                );
            }

            // Si no hay junior específico, usar el tercer usuario
            if (!$junior && $areaUsers->count() > 3) {
                $junior = $areaUsers->where('id', '!=', $director?->id)
                    ->where('id', '!=', $manager?->id)
                    ->where('id', '!=', $senior?->id)
                    ->first();
            }
            
            // Junior - Niveles bajos, objetivos de crecimiento
            if ($junior) {
                UserCompetency::updateOrCreate(
                    ['user_id' => $junior->id, 'competency_id' => $laravel->id],
                    ['current_level' => 2, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $manager->id, 'notes' => 'En proceso de aprendizaje']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $junior->id, 'competency_id' => $docker->id],
                    ['current_level' => 1, 'target_level' => 3, 'last_assessed_at' => now(), 'assessed_by' => $manager->id, 'notes' => 'Formación básica en curso']
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $junior->id, 'competency_id' => $react->id],
                    ['current_level' => 1, 'target_level' => 3, 'last_assessed_at' => now(), 'assessed_by' => $manager->id]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $junior->id, 'competency_id' => $git->id],
                    ['current_level' => 3, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $manager->id]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $junior->id, 'competency_id' => $comunicacion->id],
                    ['current_level' => 3, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $manager->id]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $junior->id, 'competency_id' => $resolucionProblemas->id],
                    ['current_level' => 2, 'target_level' => 4, 'last_assessed_at' => now(), 'assessed_by' => $manager->id]
                );
            }
        }

        // ============================================
        // COMPETENCIAS PARA CLOUD SECURITY (opcional)
        // ============================================
        if ($cloudArea) {
            $cloud = User::where('email', 'cloud@example.com')->first();
            $cloudInternal = InternalRole::where('slug', 'cloud-security-engineer')->first();

            $kubernetes = Competency::updateOrCreate(
                ['name' => 'Kubernetes', 'area_id' => $cloudArea->id],
                [
                    'description' => 'Orquestación de contenedores. Pods, Services, Deployments, y gestión de clusters.',
                    'category' => 'Técnica',
                    'level_type' => 'numeric',
                    'is_critical' => true,
                    'internal_role_id' => $cloudInternal?->id,
                    'order' => 1,
                    'is_active' => true,
                ]
            );

            $terraform = Competency::updateOrCreate(
                ['name' => 'Terraform', 'area_id' => $cloudArea->id],
                [
                    'description' => 'Infrastructure as Code. Provisioning de recursos cloud de forma declarativa.',
                    'category' => 'Técnica',
                    'level_type' => 'numeric',
                    'is_critical' => true,
                    'internal_role_id' => $cloudInternal?->id,
                    'order' => 2,
                    'is_active' => true,
                ]
            );

            if ($cloud) {
                UserCompetency::updateOrCreate(
                    ['user_id' => $cloud->id, 'competency_id' => $kubernetes->id],
                    ['current_level' => 4, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $cloud->id]
                );
                UserCompetency::updateOrCreate(
                    ['user_id' => $cloud->id, 'competency_id' => $terraform->id],
                    ['current_level' => 4, 'target_level' => 5, 'last_assessed_at' => now(), 'assessed_by' => $cloud->id]
                );
            }
        }
    }
}

