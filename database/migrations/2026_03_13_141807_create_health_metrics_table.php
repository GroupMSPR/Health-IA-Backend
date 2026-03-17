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
        Schema::create('health_metrics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('date');
            $table->decimal('start_weight')->default(0);
            $table->decimal('current_weight')->default(0);
            $table->decimal('avg_bpm')->default(0);
            $table->decimal('max_bpm')->default(0);
            $table->decimal('resting_bpm')->default(0);
            $table->smallInteger('steps_count')->default(0);
            $table->time('sleep_time');
            $table->decimal('calories_burned')->default(0);
            $table->decimal('active_minute')->default(0);
            $table->string('workout_type')->default('none');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_metrics');
    }
};
