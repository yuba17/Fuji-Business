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
        Schema::table('user_certifications', function (Blueprint $table) {
            $table->foreignId('certification_plan_id')->nullable()->after('certification_id')
                ->constrained('certification_plans')->onDelete('set null');
            $table->timestamp('assigned_from_plan_at')->nullable()->after('certification_plan_id');
            
            $table->index('certification_plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_certifications', function (Blueprint $table) {
            $table->dropForeign(['certification_plan_id']);
            $table->dropIndex(['certification_plan_id']);
            $table->dropColumn(['certification_plan_id', 'assigned_from_plan_at']);
        });
    }
};
