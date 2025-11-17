<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Certification;
use App\Models\User;
use App\Models\UserCertification;
use App\Models\CertificationBadge;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar área de Seguridad Ofensiva
        $seguridadOfensivaArea = Area::where('slug', 'seguridad-ofensiva')
            ->orWhere('slug', 'red-team')
            ->orWhere('name', 'like', '%Seguridad Ofensiva%')
            ->orWhere('name', 'like', '%Red Team%')
            ->first();
        
        $allAreas = Area::where('is_active', true)->get();
        $targetArea = $seguridadOfensivaArea ?? $allAreas->first();
        
        if (!$targetArea) {
            return;
        }

        // Obtener usuarios del área
        $users = User::where('area_id', $targetArea->id)->get();
        
        if ($users->isEmpty()) {
            return;
        }

        // Obtener usuarios por rol aproximado
        $director = $users->where('email', 'director@example.com')->first();
        if (!$director) {
            $director = $users->first();
        }
        
        $manager = $users->where('email', 'manager@example.com')->first();
        if (!$manager) {
            $manager = $users->skip(1)->first() ?? $users->first();
        }
        
        $senior = $users->where('email', 'senior@example.com')->first();
        if (!$senior) {
            $senior = $users->skip(2)->first() ?? $users->first();
        }
        
        $junior = $users->where('email', 'junior@example.com')->first();
        if (!$junior) {
            $junior = $users->skip(3)->first() ?? $users->first();
        }

        // ============================================
        // CERTIFICACIONES OFENSIVAS
        // ============================================

        // OSCP - Offensive Security Certified Professional
        $oscp = Certification::updateOrCreate(
            ['code' => 'OSCP'],
            [
                'name' => 'Offensive Security Certified Professional',
                'description' => 'Certificación práctica de hacking ético que requiere completar un examen de 24 horas en un laboratorio real.',
                'provider' => 'Offensive Security',
                'category' => 'Offensive',
                'level' => 'Advanced',
                'validity_months' => null, // Permanente
                'cost' => 1499.00,
                'currency' => 'EUR',
                'difficulty_score' => 9,
                'value_score' => 10,
                'is_critical' => true,
                'is_internal' => false,
                'points_reward' => 500,
                'badge_icon' => '🎯',
                'badge_color' => 'red',
                'order' => 1,
                'is_active' => true,
            ]
        );

        // OSCE - Offensive Security Certified Expert
        $osce = Certification::updateOrCreate(
            ['code' => 'OSCE'],
            [
                'name' => 'Offensive Security Certified Expert',
                'description' => 'Certificación avanzada que cubre técnicas avanzadas de explotación y evasión.',
                'provider' => 'Offensive Security',
                'category' => 'Offensive',
                'level' => 'Expert',
                'validity_months' => null,
                'cost' => 1999.00,
                'currency' => 'EUR',
                'difficulty_score' => 10,
                'value_score' => 10,
                'is_critical' => true,
                'is_internal' => false,
                'points_reward' => 750,
                'badge_icon' => '🔥',
                'badge_color' => 'red',
                'order' => 2,
                'is_active' => true,
            ]
        );

        // GPEN - GIAC Penetration Tester
        $gpen = Certification::updateOrCreate(
            ['code' => 'GPEN'],
            [
                'name' => 'GIAC Penetration Tester',
                'description' => 'Certificación que valida habilidades en metodologías de pentesting y herramientas.',
                'provider' => 'SANS / GIAC',
                'category' => 'Offensive',
                'level' => 'Advanced',
                'validity_months' => 36,
                'cost' => 899.00,
                'currency' => 'EUR',
                'difficulty_score' => 7,
                'value_score' => 8,
                'is_critical' => false,
                'is_internal' => false,
                'points_reward' => 400,
                'badge_icon' => '🛡️',
                'badge_color' => 'blue',
                'order' => 3,
                'is_active' => true,
            ]
        );

        // CEH - Certified Ethical Hacker
        $ceh = Certification::updateOrCreate(
            ['code' => 'CEH'],
            [
                'name' => 'Certified Ethical Hacker',
                'description' => 'Certificación que cubre metodologías y herramientas de hacking ético.',
                'provider' => 'EC-Council',
                'category' => 'Offensive',
                'level' => 'Intermediate',
                'validity_months' => 36,
                'cost' => 1199.00,
                'currency' => 'EUR',
                'difficulty_score' => 6,
                'value_score' => 7,
                'is_critical' => false,
                'is_internal' => false,
                'points_reward' => 300,
                'badge_icon' => '🔓',
                'badge_color' => 'green',
                'order' => 4,
                'is_active' => true,
            ]
        );

        // CISSP - Certified Information Systems Security Professional
        $cissp = Certification::updateOrCreate(
            ['code' => 'CISSP'],
            [
                'name' => 'Certified Information Systems Security Professional',
                'description' => 'Certificación de gestión de seguridad de la información de alto nivel.',
                'provider' => 'ISC²',
                'category' => 'Management',
                'level' => 'Expert',
                'validity_months' => 36,
                'cost' => 749.00,
                'currency' => 'EUR',
                'difficulty_score' => 8,
                'value_score' => 9,
                'is_critical' => false,
                'is_internal' => false,
                'points_reward' => 600,
                'badge_icon' => '⭐',
                'badge_color' => 'purple',
                'order' => 5,
                'is_active' => true,
            ]
        );

        // GCIH - GIAC Certified Incident Handler
        $gcih = Certification::updateOrCreate(
            ['code' => 'GCIH'],
            [
                'name' => 'GIAC Certified Incident Handler',
                'description' => 'Certificación en manejo de incidentes de seguridad y respuesta.',
                'provider' => 'SANS / GIAC',
                'category' => 'Defensive',
                'level' => 'Advanced',
                'validity_months' => 36,
                'cost' => 899.00,
                'currency' => 'EUR',
                'difficulty_score' => 7,
                'value_score' => 8,
                'is_critical' => false,
                'is_internal' => false,
                'points_reward' => 400,
                'badge_icon' => '🚨',
                'badge_color' => 'orange',
                'order' => 6,
                'is_active' => true,
            ]
        );

        // ============================================
        // ASIGNAR CERTIFICACIONES A USUARIOS
        // ============================================

        // Director tiene OSCP y CISSP
        if ($director) {
            // OSCP activa
            UserCertification::updateOrCreate(
                [
                    'user_id' => $director->id,
                    'certification_id' => $oscp->id,
                ],
                [
                    'obtained_at' => Carbon::now()->subMonths(18),
                    'expires_at' => null,
                    'certificate_number' => 'OSCP-' . strtoupper(substr($director->name, 0, 3)) . '-' . rand(1000, 9999),
                    'status' => 'active',
                    'priority' => 0,
                ]
            );

            // CISSP activa
            UserCertification::updateOrCreate(
                [
                    'user_id' => $director->id,
                    'certification_id' => $cissp->id,
                ],
                [
                    'obtained_at' => Carbon::now()->subMonths(12),
                    'expires_at' => Carbon::now()->addMonths(24),
                    'certificate_number' => 'CISSP-' . rand(100000, 999999),
                    'status' => 'active',
                    'priority' => 0,
                ]
            );

            // Badge de reconocimiento
            CertificationBadge::updateOrCreate(
                [
                    'user_id' => $director->id,
                    'certification_id' => $oscp->id,
                    'type' => 'achievement',
                ],
                [
                    'name' => 'OSCP Master',
                    'description' => 'Completó la certificación OSCP con excelencia',
                    'icon' => '🎯',
                    'color' => 'red',
                    'points' => 100,
                    'earned_at' => Carbon::now()->subMonths(18),
                    'awarded_by' => $director->id,
                    'is_public' => true,
                ]
            );
        }

        // Manager tiene OSCP y GPEN
        if ($manager) {
            // OSCP activa
            UserCertification::updateOrCreate(
                [
                    'user_id' => $manager->id,
                    'certification_id' => $oscp->id,
                ],
                [
                    'obtained_at' => Carbon::now()->subMonths(24),
                    'expires_at' => null,
                    'certificate_number' => 'OSCP-' . strtoupper(substr($manager->name, 0, 3)) . '-' . rand(1000, 9999),
                    'status' => 'active',
                    'priority' => 0,
                ]
            );

            // GPEN activa
            UserCertification::updateOrCreate(
                [
                    'user_id' => $manager->id,
                    'certification_id' => $gpen->id,
                ],
                [
                    'obtained_at' => Carbon::now()->subMonths(6),
                    'expires_at' => Carbon::now()->addMonths(30),
                    'certificate_number' => 'GPEN-' . rand(10000, 99999),
                    'status' => 'active',
                    'priority' => 0,
                ]
            );

            // OSCE planificada
            UserCertification::updateOrCreate(
                [
                    'user_id' => $manager->id,
                    'certification_id' => $osce->id,
                ],
                [
                    'obtained_at' => Carbon::now(),
                    'expires_at' => null,
                    'status' => 'planned',
                    'planned_date' => Carbon::now()->addMonths(6),
                    'priority' => 5,
                    'notes' => 'Objetivo para Q2 2025',
                ]
            );
        }

        // Senior tiene OSCP y CEH
        if ($senior) {
            // OSCP activa
            UserCertification::updateOrCreate(
                [
                    'user_id' => $senior->id,
                    'certification_id' => $oscp->id,
                ],
                [
                    'obtained_at' => Carbon::now()->subMonths(12),
                    'expires_at' => null,
                    'certificate_number' => 'OSCP-' . strtoupper(substr($senior->name, 0, 3)) . '-' . rand(1000, 9999),
                    'status' => 'active',
                    'priority' => 0,
                ]
            );

            // CEH activa
            UserCertification::updateOrCreate(
                [
                    'user_id' => $senior->id,
                    'certification_id' => $ceh->id,
                ],
                [
                    'obtained_at' => Carbon::now()->subMonths(18),
                    'expires_at' => Carbon::now()->addMonths(18),
                    'certificate_number' => 'CEH-' . rand(100000, 999999),
                    'status' => 'active',
                    'priority' => 0,
                ]
            );

            // GPEN planificada
            UserCertification::updateOrCreate(
                [
                    'user_id' => $senior->id,
                    'certification_id' => $gpen->id,
                ],
                [
                    'obtained_at' => Carbon::now(),
                    'expires_at' => null,
                    'status' => 'planned',
                    'planned_date' => Carbon::now()->addMonths(3),
                    'priority' => 4,
                    'notes' => 'Preparación en curso',
                ]
            );
        }

        // Junior tiene CEH y OSCP planificada
        if ($junior) {
            // CEH activa
            UserCertification::updateOrCreate(
                [
                    'user_id' => $junior->id,
                    'certification_id' => $ceh->id,
                ],
                [
                    'obtained_at' => Carbon::now()->subMonths(6),
                    'expires_at' => Carbon::now()->addMonths(30),
                    'certificate_number' => 'CEH-' . rand(100000, 999999),
                    'status' => 'active',
                    'priority' => 0,
                ]
            );

            // OSCP planificada
            UserCertification::updateOrCreate(
                [
                    'user_id' => $junior->id,
                    'certification_id' => $oscp->id,
                ],
                [
                    'obtained_at' => Carbon::now(),
                    'expires_at' => null,
                    'status' => 'planned',
                    'planned_date' => Carbon::now()->addMonths(9),
                    'priority' => 5,
                    'notes' => 'Objetivo principal para 2025',
                ]
            );

            // Badge de logro
            CertificationBadge::updateOrCreate(
                [
                    'user_id' => $junior->id,
                    'certification_id' => $ceh->id,
                    'type' => 'achievement',
                ],
                [
                    'name' => 'CEH Certified',
                    'description' => 'Completó la certificación CEH',
                    'icon' => '🔓',
                    'color' => 'green',
                    'points' => 50,
                    'earned_at' => Carbon::now()->subMonths(6),
                    'awarded_by' => $manager?->id ?? $director?->id,
                    'is_public' => true,
                ]
            );
        }

        // Añadir algunas certificaciones vencidas para mostrar alertas
        if ($senior) {
            // GCIH vencida (para mostrar alerta)
            UserCertification::updateOrCreate(
                [
                    'user_id' => $senior->id,
                    'certification_id' => $gcih->id,
                ],
                [
                    'obtained_at' => Carbon::now()->subMonths(40),
                    'expires_at' => Carbon::now()->subMonths(4),
                    'certificate_number' => 'GCIH-' . rand(10000, 99999),
                    'status' => 'expired',
                    'priority' => 0,
                    'notes' => 'Requiere renovación',
                ]
            );
        }

        // Badge especial de reconocimiento para el director
        if ($director) {
            CertificationBadge::updateOrCreate(
                [
                    'user_id' => $director->id,
                    'type' => 'recognition',
                    'name' => 'Líder de Certificaciones',
                ],
                [
                    'description' => 'Líder del equipo en certificaciones de seguridad',
                    'icon' => '👑',
                    'color' => 'gold',
                    'points' => 200,
                    'earned_at' => Carbon::now()->subMonths(6),
                    'awarded_by' => $director->id,
                    'is_public' => true,
                ]
            );
        }

        $this->command->info('✅ Certificaciones creadas y asignadas correctamente');
    }
}
