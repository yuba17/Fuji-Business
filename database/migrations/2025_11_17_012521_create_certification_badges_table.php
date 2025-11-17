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
        Schema::create('certification_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('certification_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // achievement, milestone, recognition, special
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Emoji o código de icono
            $table->string('color')->nullable(); // Color del badge
            $table->integer('points')->default(0); // Puntos de gamificación
            $table->date('earned_at');
            $table->foreignId('awarded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_public')->default(true); // Visible en leaderboard
            $table->timestamps();
            
            $table->index(['user_id', 'type']);
            $table->index('earned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certification_badges');
    }
};
