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
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->foreignUuid('post_id')->references('id')->on('posts');
            $table->uuid('parent_id')->nullable();
            $table->text('content');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('comments')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
