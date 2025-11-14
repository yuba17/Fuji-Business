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
        Schema::create('plan_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('content')->nullable();
            $table->string('type')->default('text'); // text, html, markdown
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['plan_id', 'order']);
            $table->unique(['plan_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_sections');
    }
};
