<?php

namespace App\Policies;

use App\Access\Controls\EquipmentControl;
use Lomkit\Access\Policies\ControlledPolicy;

class EquipmentPolicy extends ControlledPolicy
{
    protected string $control = EquipmentControl::class;
}
