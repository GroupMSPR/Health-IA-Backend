<?php

namespace App\Policies;

use App\Access\Controls\MuscleControl;
use Lomkit\Access\Policies\ControlledPolicy;

class MusclePolicy extends ControlledPolicy
{
    protected string $control = MuscleControl::class;
}
