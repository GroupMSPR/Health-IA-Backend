<?php

namespace App\Access\Controls;

use App\Access\Perimeters\GlobalPerimeter;
use App\Models\Exercise;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Access\Controls\Control;
use Lomkit\Access\Perimeters\Perimeter;

class ExerciseControl extends Control
{
    /**
     * The model the control refers to.
     *
     * @var class-string<Model>
     */
    protected string $model = Exercise::class;

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
                    $ability = match ($method) {
                        'viewAny', 'view' => 'view-exercises',
                        'create' => 'create-exercises',
                        'update' => 'update-exercises',
                        'delete', 'restore', 'forceDelete' => 'delete-exercises',
                        default => null
                    };

                    return $ability ? $user->hasPermissionTo($ability) : false;
                })

                ->should(fn (Model $user, Model $model) => true),
        ];
    }
}
