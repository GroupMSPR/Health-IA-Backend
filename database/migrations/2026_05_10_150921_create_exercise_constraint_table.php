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
        Schema::create('exercise_constraint', function (Blueprint $table) {
            $table->foreignUuid('exercise_id')->constrained('exercises');
            $table->foreignUuid('constraint_id')->constrained('constraints');
            $table->timestamps();
            $table->unique(['exercise_id', 'constraint_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_constraint');
    }
};
