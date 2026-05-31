<?php

namespace App\Rest\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\MutateRequest;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsToMany;
use Lomkit\Rest\Relations\HasMany;

class UserResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = User::class;

    /**
     * The exposed fields that could be provided
     */
    public function fields(RestRequest $request): array
    {
        return [
            'last_name',
            'first_name',
            'email',
            'password',
            'profile_picture',
            'birthdate',
            'gender',
            'weight',
            'height',
            'bmi',
            'body_fat_pct',
            'physical_activity_level',
            'daily_caloric_intake',
            'subscription',
        ];
    }

    /**
     * The exposed relations that could be provided
     */
    public function relations(RestRequest $request): array
    {
        return [
            HasMany::make('healthMetrics', HealthMetricResource::class),
            BelongsToMany::make('foods', FoodResource::class),
            BelongsToMany::make('exercises', ExerciseResource::class),
            BelongsToMany::make('goals', GoalResource::class),
            BelongsToMany::make('constraints', ConstraintResource::class),
            BelongsToMany::make('subscriptions', SubscriptionResource::class),
            BelongsToMany::make('equipments', EquipmentResource::class),
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
     * @return array<string, array<int, string>>
     */
    public function rules(RestRequest $request): array
    {
        return [
            'last_name' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'email' => ['string', 'email', 'max:255'],
            'password' => ['string', 'min:6'],
            'profile_picture' => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'birthdate' => ['required', 'date'],
            'gender' => ['string', 'in:male,female,other'],
            'weight' => ['numeric', 'between:1,500'],
            'height' => ['integer', 'between:1,300'],
            'body_fat_pct' => ['integer', 'between:1,100'],
            'constraints' => ['array'],
            'physical_activity_level' => ['string'],
            'daily_caloric_intake' => ['integer'],
            'goal' => ['string', 'max:500'],
            'subscription' => ['string', 'max:50'],
            'date_subscription' => ['required', 'date'],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function createRules(RestRequest $request): array
    {
        return [
            'last_name' => ['required'],
            'first_name' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
            'profile_picture' => ['nullable'],
            'birthdate' => ['required'],
            'gender' => ['required'],
            'weight' => ['required'],
            'height' => ['required'],
            'body_fat_pct' => ['required'],
            'constraints' => ['required'],
            'physical_activity_level' => ['required'],
            'daily_caloric_intake' => ['required'],
            'goal' => ['required'],
            'subscription' => ['required'],
        ];
    }

    public function mutating(MutateRequest $request, array $requestBody, Model $model): void
    {
        $bmi = null;

        $attributes = $requestBody['attributes'] ?? [];

        if (! empty($attributes['weight']) && ! empty($attributes['height'])) {
            $heightInMeters = $attributes['height'] / 100;
            if ($heightInMeters > 0) {
                $bmi = $attributes['weight'] / ($heightInMeters * $heightInMeters);
            }
        }

        if (isset($requestBody['operation']) && $requestBody['operation'] === 'create' && $bmi !== null) {
            $model->bmi = $bmi;
        }
    }
}
