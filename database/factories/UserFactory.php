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
            'last_name' => $this->faker->lastName(),
            'first_name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password123'),
            'birthdate' => $this->faker->dateTimeBetween('-35 years', '-25 years'),
            'gender' => $this->faker->randomElement(['Homme', 'Femme', 'Autres']),
            'weight' => $weight,
            'height' => $height,
            'bmi' => $bmi,
            'body_fat_pct' => $this->faker->numberBetween(1, 100),
            'physical_activity_level' => $this->faker->randomElement(['Sedentaire', 'Moyennement Actif(ve)', 'Actif(ve)']),
            'daily_caloric_intake' => $this->faker->numberBetween(1200, 5000),
        ];
    }
}
