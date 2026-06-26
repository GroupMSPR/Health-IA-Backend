<?php

namespace App\Policies;

use App\Access\Controls\FoodControl;
use Lomkit\Access\Policies\ControlledPolicy;

class FoodPolicy extends ControlledPolicy
{
    protected string $control = FoodControl::class;
}
