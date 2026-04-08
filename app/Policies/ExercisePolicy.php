<?php

namespace App\Policies;

use App\Access\Controls\ExerciseControl;
use App\Models\Exercise;

class ExercisePolicy
{
    protected string $control = ExerciseControl::class;
}
