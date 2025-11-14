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
        Schema::create('milestone_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('predecessor_id')->constrained('milestones')->cascadeOnDelete();
            $table->foreignId('successor_id')->constrained('milestones')->cascadeOnDelete();
            $table->enum('type', ['fs', 'ss', 'ff', 'sf'])->default('fs'); // Finish-to-Start, Start-to-Start, Finish-to-Finish, Start-to-Finish
            $table->integer('lag_days')->default(0); // Días de retraso entre dependencias
            $table->timestamps();
            
            $table->unique(['predecessor_id', 'successor_id']);
            $table->index('predecessor_id');
            $table->index('successor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestone_dependencies');
    }
};
