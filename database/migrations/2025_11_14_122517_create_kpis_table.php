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
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->enum('type', ['numeric', 'percentage', 'indicator', 'calculated'])->default('numeric');
            $table->string('unit')->nullable(); // %, €, unidades, etc.
            $table->decimal('target_value', 15, 4)->nullable();
            $table->decimal('current_value', 15, 4)->nullable();
            $table->string('calculation_method')->nullable(); // manual, automatic, formula
            $table->text('formula')->nullable(); // Para KPIs calculados
            $table->enum('update_frequency', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->date('last_updated_at')->nullable();
            $table->enum('status', ['green', 'yellow', 'red'])->default('green');
            $table->decimal('threshold_green', 5, 2)->default(80); // % del objetivo
            $table->decimal('threshold_yellow', 5, 2)->default(50); // % del objetivo
            $table->foreignId('responsible_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('kpi_type', ['leading', 'lagging', 'composite'])->default('lagging');
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['plan_id', 'is_active']);
            $table->index(['area_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
