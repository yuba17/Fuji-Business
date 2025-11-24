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
            $table->decimal('tax_rate', 5, 2)->nullable()->after('cost_yearly'); // porcentaje (ej: 21.00)
            $table->decimal('cost_monthly_with_tax', 12, 2)->nullable()->after('tax_rate');
            $table->decimal('cost_yearly_with_tax', 12, 2)->nullable()->after('cost_monthly_with_tax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infrastructures', function (Blueprint $table) {
            $table->dropColumn([
                'tax_rate',
                'cost_monthly_with_tax',
                'cost_yearly_with_tax',
            ]);
        });
    }
};
