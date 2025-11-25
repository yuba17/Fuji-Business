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
        Schema::create('certification_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_line_id')->constrained('service_lines')->onDelete('cascade');
            $table->foreignId('internal_role_id')->constrained('internal_roles')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            
            // Índice compuesto para búsquedas rápidas
            $table->index(['service_line_id', 'internal_role_id', 'is_active']);
            // Nota: La validación de planes únicos activos se maneja a nivel de aplicación
            // debido a las limitaciones de índices únicos con soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certification_plans');
    }
};
