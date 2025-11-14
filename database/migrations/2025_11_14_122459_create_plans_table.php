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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('plan_type_id')->constrained('plan_types')->cascadeOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('director_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->enum('status', [
                'draft',
                'internal_review',
                'director_review',
                'approved',
                'in_progress',
                'under_review',
                'closed',
                'archived'
            ])->default('draft');
            $table->date('start_date')->nullable();
            $table->date('target_date')->nullable();
            $table->date('review_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('version')->default(1);
            $table->boolean('is_current_version')->default(true);
            $table->json('metadata')->nullable(); // Datos adicionales flexibles
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['area_id', 'status']);
            $table->index(['manager_id', 'status']);
            $table->index(['plan_type_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
