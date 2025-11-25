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
        Schema::table('certifications', function (Blueprint $table) {
            $table->dropColumn(['badge_icon', 'badge_color', 'points_reward']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certifications', function (Blueprint $table) {
            $table->string('badge_icon')->nullable()->after('image_url');
            $table->string('badge_color')->nullable()->after('badge_icon');
            $table->integer('points_reward')->default(0)->after('badge_color');
        });
    }
};
