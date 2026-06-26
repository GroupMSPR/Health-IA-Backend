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
        Schema::create('exercise_equipment', function (Blueprint $table) {
            $table->foreignUuid('exercise_id')->constrained('exercises');
            $table->foreignUuid('equipment_id')->constrained('equipments');
            $table->timestamps();
            $table->unique(['exercise_id', 'equipment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_equipment');
    }
};
