<?php

namespace Database\Seeders;

use App\Models\HealthMetric;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HealthMetricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HealthMetric::factory()->count(30)->create();
    }
}
