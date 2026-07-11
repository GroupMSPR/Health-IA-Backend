<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PostgreSQL does not automatically index the *referencing* column of a foreign
 * key (only the referenced primary key gets an index). This adds the missing
 * single-column indexes on FK columns to speed up joins, filtered lookups and
 * ON DELETE/UPDATE cascade checks.
 *
 * Only columns that were NOT already the leading column of an existing index
 * (PK / unique / composite) are listed. Framework/package tables (spatie's
 * role_has_permissions, etc.) are intentionally left untouched.
 */
return new class extends Migration
{
    /**
     * @var array<string, array<int, string>>
     */
    private array $indexes = [
        'comments' => ['parent_id', 'post_id', 'user_id'],
        'consume' => ['food_id', 'user_id'],
        'exercise_constraint' => ['constraint_id'],
        'exercise_equipment' => ['equipment_id'],
        'exercise_goal' => ['goal_id'],
        'food_constraint' => ['constraint_id'],
        'foods' => ['user_id'],
        'health_metrics' => ['user_id'],
        'likes' => ['post_id'],
        'posts' => ['user_id'],
        'practice' => ['exercise_id', 'user_id'],
        'primary_muscle' => ['muscle_id'],
        'secondary_muscle' => ['muscle_id'],
        'user_constraint' => ['constraint_id'],
        'user_equipment' => ['equipment_id'],
        'user_goal' => ['goal_id'],
        'user_subscription' => ['subscription_id'],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $table => $columns) {
            Schema::table($table, function (Blueprint $t) use ($columns) {
                foreach ($columns as $column) {
                    $t->index($column);
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $table => $columns) {
            Schema::table($table, function (Blueprint $t) use ($columns) {
                foreach ($columns as $column) {
                    $t->dropIndex([$column]);
                }
            });
        }
    }
};
