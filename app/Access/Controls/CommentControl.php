<?php

namespace App\Access\Controls;

use App\Access\Perimeters\GlobalPerimeter;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Access\Controls\Control;
use Lomkit\Access\Perimeters\Perimeter;

class CommentControl extends Control
{
    /**
     * The model the control refers to.
     *
     * @var class-string<Model>
     */
    protected string $model = Comment::class;

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
                        'viewAny', 'view' => 'view-comments',
                        'create' => 'create-comments',
                        'update' => 'update-comments',
                        'delete', 'restore', 'forceDelete' => 'delete-comments',
                        default => null,
                    };

                    return $ability ? $user->hasPermissionTo($ability, 'api') : false;
                })
                ->should(function (User $user, Comment $model) {
                    if ($model->exists) {
                        return $model->user_id === $user->getKey();
                    }
                    return true;
                }),
        ];
    }
}
