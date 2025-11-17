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
            $table->foreignId('internal_role_id')
                ->nullable()
                ->after('manager_id')
                ->constrained('internal_roles')
                ->nullOnDelete();

            $table->foreignId('area_id')
                ->nullable()
                ->after('internal_role_id')
                ->constrained('areas')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('internal_role_id');
            $table->dropConstrainedForeignId('area_id');
        });
    }
};
