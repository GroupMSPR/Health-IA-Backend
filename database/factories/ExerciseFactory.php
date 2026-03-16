<?php

namespace Database\Factories;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Exercise>
 */
class ExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->randomElement(['Cardio', 'Strength', 'Flexibility', 'Powerlifting']),
            'difficulty_level' => $this->faker->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'target_muscle' => $this->faker->randomElement(['Chest', 'Back', 'Legs', 'Arms', 'Core']),
            'secondary_muscle' => $this->faker->randomElement(['Shoulders', 'Triceps', 'Biceps', 'Calves', 'None']),
            'equipment' => $this->faker->randomElement(['Dumbbells', 'Barbell', 'Machine', 'Bodyweight', 'Cables']),
            'instructions' => $this->faker->paragraph(),
        ];
    }
}
