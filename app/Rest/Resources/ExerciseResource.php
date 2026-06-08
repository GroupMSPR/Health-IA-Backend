<?php

namespace App\Rest\Resources;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsToMany;

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
            'image',
            'category',
            'sub_category',
            'difficulty_level',
            'instructions',
            'short_description',
            'target_muscle',
            'secondary_muscle',
            'short_description',
            'equipment',
            'rep_range_min',
            'rep_range_max',
            'recommended_duration_seconds',
            'recommended_rest_minutes',
            'estimated_calories_per_minutes',
            'range_of_motion',
            'injury_risk_level',
            'next_progression_exercise',
            'previous_progression_exercise',
            'created_at',
        ];
    }

    public function searchables(RestRequest $request): array
    {
        return [
            'id',
            'name',
            'category',
            'difficulty_level',
            'target_muscle',
            'equipment',
            'created_at',
            'estimated_calories_per_minutes',
            'recommended_duration_seconds'
        ];
    }

    /**
     * The exposed relations that could be provided
     */
    public function relations(RestRequest $request): array
    {
        return [
            BelongsToMany::make('users', UserResource::class),
            BelongsToMany::make('goals', GoalResource::class),
            BelongsToMany::make('constraints', ConstraintResource::class),
            BelongsToMany::make('equipments', EquipmentResource::class),
            BelongsToMany::make('primaryMuscles', MuscleResource::class),
            BelongsToMany::make('secondaryMuscles', MuscleResource::class),
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
            'difficulty_level' => ['required'],
            'instructions' => ['required'],
        ];
    }
}
