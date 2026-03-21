<?php

namespace Database\Factories;

use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Food>
 */
class FoodFactory extends Factory
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
            'category' => $this->faker->word(),
            'calories' => $this->faker->randomFloat(2, 0, 1000),
            'protein' => $this->faker->randomFloat(2, 0, 100),
            'carbohydrates' => $this->faker->randomFloat(2, 0, 100),
            'fat' => $this->faker->randomFloat(2, 0, 100),
            'fiber' => $this->faker->randomFloat(2, 0, 100),
            'sugars' => $this->faker->randomFloat(2, 0, 100),
            'sodium' => $this->faker->numberBetween(0, 3000),
            'cholesterol' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
