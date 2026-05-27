<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['freemium', 'premium', 'premium_plus', 'b2b'];

        foreach ($types as $type) {
            Subscription::firstOrCreate(
                ['subscription_type' => $type]
            );
        }
    }
}
