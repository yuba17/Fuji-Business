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
        Schema::create('user_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('certification_id')->constrained()->onDelete('cascade');
            $table->date('obtained_at'); // Fecha de obtención
            $table->date('expires_at')->nullable(); // Fecha de vencimiento (null = permanente)
            $table->string('certificate_number')->nullable(); // Número de certificado
            $table->string('status')->default('active'); // active, expired, revoked, in_progress, planned
            $table->date('planned_date')->nullable(); // Fecha planificada para obtenerla
            $table->integer('priority')->default(0); // Prioridad en el roadmap personal (1-5)
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'certification_id']);
            $table->index(['user_id', 'status']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_certifications');
    }
};
