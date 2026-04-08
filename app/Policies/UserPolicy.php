<?php

namespace App\Policies;

use App\Access\Controls\UserControl;
use App\Models\User;

class UserPolicy
{
    protected string $control = UserControl::class;
}
