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
        Schema::create('certification_plan_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_plan_id')->constrained('certification_plans')->onDelete('cascade');
            $table->foreignId('certification_id')->constrained('certifications')->onDelete('cascade');
            $table->integer('priority')->default(3)->comment('1=Crítica, 2=Alta, 3=Media, 4=Baja, 5=Opcional');
            $table->integer('order')->default(0);
            $table->integer('target_months')->nullable()->comment('Meses desde inicio del plan para obtenerla');
            $table->date('target_date')->nullable()->comment('Fecha específica objetivo');
            $table->boolean('is_flexible')->default(true)->comment('Si puede ajustarse la fecha');
            $table->timestamps();
            
            // Evitar duplicados de certificaciones en el mismo plan
            $table->unique(['certification_plan_id', 'certification_id']);
            // Índice para ordenamiento
            $table->index(['certification_plan_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certification_plan_certifications');
    }
};
