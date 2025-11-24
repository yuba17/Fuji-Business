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
        Schema::create('tooling_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tooling_id')->constrained('toolings')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('milestone_type', ['nueva_funcionalidad', 'mejora_estabilidad', 'mejora_rendimiento', 'ampliacion_escenarios', 'integracion', 'otro'])->default('otro');
            $table->string('target_quarter')->nullable(); // "Q1", "Q2", "Q3", "Q4"
            $table->integer('target_year')->nullable();
            $table->enum('priority', ['alta', 'media', 'baja'])->default('media');
            $table->enum('status', ['planificado', 'en_curso', 'completado', 'bloqueado'])->default('planificado');
            $table->foreignId('assigned_to_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tooling_milestones');
    }
};
