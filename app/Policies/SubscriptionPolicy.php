<?php

namespace App\Policies;

use App\Access\Controls\SubscriptionControl;
use Lomkit\Access\Policies\ControlledPolicy;

class SubscriptionPolicy extends ControlledPolicy
{
    protected string $control = SubscriptionControl::class;
}
