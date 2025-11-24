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
        Schema::table('infrastructures', function (Blueprint $table) {
            // Cantidad de unidades (para elementos que no necesitan tracking individual)
            $table->integer('quantity')->default(1)->after('is_critical');
            
            // Sistema de plantillas
            $table->boolean('is_template')->default(false)->after('quantity');
            $table->foreignId('template_id')->nullable()->after('is_template')
                ->constrained('infrastructures')->onDelete('set null');
            
            // Identificador único para elementos individuales (número de serie, código, etc.)
            $table->string('identifier')->nullable()->after('template_id');
            
            // Índices
            $table->index('is_template');
            $table->index('template_id');
            $table->index('identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infrastructures', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropIndex(['is_template']);
            $table->dropIndex(['template_id']);
            $table->dropIndex(['identifier']);
            $table->dropColumn(['quantity', 'is_template', 'template_id', 'identifier']);
        });
    }
};
