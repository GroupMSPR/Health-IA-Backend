<?php

namespace Database\Seeders;

use App\Models\Constraint;
use App\Models\Equipment;
use App\Models\Goal;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $goals = Goal::all();
        $constraints = Constraint::all();
        $subscriptions = Subscription::all();
        $equipments = Equipment::all();

        if ($users->isEmpty()) {
            $this->command->info('pas de user trouvé, faut lancer le UserSeeder avant');
            return;
        }

        foreach ($users as $user) {
            if ($goals->isNotEmpty()) {
                $user->goals()->syncWithoutDetaching(
                    $goals->random(min(2, $goals->count()))->pluck('id')->toArray()
                );
            }

            if ($constraints->isNotEmpty() && rand (0, 1)) {
                $user->constraints()->syncWithoutDetaching(
                    $constraints->random(min(2, $constraints->count()))->pluck('id')->toArray()
                );
            }

            if ($subscriptions->isNotEmpty()) {
                $user->subscriptions()->syncWithoutDetaching([
                    $subscriptions->random()->id => [
                        'started_at' => now()->subMonths(rand(1, 12)),
                        'ended_at' => null,
                    ]
                ]);
            }

            if ($equipments->isNotEmpty()) {
                $user->equipments()->syncWithoutDetaching(
                    $equipments->random(min(3, $equipments->count()))->pluck('id')->toArray()
                );
            }
        }

        $this->command->info('relations user seedés');
    }
}
