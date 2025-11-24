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
        Schema::create('toolings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['ofensiva', 'automatizacion', 'laboratorio', 'reporting', 'soporte', 'otro'])->default('otro');
            $table->text('description')->nullable();
            $table->enum('status', ['idea', 'en_evaluacion', 'en_desarrollo', 'beta', 'produccion', 'obsoleta'])->default('idea');
            $table->enum('criticality', ['alta', 'media', 'baja'])->default('media');
            $table->date('started_at')->nullable();
            $table->date('last_updated_at')->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('contact_reference')->nullable();
            $table->text('benefits')->nullable();
            $table->json('use_scenarios')->nullable();
            $table->json('impact_metrics')->nullable();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toolings');
    }
};
