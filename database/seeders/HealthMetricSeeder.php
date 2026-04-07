<?php

namespace Database\Seeders;

use App\Models\HealthMetric;
use App\Models\User;
use Illuminate\Database\Seeder;

class HealthMetricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            HealthMetric::factory()->count(5)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
