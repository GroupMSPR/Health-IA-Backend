<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ExerciseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'instructions' => $this->faker->paragraph(),
            'short_description' => $this->faker->sentence(),
            'category' => $this->faker->randomElement(['Cardio', 'Strength', 'Flexibility', 'Balance']),
            'sub_category' => $this->faker->randomElement(['HIIT', 'Bodyweight', 'Machine', 'Free weights']),
            'target_muscle' => $this->faker->randomElement(['Chest', 'Back', 'Legs', 'Arms', 'Core']),
            'secondary_muscle' => $this->faker->randomElement(['Shoulders', 'Triceps', 'Biceps', 'None']),
            'equipment' => $this->faker->randomElement(['Dumbbells', 'Barbell', 'Bodyweight']),
            'difficulty_level' => $this->faker->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'rep_range_min' => $this->faker->numberBetween(5, 10),
            'rep_range_max' => $this->faker->numberBetween(10, 20),
            'recommended_duration_seconds' => $this->faker->numberBetween(30, 600),
            'recommended_rest_minutes' => $this->faker->numberBetween(1, 5),
            'estimated_calories_per_minutes' => $this->faker->numberBetween(5, 15),
            'range_of_motion' => $this->faker->randomElement(['Short', 'Full']),
            'injury_risk_level' => $this->faker->randomElement(['Low', 'Medium', 'High']),
            'next_progression_exercise' => Str::uuid(),
            'previous_progression_exercise' => Str::uuid(),
        ];
    }
}
