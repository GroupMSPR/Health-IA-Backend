<?php

namespace App\Access\Controls;

use App\Access\Perimeters\GlobalPerimeter;
use App\Access\Perimeters\OwnPerimeter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Lomkit\Access\Controls\Control;
use Lomkit\Access\Perimeters\Perimeter;

class UserControl extends Control
{
     /**
      * The model the control refers to.
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
                ->allowed(fn (Model $user, string $method) => $user->hasRole('admin'))
                ->should(fn (Model $user, Model $model) => true),

            OwnPerimeter::new()
                ->allowed(fn (Model $user, string $method) => in_array($method, ['view', 'update']))
                ->should(fn (Model $user, Model $model) => $model->id === $user->id)
                ->query(fn (Model $user, $query) => $query->where('id', $user->id))
        ];
    }
}
