<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
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
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'age' => $this->faker->numberBetween(1, 130),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'weight' => $weight,
            'height' => $height,
            'bmi' => $bmi,
            'body_fat_pct' => $this->faker->numberBetween(1, 100),
            'goal' => $this->faker->randomElement(['weight_loss', 'muscle_gain', 'maintenance']),
            'subscription' => $this->faker->randomElement(['free', 'premium', 'ultra']),
        ];
    }
}
