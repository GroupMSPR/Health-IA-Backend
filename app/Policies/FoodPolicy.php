<?php

namespace App\Policies;

use App\Access\Controls\FoodControl;
use App\Models\Food;

class FoodPolicy
{
    protected string $control = FoodControl::class;

    public function before($user, $ability)
    {
        dd([
            'alerte' => 'ON EST DANS LA POLICY !',
            'action_demandee' => $ability,
            'utilisateur' => $user->email
        ]);
    }
}
