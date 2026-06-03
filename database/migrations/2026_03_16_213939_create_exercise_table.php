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
        Schema::create('exercises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('image')->nullable();
            $table->text('instructions');
            $table->string('short_description');
            $table->string('category');
            $table->string('sub_category');
            $table->text('target_muscle');
            $table->text('secondary_muscle');
            $table->text('equipment');
            $table->string('difficulty_level');
            $table->integer('rep_range_min');
            $table->integer('rep_range_max');
            $table->integer('recommended_duration_seconds');
            $table->integer('recommended_rest_minutes');
            $table->integer('estimated_calories_per_minutes');
            $table->string('range_of_motion');
            $table->string('injury_risk_level');
            $table->foreignUuid('next_progression_exercise')->nullable();
            $table->foreignUuid('previous_progression_exercise')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
