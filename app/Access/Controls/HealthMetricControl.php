<?php

namespace App\Access\Controls;

use App\Access\Perimeters\GlobalPerimeter;
use App\Access\Perimeters\OwnPerimeter;
use App\Models\HealthMetric;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Lomkit\Access\Controls\Control;
use Lomkit\Access\Perimeters\Perimeter;

class HealthMetricControl extends Control
{
     /**
      * The model the control refers to.
      * @var class-string<Model>
      */
     protected string $model = HealthMetric::class;

    /**
     * Retrieve the list of perimeter definitions for the current control.
     *
     * @return array<Perimeter> An array of Perimeter objects.
     */
    protected function perimeters(): array
    {
        return [
            GlobalPerimeter::new()
                ->allowed(fn (Model $user, string $method) => $user->hasRole('admin') || $user->hasRole('coach'))
                ->should(fn (Model $user, Model $model) => true),

            OwnPerimeter::new()
                ->allowed(function (Model $user, string $method) {
                    $ability = match($method) {
                        'viewAny', 'view' => 'view-health-metrics',
                        'create' => 'create-health-metrics',
                        'update' => 'update-health-metrics',
                        'delete', 'restore', 'forceDelete' => 'delete-health-metrics',
                        default => null
                    };
                    return $ability ? $user->hasPermissionTo($ability) : false;
                })
                ->should(fn (Model $user, Model $model) => $model->user_id === $user->id)
                ->query(fn (Model $user, $query) => $query->where('user_id', $user->id))
        ];
    }
}
