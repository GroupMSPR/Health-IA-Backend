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
        Schema::create('food_constraint', function (Blueprint $table) {
            $table->foreignUuid('food_id')->constrained('foods');
            $table->foreignUuid('constraint_id')->constrained('constraints');
            $table->timestamps();
            $table->unique(['food_id', 'constraint_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_constraint');
    }
};
