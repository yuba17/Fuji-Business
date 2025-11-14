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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 7)->nullable(); // Color hex para UI
            $table->enum('category', ['domain', 'priority', 'status', 'type'])->nullable();
            $table->boolean('is_predefined')->default(false);
            $table->integer('usage_count')->default(0);
            $table->timestamps();
            
            $table->index(['category', 'is_predefined']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
