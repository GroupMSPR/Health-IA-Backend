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
        Schema::create('secondary_muscle', function (Blueprint $table) {
            $table->foreignUuid('exercise_id')->constrained('exercises');
            $table->foreignUuid('muscle_id')->constrained('muscles');
            $table->timestamps();
            $table->unique(['exercise_id', 'muscle_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secondary_muscle');
    }
};
