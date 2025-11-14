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
        Schema::create('risk_mitigation_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_id')->constrained('risks')->cascadeOnDelete();
            $table->string('action');
            $table->text('description')->nullable();
            $table->foreignId('responsible_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('target_date');
            $table->date('completed_at')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->decimal('cost', 15, 2)->nullable();
            $table->integer('expected_probability_reduction')->nullable(); // Reducción esperada de probabilidad (1-5)
            $table->integer('expected_impact_reduction')->nullable(); // Reducción esperada de impacto (1-5)
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['risk_id', 'status']);
            $table->index(['target_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_mitigation_actions');
    }
};
