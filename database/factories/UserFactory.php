<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'age' => $this->faker->numberBetween(1, 100),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'weight' => $this->faker->numberBetween(1, 120),
            'height' => $this->faker->numberBetween(1, 300),
            'bmi' => $this->faker->numberBetween(1, 50),
            'body_fat_pct' => $this->faker->numberBetween(1, 100),
            'goal' => $this->faker->text(),
            'subscription' => $this->faker->text(),
        ];
    }
}
