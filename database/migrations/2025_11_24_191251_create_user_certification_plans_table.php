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
        Schema::create('user_certification_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('certification_plan_id')->constrained('certification_plans')->onDelete('cascade');
            $table->timestamp('assigned_at');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('source', ['automatic', 'manual', 'migration'])->default('automatic');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->boolean('auto_reassigned')->default(false);
            $table->foreignId('previous_plan_id')->nullable()->constrained('certification_plans')->onDelete('set null');
            $table->timestamp('reassigned_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Índices para consultas frecuentes
            $table->index(['user_id', 'status']);
            $table->index('certification_plan_id');
            // Nota: La validación de planes únicos por usuario se maneja a nivel de aplicación
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_certification_plans');
    }
};
