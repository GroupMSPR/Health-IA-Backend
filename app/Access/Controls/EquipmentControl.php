<?php

namespace App\Access\Controls;

use App\Access\Perimeters\GlobalPerimeter;
use App\Models\Equipment;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Access\Controls\Control;

class EquipmentControl extends Control
{
    /**
     * The model the control refers to.
     * @var class-string<Model>
     */
    protected string $model = Equipment::class;

    /**
     * Retrieve the list of perimeter definitions for the current control.
     *
     * @return array<\Lomkit\Access\Perimeters\Perimeter> An array of Perimeter objects.
     */
    protected function perimeters(): array
    {
        return [
            GlobalPerimeter::new()
                ->allowed(function (Model $user, string $method) {
                    $ability = match ($method) {
                        'viewAny', 'view' => 'view-equipments',
                        'create' => 'create-equipments',
                        'update' => 'update-equipments',
                        'delete', 'restore', 'forceDelete' => 'delete-equipments',
                        default => null
                    };
                    return $ability ? $user->hasPermissionTo($ability) : false;
                })
                ->should(fn(Model $user, Model $model) => true)
        ];
    }
}
