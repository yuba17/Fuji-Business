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
        Schema::create('infrastructures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // servidor, cloud, herramienta, red, almacenamiento, etc.
            $table->string('category'); // hardware, software, cloud, network, security, etc.
            $table->string('status')->default('active'); // active, maintenance, deprecated, planned
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('cost_monthly', 10, 2)->nullable();
            $table->decimal('cost_yearly', 10, 2)->nullable();
            $table->string('capacity')->nullable(); // Ej: "100GB", "10TB", "50 usuarios", etc.
            $table->integer('utilization_percent')->nullable(); // 0-100
            $table->string('provider')->nullable(); // AWS, Azure, GCP, On-premise, etc.
            $table->string('location')->nullable(); // Ubicación física o región cloud
            $table->date('roadmap_date')->nullable(); // Fecha planificada si está en roadmap
            $table->json('metadata')->nullable(); // Campos adicionales flexibles
            $table->integer('order')->default(0);
            $table->boolean('is_critical')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infrastructures');
    }
};
