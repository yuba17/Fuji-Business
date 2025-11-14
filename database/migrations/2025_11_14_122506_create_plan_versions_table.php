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
        Schema::create('plan_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            $table->integer('version_number');
            $table->json('snapshot'); // Snapshot completo del plan en esta versión
            $table->foreignId('created_by')->constrained('users')->nullOnDelete();
            $table->text('change_summary')->nullable(); // Resumen de cambios
            $table->timestamps();
            
            $table->unique(['plan_id', 'version_number']);
            $table->index('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_versions');
    }
};
