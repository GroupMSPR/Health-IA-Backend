<?php

namespace Database\Factories;

use App\Models\Goal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Goal>
 */
class GoalFactory extends Factory
{
    protected $model = Goal::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $goals = [
            'Perte de poids',
            'Prise de masse',
            'Endurance cardiovasculaire',
            'Renforcement musculaire',
            'Amélioration du sommeil',
            'Maintien de la forme',
            'Flexibilité et mobilité',
            'Performance sportive',
        ];

        return [
            'goal' => $this->faker->unique()->randomElement($goals),
        ];
    }
}
