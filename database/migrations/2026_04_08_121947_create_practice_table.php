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
        Schema::create('practice', function (Blueprint $table) {
            $table->uuid('practice_id')->primary();
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->foreignUuid('exercise_id')->references('id')->on('exercises');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice');
    }
};
