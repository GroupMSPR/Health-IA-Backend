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
        Schema::create('foods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('category');
            $table->decimal('calories')->default(0);
            $table->decimal('protein')->default(0);
            $table->decimal('carbohydrates')->default(0);
            $table->decimal('fat')->default(0);
            $table->decimal('fiber')->default(0);
            $table->decimal('sugars')->default(0);
            $table->smallInteger('sodium')->default(0);
            $table->smallInteger('cholesterol')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
