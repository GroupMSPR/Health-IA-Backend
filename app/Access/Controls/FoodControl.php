<?php

namespace App\Access\Controls;

use App\Access\Perimeters\GlobalPerimeter;
use App\Models\Food;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Lomkit\Access\Controls\Control;
use Lomkit\Access\Perimeters\Perimeter;

class FoodControl extends Control
{
     /**
      * The model the control refers to.
      * @var class-string<Model>
      */
     protected string $model = Food::class;

    /**
     * Retrieve the list of perimeter definitions for the current control.
     *
     * @return array<Perimeter> An array of Perimeter objects.
     */
    protected function perimeters(): array
    {
        return [
            GlobalPerimeter::new()
                ->allowed(function (Model $user, string $method) {
                    $ability = match($method) {
                        'viewAny', 'view' => 'view-foods',
                        'create' => 'create-foods',
                        'update' => 'update-foods',
                        'delete', 'restore', 'forceDelete' => 'delete-foods',
                        default => null
                    };
                    return $ability ? $user->hasPermissionTo($ability) : false;
                })
                ->should(fn (Model $user, Model $model) => true)
        ];
    }
}
