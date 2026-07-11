<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Normalize legacy user enum values to their canonical stored form:
 *   - gender:                    male/female/other  ->  Homme/Femme/Autres
 *   - physical_activity_level:   FR wording         ->  sedentary/moderate/active
 *   - favorite_exercise_category: casing            ->  Poids du corps
 *
 * Idempotent: WHERE clauses only match legacy values, so re-running (or running
 * on an already-canonical database) is a no-op. DB::table() bypasses the
 * SoftDeletes scope so trashed rows are normalized too.
 */
return new class extends Migration
{
    /**
     * @var array<string, array<string, string>>
     */
    private array $mappings = [
        'gender' => [
            'male' => 'Homme',
            'female' => 'Femme',
            'other' => 'Autres',
        ],
        'physical_activity_level' => [
            'Sedentaire' => 'sedentary',
            'Sédentaire' => 'sedentary',
            'Moyennement Actif(ve)' => 'moderate',
            'Moyennement actif' => 'moderate',
            'Actif(ve)' => 'active',
            'Actif' => 'active',
        ],
        'favorite_exercise_category' => [
            'Poids du Corps' => 'Poids du corps',
        ],
    ];

    public function up(): void
    {
        foreach ($this->mappings as $column => $map) {
            foreach ($map as $legacy => $canonical) {
                DB::table('users')
                    ->where($column, $legacy)
                    ->update([$column => $canonical]);
            }
        }
    }

    public function down(): void
    {
        // One-way data normalization: canonical values are intentionally kept.
    }
};
