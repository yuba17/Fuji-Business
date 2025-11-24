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
        Schema::table('infrastructures', function (Blueprint $table) {
            $table->string('tracking_mode', 20)
                ->default('individual')
                ->after('infrastructure_class');

            $table->integer('quantity_in_use')
                ->default(0)
                ->after('quantity');

            $table->integer('quantity_reserved')
                ->default(0)
                ->after('quantity_in_use');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infrastructures', function (Blueprint $table) {
            $table->dropColumn(['tracking_mode', 'quantity_in_use', 'quantity_reserved']);
        });
    }
};
