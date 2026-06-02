<?php

namespace App\Policies;

use App\Access\Controls\ConstraintControl;
use Lomkit\Access\Policies\ControlledPolicy;

class ConstraintPolicy extends ControlledPolicy
{
    protected string $control = ConstraintControl::class;
}
