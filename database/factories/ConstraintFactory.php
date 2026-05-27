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
            ['name' => 'Allergie gluten', 'description' => 'Intolérance ou allergie au gluten', 'severity' => 'high'],
            ['name' => 'Allergie lactose', 'description' => 'Intolérance au lactose', 'severity' => 'medium'],
            ['name' => 'Blessure genou', 'description' => 'Douleur ou blessure au niveau du genou', 'severity' => 'high'],
            ['name' => 'Blessure dos', 'description' => 'Douleur lombaire ou dorsale', 'severity' => 'high'],
            ['name' => 'Blessure épaule', 'description' => 'Douleur ou blessure à l\'épaule', 'severity' => 'medium'],
            ['name' => 'Diabète type 2', 'description' => 'Diabète de type 2 nécessitant un suivi', 'severity' => 'high'],
            ['name' => 'Hypertension', 'description' => 'Pression artérielle élevée', 'severity' => 'medium'],
            ['name' => 'Végétarien', 'description' => 'Régime sans viande', 'severity' => 'low'],
            ['name' => 'Végan', 'description' => 'Régime sans produits animaux', 'severity' => 'low'],
            ['name' => 'Allergie noix', 'description' => 'Allergie aux fruits à coque', 'severity' => 'high'],
        ];

        $constraint = $this->faker->unique()->randomElement($constraints);

        return [
            'name' => $constraint['name'],
            'description' => $constraint['description'],
            'severity' => $constraint['severity'],
        ];
    }
}
