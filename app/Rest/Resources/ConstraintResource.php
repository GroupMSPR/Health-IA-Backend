<?php

namespace App\Rest\Resources;

use App\Models\Constraint;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsToMany;

class ConstraintResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = Constraint::class;

    /**
     * The exposed fields that could be provided
     */
    public function fields(RestRequest $request): array
    {
        return [
            'name',
            'description',
            'severity',
        ];
    }

    /**
     * The exposed relations that could be provided
     */
    public function relations(RestRequest $request): array
    {
        return [
            BelongsToMany::make('users', UserResource::class),
            BelongsToMany::make('exercises', ExerciseResource::class),
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

    public function rules(RestRequest $request)
    {
        return [
            'name' => ['string', 'max:255'],
            'description' => ['nullable', 'string'],
            'severity' => ['nullable', 'string', 'in:low,medium,high'],
        ];
    }

    public function createRules(RestRequest $request)
    {
        return [
            'name' => ['required'],
        ];
    }
}
