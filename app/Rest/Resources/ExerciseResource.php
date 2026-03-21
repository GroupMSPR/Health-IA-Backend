<?php

namespace App\Rest\Resources;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;

class ExerciseResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = Exercise::class;

    /**
     * The exposed fields that could be provided
     */
    public function fields(RestRequest $request): array
    {
        return [
            'name',
            'type',
            'difficulty_level',
            'target_muscle',
            'secondary_muscle',
            'equipment',
            'instructions',
        ];
    }

    /**
     * The exposed relations that could be provided
     */
    public function relations(RestRequest $request): array
    {
        return [];
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

    /**
     * @return array[]
     */
    public function rules(RestRequest $request): array
    {
        return [
            'name' => ['string'],
            'type' => ['string'],
            'difficulty_level' => ['string'],
            'target_muscle' => ['string'],
            'secondary_muscle' => ['string'],
            'equipment' => ['string'],
            'instructions' => ['string'],
        ];
    }

    /**
     * @return array[]
     */
    public function createRules(RestRequest $request): array
    {
        return [
            'name' => ['required'],
            'type' => ['required'],
            'difficulty_level' => ['required'],
            'target_muscle' => ['required'],
            'secondary_muscle' => ['required'],
            'equipment' => ['required'],
            'instructions' => ['required'],
        ];
    }
}
