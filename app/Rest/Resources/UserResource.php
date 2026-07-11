<?php

namespace App\Rest\Resources;

use App\Enums\ActivityLevel;
use App\Enums\Gender;
use App\Models\User;
use App\Support\HealthCalculator;
use Carbon\Carbon;
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
            'id',
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
            'favorite_exercise_category',
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
            BelongsToMany::make('subscriptions', SubscriptionResource::class)
                ->withPivotFields(['started_at', 'ended_at']),
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
            'last_name' => ['string'],
            'first_name' => ['string'],
            'email' => ['string', 'email', 'max:255'],
            'password' => ['string', 'min:6'],
            'profile_picture' => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'birthdate' => ['date'],
            'gender' => ['string', 'in:Homme,Femme,Autres'],
            'weight' => ['numeric', 'between:1,500'],
            'height' => ['integer', 'between:1,300'],
            'body_fat_pct' => ['integer', 'between:1,100'],
            'physical_activity_level' => ['string'],
            'daily_caloric_intake' => ['integer'],
            'favorite_exercise_category' => ['string'],
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
            'physical_activity_level' => ['required'],
            'daily_caloric_intake' => ['required'],
            'favorite_exercise_category' => ['required'],
        ];
    }

    public function mutating(MutateRequest $request, array $requestBody, Model $model): void
    {
        if (($requestBody['operation'] ?? null) !== 'create') {
            return;
        }

        $attributes = $requestBody['attributes'] ?? [];

        $bmi = app(HealthCalculator::class)->bmi(
            isset($attributes['weight']) ? (float) $attributes['weight'] : null,
            isset($attributes['height']) ? (float) $attributes['height'] : null,
        );

        if ($bmi !== null) {
            $model->bmi = $bmi;
        }
    }

    // calculer l'apport journalier pour le metabolisme de base (BMR) et l'apport calorique total (TDEE)
    public function mutated(MutateRequest $request, array $requestBody, Model $model): void
    {
        if (($requestBody['operation'] ?? null) !== 'create') {
            return;
        }

        $attributes = $requestBody['attributes'] ?? [];

        $weight = isset($attributes['weight']) ? (float) $attributes['weight'] : null;
        $height = isset($attributes['height']) ? (float) $attributes['height'] : null;
        $age = isset($attributes['birthdate']) ? Carbon::parse($attributes['birthdate'])->age : null;
        $gender = Gender::fromLegacy($attributes['gender'] ?? null);
        $level = ActivityLevel::fromLegacy($attributes['physical_activity_level'] ?? null);

        $calc = app(HealthCalculator::class);
        $changed = false;

        $bmi = $calc->bmi($weight, $height);
        if ($bmi !== null) {
            $model->bmi = $bmi;
            $changed = true;
        }

        // Preserve the original semantics: the target intake is only computed
        // when the activity level is provided (all five inputs present).
        if ($level !== null) {
            $target = $calc->dailyCaloricTarget($gender, $level, $weight, $height, $age);
            if ($target !== null) {
                $model->daily_caloric_intake = $target;
                $changed = true;
            }
        }

        if ($changed) {
            $model->save();
        }
    }
}
