<?php

namespace App\Rest\Resources;

use App\Models\Comment;
use App\Rest\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\MutateRequest;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsTo;
use Lomkit\Rest\Relations\HasMany;

class CommentResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    public static $model = Comment::class;

    /**
     * The exposed fields that could be provided
     * @param RestRequest $request
     * @return array
     */
    public function fields(\Lomkit\Rest\Http\Requests\RestRequest $request): array
    {
        return [
            'id',
            'user_id',
            'post_id',
            'parent_id',
            'content',
        ];
    }

    /**
     * The exposed relations that could be provided
     * @param RestRequest $request
     * @return array
     */
    public function relations(\Lomkit\Rest\Http\Requests\RestRequest $request): array
    {
        return [
            BelongsTo::make('user', UserResource::class),
            BelongsTo::make('post', PostResource::class),
            HasMany::make('replies', CommentResource::class),
        ];
    }

    /**
     * The exposed scopes that could be provided
     * @param RestRequest $request
     * @return array
     */
    public function scopes(\Lomkit\Rest\Http\Requests\RestRequest $request): array
    {
        return [];
    }

    /**
     * The exposed limits that could be provided
     * @param RestRequest $request
     * @return array
     */
    public function limits(\Lomkit\Rest\Http\Requests\RestRequest $request): array
    {
        return [
            10,
            25,
            50
        ];
    }

    /**
     * The actions that should be linked
     * @param RestRequest $request
     * @return array
     */
    public function actions(\Lomkit\Rest\Http\Requests\RestRequest $request): array
    {
        return [];
    }

    /**
     * The instructions that should be linked
     * @param RestRequest $request
     * @return array
     */
    public function instructions(\Lomkit\Rest\Http\Requests\RestRequest $request): array
    {
        return [];
    }

    public function rules(RestRequest $request): array
    {
        return [
            'content' => ['string'],
            'parent_id' => ['uuid', 'nullable', 'exists:comments,id'],
        ];
    }

    public function createRules(RestRequest $request)
    {
        return [
            'content' => ['required'],
            'post_id' => ['required', 'uuid', 'exists:posts,id'],
        ];
    }

    public function mutating(MutateRequest $request, array $requestBody, Model $model): void
    {
        if ($requestBody['operation'] === 'create') {
            $model->user_id = $request->user()->getKey();
        }
    }
}
