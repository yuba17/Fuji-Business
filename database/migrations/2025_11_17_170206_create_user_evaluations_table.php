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
        Schema::create('user_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->nullOnDelete()->comment('Usuario que realiza la evaluación (manager)');
            $table->date('evaluation_date');
            $table->enum('type', ['quarterly', 'biannual', 'annual', 'probationary', 'promotion', 'custom'])->default('quarterly');
            $table->enum('status', ['draft', 'in_progress', 'completed', 'approved', 'rejected'])->default('draft');
            
            // Evaluación general
            $table->integer('overall_score')->nullable()->comment('Puntuación general (1-5)');
            $table->text('strengths')->nullable()->comment('Fortalezas identificadas');
            $table->text('areas_for_improvement')->nullable()->comment('Áreas de mejora');
            $table->text('achievements')->nullable()->comment('Logros destacados');
            $table->text('feedback')->nullable()->comment('Feedback general');
            
            // Objetivos
            $table->json('goals_achieved')->nullable()->comment('Objetivos alcanzados');
            $table->json('goals_set')->nullable()->comment('Nuevos objetivos establecidos');
            $table->json('career_development_plan')->nullable()->comment('Plan de desarrollo profesional');
            
            // Competencias evaluadas
            $table->json('competency_scores')->nullable()->comment('Puntuaciones por competencia');
            
            // Metadatos
            $table->date('next_evaluation_date')->nullable()->comment('Fecha de próxima evaluación');
            $table->text('notes')->nullable()->comment('Notas adicionales');
            $table->json('metadata')->nullable()->comment('Datos adicionales en formato JSON');
            
            $table->timestamps();
            
            $table->index(['user_id', 'evaluation_date']);
            $table->index(['evaluator_id', 'status']);
            $table->index('next_evaluation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_evaluations');
    }
};
