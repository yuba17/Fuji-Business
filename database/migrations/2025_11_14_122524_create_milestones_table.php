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
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            // roadmap_id removido - los milestones pertenecen directamente a un plan
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('target_date');
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'delayed', 'cancelled'])->default('not_started');
            $table->foreignId('responsible_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('progress_percentage')->default(0);
            $table->json('metadata')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index(['plan_id', 'status']);
            $table->index(['target_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
