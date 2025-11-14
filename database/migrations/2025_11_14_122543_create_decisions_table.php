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
        Schema::create('decisions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('decision_date');
            $table->foreignId('proponent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('impact_type', ['strategic', 'operational', 'commercial', 'hr', 'economic'])->nullable();
            $table->enum('status', ['proposed', 'discussion', 'pending_approval', 'approved', 'rejected', 'implemented', 'reviewed'])->default('proposed');
            $table->text('alternatives_considered')->nullable();
            $table->text('rationale')->nullable();
            $table->text('expected_impact')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['decision_date', 'status']);
            $table->index('impact_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decisions');
    }
};
