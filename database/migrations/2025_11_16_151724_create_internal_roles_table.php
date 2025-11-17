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
        Schema::create('internal_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('track')->nullable();
            $table->string('level')->nullable();
            $table->string('role_type')->nullable();
            $table->boolean('is_critical')->default(false);
            $table->decimal('avg_cost_year', 12, 2)->nullable();
            $table->decimal('billable_ratio', 5, 2)->nullable();
            $table->unsignedInteger('max_direct_reports')->nullable();
            $table->boolean('can_see_all_teams')->default(false);
            $table->boolean('can_approve_plans')->default(false);
            $table->boolean('can_approve_certifications')->default(false);
            $table->json('key_responsibilities')->nullable();
            $table->json('expected_focus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_roles');
    }
};
