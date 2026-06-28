<?php

namespace App\Access\Controls;

use App\Access\Perimeters\GlobalPerimeter;
use App\Models\Constraint;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Access\Controls\Control;
use Lomkit\Access\Perimeters\Perimeter;

class ConstraintControl extends Control
{
    /**
     * The model the control refers to.
     *
     * @var class-string<Model>
     */
    protected string $model = Constraint::class;

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
                    if ($user && in_array($method, ['viewAny', 'view', 'search'])) {
                        return true;
                    }
                    $ability = match ($method) {
                        'viewAny', 'view' => 'view-constraints',
                        'create' => 'create-constraints',
                        'update' => 'update-constraints',
                        'delete', 'restore', 'forceDelete' => 'delete-constraints',
                        default => null
                    };

                    return $ability ? $user->hasPermissionTo($ability) : false;
                })
                ->should(fn (Model $user, Model $method) => true),

        ];
    }
}
