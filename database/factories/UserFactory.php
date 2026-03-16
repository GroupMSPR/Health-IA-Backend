<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $weight = $this->faker->numberBetween(40, 300);
        $height = $this->faker->numberBetween(100, 300);

        $heightInMeters = $height / 100;
        $bmi = round($weight / ($heightInMeters * $heightInMeters), 2);

        return [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'age' => $this->faker->numberBetween(1, 130),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'weight' => $weight,
            'height' => $height,
            'bmi' => $bmi,
            'body_fat_pct' => $this->faker->numberBetween(1, 100),
            'disease_type' => $this->faker->randomElement(['Diabetes', 'Hypertension', 'Obesity', 'None']),
            'severity' => $this->faker->randomElement(['Mild', 'Moderate', 'Severe']),
            'physical_activity_level' => $this->faker->randomElement(['Sedentary', 'Moderate', 'Active']),
            'daily_caloric_intake' => $this->faker->numberBetween(1200, 5000),
            'goal' => $this->faker->randomElement(['weight_loss', 'muscle_gain', 'maintenance']),
            'subscription' => $this->faker->randomElement(['free', 'premium', 'ultra']),
        ];
    }
}
