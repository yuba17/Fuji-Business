<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->nullable(); // Ej: OSCP, CEH, GPEN
            $table->text('description')->nullable();
            $table->string('provider'); // Ej: Offensive Security, EC-Council, SANS
            $table->string('category')->nullable(); // Ej: Offensive, Defensive, Management
            $table->string('level')->nullable(); // Ej: Entry, Intermediate, Advanced, Expert
            $table->integer('validity_months')->nullable(); // Duración en meses (null = permanente)
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->integer('difficulty_score')->nullable(); // 1-10
            $table->integer('value_score')->nullable(); // 1-10 (valor en el mercado)
            $table->boolean('is_critical')->default(false);
            $table->boolean('is_internal')->default(false); // Certificación interna de la empresa
            $table->text('requirements')->nullable(); // Requisitos previos
            $table->text('exam_details')->nullable(); // Detalles del examen
            $table->string('badge_icon')->nullable(); // Icono para el badge
            $table->string('badge_color')->nullable(); // Color del badge
            $table->integer('points_reward')->default(0); // Puntos de gamificación
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
