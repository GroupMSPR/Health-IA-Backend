<?php

namespace MSPR2\SdkIA\Handlers;

use App\Models\Exercise;
use App\Models\User;

class IllegalExercisesHandler
{
    public function isLegal(Exercise $exercise, User $user): bool
    {
        //contrainte des exercices
        $userConstraintsIds = $user->constraints()->pluck('id');

        $exerciseConstraintsIds = $exercise->constraints()->pluck('id');

        $constraintsCommon = $exerciseConstraintsIds->intersect($userConstraintsIds);

        if ($constraintsCommon->isNotEmpty()) {
            return false;
        }

       //le goal de l'exercice doit matcher avec le goal du user
        $userGoalIds = $user->goals()->pluck('id');

        $exerciseGoalsIds = $exercise->goals()->pluck('id');

        if ($userGoalIds->isNotEmpty() && $exerciseGoalsIds->intersect($userGoalIds)->isEmpty()) {
            return false;
        }

        return true;
    }
}
