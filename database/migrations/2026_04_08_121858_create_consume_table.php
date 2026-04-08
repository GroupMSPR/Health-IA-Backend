<?php

use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consume', function (Blueprint $table) {
            $table->uuid('consume_id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->foreignUuid('food_id')->references('id')->on('foods');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consume');
    }
};
