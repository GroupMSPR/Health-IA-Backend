<?php

namespace App\Access\Controls;

use App\Access\Perimeters\GlobalPerimeter;
use App\Access\Perimeters\OwnPerimeter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Access\Controls\Control;
use Lomkit\Access\Perimeters\Perimeter;

class UserControl extends Control
{
    /**
     * The model the control refers to.
     *
     * @var class-string<Model>
     */
    protected string $model = User::class;

    /**
     * Retrieve the list of perimeter definitions for the current control.
     *
     * @return array<Perimeter> An array of Perimeter objects.
     */
    protected function perimeters(): array
    {
        return [
            GlobalPerimeter::new()
                ->allowed(function ($user, string $method) {
                    if (! $user instanceof User) {
                        return false;
                    }

                    return $user->hasRole('admin');
                })
                ->should(fn ($user, Model $model) => true),

            OwnPerimeter::new()
                ->allowed(function ($user, string $method) {
                    if (! $user instanceof User) {
                        return false;
                    }

                    return in_array($method, ['viewAny', 'view', 'update', 'search']);
                })
                ->should(function ($user, Model $model) {
                    if (! $user instanceof User) {
                        return false;
                    }

                    return $model->id === $user->id;
                })
                ->query(function (Builder $query, $user) {
                    if (! $user instanceof User) {
                        return $query->whereRaw('1 = 0');
                    }

                    return $query->where('id', $user->id);
                }),
        ];
    }
}
