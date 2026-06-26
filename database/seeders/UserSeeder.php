<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'id' => '019edc60-6a3f-73ec-afde-13d362eea794',
            'email' => 'john.doe@example.com',
        ])->assignRole('user');

        User::factory()->create([
            'id' => '019edc60-6a47-72ea-aabb-075e2caf2985',
            'email' => 'admin@healthai-coach.mspr',
        ])->assignRole('admin');
    }
}
