<?php

namespace Database\Seeders;

use App\Models\Constraint;
use App\Models\Equipment;
use App\Models\Exercise;
use App\Models\Goal;
use App\Models\Muscle;
use Illuminate\Database\Seeder;

class ExerciseRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exercises = Exercise::all();

        if ($exercises->isEmpty()) {
            $this->command->info('aucun exercice trouvé, faut passer ExerciseSeeder avant');

            return;
        }

        $goals = Goal::all();
        $constraints = Constraint::all();
        $equipments = Equipment::all();
        $muscles = Muscle::all();

        foreach ($exercises as $exercise) {
            if ($goals->isNotEmpty()) {
                $exercise->goals()->syncWithoutDetaching(
                    $goals->random(min(2, $goals->count()))->pluck('id')->toArray()
                );
            }

            if ($constraints->isNotEmpty() && rand(0, 1)) {
                $exercise->constraints()->syncWithoutDetaching(
                    [$constraints->random()->id]
                );
            }

            if ($equipments->isNotEmpty()) {
                $exercise->equipments()->syncWithoutDetaching(
                    [$equipments->random()->id]
                );
            }

            if ($muscles->isNotEmpty()) {
                $exercise->primaryMuscles()->syncWithoutDetaching(
                    [$muscles->random()->id]
                );
            }

            if ($muscles->count() > 1) {
                $exercise->secondaryMuscles()->syncWithoutDetaching(
                    $muscles->random(min(2, $muscles->count()))->pluck('id')->toArray()
                );
            }
        }
        $this->command->info('relation exercices seedés');
    }
}
