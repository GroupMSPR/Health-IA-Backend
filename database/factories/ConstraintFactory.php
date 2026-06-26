<?php

namespace Database\Factories;

use App\Models\Constraint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Constraint>
 */
class ConstraintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $constraints = [

            // liée au exercices
            ['name' => 'Blessure genou', 'description' => 'Douleur ou blessure au niveau du genou', 'severity' => 'high'],
            ['name' => 'Blessure dos', 'description' => 'Douleur lombaire ou dorsale', 'severity' => 'high'],
            ['name' => 'Blessure épaule', 'description' => 'Douleur ou blessure à l\'épaule', 'severity' => 'medium'],
            ['name' => 'Blessure cheville', 'description' => 'Entorse ou douleur à la cheville', 'severity' => 'medium'],
            ['name' => 'Hypertension', 'description' => 'Pression artérielle élevée', 'severity' => 'medium'],
            ['name' => 'Hernie discale', 'description' => 'Problème de colonne vertébrale', 'severity' => 'high'],
            ['name' => 'Tendinite', 'description' => 'Inflammation des tendons', 'severity' => 'medium'],
            ['name' => 'Asthme', 'description' => 'Troubles respiratoires à l\'effort', 'severity' => 'medium'],
            ['name' => 'Arthrite', 'description' => 'Inflammation des articulations', 'severity' => 'high'],

            // liée au foods
            ['name' => 'Allergie gluten', 'description' => 'Intolérance ou allergie au gluten', 'severity' => 'high'],
            ['name' => 'Allergie lactose', 'description' => 'Intolérance au lactose', 'severity' => 'medium'],
            ['name' => 'Allergie noix', 'description' => 'Allergie aux fruits à coque', 'severity' => 'high'],
            ['name' => 'Végétarien', 'description' => 'Régime alimentaire sans viande', 'severity' => 'low'],
            ['name' => 'Végan', 'description' => 'Régime sans aucun produit animal', 'severity' => 'low'],
            ['name' => 'Diabète type 2', 'description' => 'Régime alimentaire adapté au diabète', 'severity' => 'high'],

        ];

        $constraint = $this->faker->unique()->randomElement($constraints);

        return [
            'name' => $constraint['name'],
            'description' => $constraint['description'],
            'severity' => $constraint['severity'],
        ];
    }
}
