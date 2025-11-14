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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('plan_comercial_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->string('sector_economico')->nullable();
            $table->enum('status', [
                'prospecto',
                'en_negociacion',
                'activo',
                'en_pausa',
                'completado',
                'cancelado'
            ])->default('prospecto');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->decimal('presupuesto', 15, 2)->nullable();
            $table->string('moneda', 3)->default('EUR');
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['client_id', 'status']);
            $table->index(['plan_comercial_id', 'status']);
            $table->index('sector_economico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
