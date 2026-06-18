<?php

namespace App\Access\Controls;

use App\Access\Perimeters\GlobalPerimeter;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Access\Controls\Control;
use Lomkit\Access\Perimeters\Perimeter;

class GoalControl extends Control
{
    /**
     * The model the control refers to.
     *
     * @var class-string<Model>
     */
    protected string $model = Goal::class;

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
                        'viewAny', 'view' => 'view-goals',
                        'create' => 'create-goals',
                        'update' => 'update-goals',
                        'delete', 'restore', 'forceDelete' => 'delete-goals',
                        default => null,
                    };

                    return $ability ? $user->hasPermissionTo($ability) : false;
                })
                ->should(function (User $user, Goal $model) {
                    $method = request()->route()?->getActionMethod();

                    if (in_array($method, ['destroy', 'restore', 'forceDelete'])) {
                        return $model->users()->where('users.id', $user->getKey())->exists();
                    }

                    return true;
                }),
        ];
    }
}
