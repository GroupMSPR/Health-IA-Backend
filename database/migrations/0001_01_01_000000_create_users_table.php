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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->date('birthdate');
            $table->string('gender');
            $table->decimal('weight');
            $table->integer('height');
            $table->decimal('bmi', 5)->default(0);
            $table->decimal('body_fat_pct');
            $table->text('constraints')->default('Non renseigné');
            $table->string('physical_activity_level');
            $table->integer('daily_caloric_intake');
            $table->text('goal');
            $table->string('subscription');
            $table->dateTime('date_subscription')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
