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
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('email');
            $table->string('avatar_url')->nullable()->after('bio');
            $table->string('phone')->nullable()->after('avatar_url');
            $table->date('joined_at')->nullable()->after('phone');
            $table->date('last_evaluation_at')->nullable()->after('joined_at');
            $table->integer('availability_percent')->default(100)->after('last_evaluation_at')->comment('Disponibilidad en porcentaje (0-100)');
            $table->json('work_preferences')->nullable()->after('availability_percent')->comment('Preferencias de trabajo (remoto, horarios, etc.)');
            $table->text('career_goals')->nullable()->after('work_preferences')->comment('Objetivos profesionales');
            $table->integer('profile_completion_percent')->default(0)->after('career_goals')->comment('Porcentaje de completitud del perfil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'avatar_url',
                'phone',
                'joined_at',
                'last_evaluation_at',
                'availability_percent',
                'work_preferences',
                'career_goals',
                'profile_completion_percent',
            ]);
        });
    }
};
