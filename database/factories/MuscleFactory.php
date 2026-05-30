<?php

namespace Database\Factories;

use App\Models\Muscle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Muscle>
 */
class MuscleFactory extends Factory
{
    protected $model = Muscle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $muscles = [
            'Quadriceps',
            'Fessiers',
            'Mollets',
            'Pectoraux',
            'Grand dorsal',
            'Trapèzes',
            'Biceps',
            'Triceps',
            'Abdominaux',
            'Lombaires',
            'Avant-bras',
            'Tibial antérieur',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($muscles),
        ];
    }
}
