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
            // Clasificación principal: licencia o hardware
            $table->enum('infrastructure_class', ['license', 'hardware'])->nullable()->after('category');
            
            // Estado de adquisición
            $table->enum('acquisition_status', ['purchased', 'to_purchase', 'planned'])->default('purchased')->after('infrastructure_class');
            
            // Para licencias: fecha de caducidad y días de aviso
            $table->date('expires_at')->nullable()->after('acquisition_status');
            $table->integer('renewal_reminder_days')->default(30)->nullable()->after('expires_at');
            
            // Actualizar el campo type para tener valores más específicos
            // Los valores existentes se mantendrán, pero ahora tendremos más opciones
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infrastructures', function (Blueprint $table) {
            $table->dropColumn([
                'infrastructure_class',
                'acquisition_status',
                'expires_at',
                'renewal_reminder_days',
            ]);
        });
    }
};
