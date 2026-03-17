<?php

namespace Database\Factories;

use App\Models\HealthMetric;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HealthMetric>
 */
class HealthMetricFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = HealthMetric::class;
    public function definition(): array
    {
        $startWeight = $this->faker->randomFloat(1, 45, 130);
        $currentWeight = max(35, $startWeight + $this->faker->randomFloat(1, -0.8, 0.8));
        $restingBpm = $this->faker->randomFloat(1, 50, 85);
        $avgBpm = $this->faker->numberBetween(max(60, $restingBpm), 140);
        $maxBpm = $this->faker->numberBetween($avgBpm, 195);
        $sleepHours = $this->faker->randomFloat(1, 4.5, 9.5);
        $sleepTime = sprintf('%02d:%02d:00', (int)$sleepHours, (int)(($sleepHours - (int)$sleepHours) * 60));
        $steps = $this->faker->numberBetween(0, 25000);
        $caloriesBurned = round(($steps / 1000) * $this->faker->randomFloat(1, 30, 60), 1);

        return [
            'date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'start_weight' => $startWeight,
            'current_weight' => $currentWeight,
            'resting_bpm' => $restingBpm,
            'avg_bpm' => $avgBpm,
            'max_bpm' => $maxBpm,
            'steps_count' => $steps,
            'sleep_time' => $sleepTime,
            'calories_burned' => $caloriesBurned,
            'active_minute' => $this->faker->numberBetween(0, 180),
            'workout_type' => $this->faker->randomElement(['none', 'walk', 'run', 'cycling', 'hit', 'strength', 'swim', 'yoga'])
        ];
    }

    public function athletes(): static
    {
        return $this->state(fn () => [
            'steps_count' => $this->faker->numberBetween(12000, 25000),
            'active_minute' => $this->faker->numberBetween(60, 180),
            'workout_type' => $this->faker->randomElement(['walk', 'run', 'cycling', 'hit']),
        ]);
    }
}
