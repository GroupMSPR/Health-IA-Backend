<?php

namespace App\Access\Controls;

use App\Access\Perimeters\GlobalPerimeter;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Access\Controls\Control;

class PostControl extends Control
{
    protected string $model = Post::class;

    protected function perimeters(): array
    {
        return [
            GlobalPerimeter::new()
                ->allowed(function (Model $user, string $method) {
                    $ability = match ($method) {
                        'viewAny', 'view', 'search' => 'view-posts',
                        'create' => 'create-posts',
                        'update' => 'update-posts',
                        'delete', 'restore', 'forceDelete' => 'delete-posts',
                        default => null,
                    };

                    return $ability ? $user->hasPermissionTo($ability, 'api') : false;
                })
                ->should(function (Model $user, Post $model) {
                    if ($model->exists) {
                        return $model->user_id === $user->getKey();
                    }
                    return true;
                }),
        ];
    }
}
