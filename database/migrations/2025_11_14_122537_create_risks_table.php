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
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->integer('probability')->default(1); // 1-5
            $table->integer('impact')->default(1); // 1-5
            $table->integer('risk_level')->nullable(); // Calculado: probability * impact (1-25), se calcula en el modelo
            $table->enum('category', ['bajo', 'medio', 'alto', 'critico'])->nullable();
            $table->enum('strategy', ['avoid', 'mitigate', 'transfer', 'accept'])->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['open', 'in_mitigation', 'mitigated', 'closed', 'accepted'])->default('open');
            $table->date('identified_at');
            $table->date('target_mitigation_date')->nullable();
            $table->date('mitigated_at')->nullable();
            $table->text('mitigation_plan')->nullable();
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->enum('trend', ['stable', 'increasing', 'decreasing'])->default('stable');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['plan_id', 'status']);
            $table->index(['area_id', 'status']);
            $table->index(['risk_level', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
