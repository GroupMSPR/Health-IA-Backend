<?php

namespace App\Policies;

use App\Access\Controls\HealthMetricControl;
use Lomkit\Access\Policies\ControlledPolicy;

class HealthMetricPolicy extends ControlledPolicy
{
    protected string $control = HealthMetricControl::class;
}
