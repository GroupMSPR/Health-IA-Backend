<?php

namespace Database\Seeders;

use App\Models\Constraint;
use Illuminate\Database\Seeder;

class ConstraintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Constraint::factory()->count(10)->create();
    }
}
