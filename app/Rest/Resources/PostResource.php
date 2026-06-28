<?php

namespace App\Rest\Resources;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\MutateRequest;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsTo;
use Lomkit\Rest\Relations\BelongsToMany;
use Lomkit\Rest\Relations\HasMany;

class PostResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = Post::class;

    /**
     * The exposed fields that could be provided
     */
    public function fields(RestRequest $request): array
    {
        return [
            'id',
            'user_id',
            'text',
            'image',
            'like_count',
            'created_on',
        ];
    }

    /**
     * The exposed relations that could be provided
     */
    public function relations(RestRequest $request): array
    {
        return [
            BelongsTo::make('user', UserResource::class),
            HasMany::make('comments', CommentResource::class),
            BelongsToMany::make('likers', UserResource::class),
        ];
    }

    /**
     * The exposed scopes that could be provided
     */
    public function scopes(RestRequest $request): array
    {
        return [];
    }

    /**
     * The exposed limits that could be provided
     */
    public function limits(RestRequest $request): array
    {
        return [
            10,
            25,
            50,
        ];
    }

    /**
     * The actions that should be linked
     */
    public function actions(RestRequest $request): array
    {
        return [];
    }

    /**
     * The instructions that should be linked
     */
    public function instructions(RestRequest $request): array
    {
        return [];
    }

    public function rules(RestRequest $request): array
    {
        return [
            'text' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
        ];
    }

    public function createRules(RestRequest $request)
    {
        return [
            'text' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
        ];
    }

    public function mutating(MutateRequest $request, array $requestBody, Model $model): void
    {
        if ($requestBody['operation'] === 'create') {
            $model->user_id = $request->user()->getKey();
        }
    }
}
