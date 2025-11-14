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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sector_economico')->nullable(); // Sector económico
            $table->enum('tamaño_empresa', ['startup', 'pequeña', 'mediana', 'grande', 'multinacional'])->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('contacto_principal')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('sitio_web')->nullable();
            $table->text('notas')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('sector_economico');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
