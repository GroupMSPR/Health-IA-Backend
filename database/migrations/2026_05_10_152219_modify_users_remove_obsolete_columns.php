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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('goal');
            $table->dropColumn('subscription');
            $table->dropColumn('date_subscription');
            $table->dropColumn('constraints');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('goal')->nullable();
            $table->string('subscription')->nullable();
            $table->dateTime('date_subscription')->nullable();
            $table->text('constraints')->default('Non renseigné');
        });
    }
};
