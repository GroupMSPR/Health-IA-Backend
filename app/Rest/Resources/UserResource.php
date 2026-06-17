<?php

namespace App\Rest\Resources;

use App\Models\User;
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
            'favorite_exercise_category'
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
            'last_name' => ['register', 'string'],
            'first_name' => ['register', 'string'],
            'email' => ['string', 'email', 'max:255'],
            'password' => ['string', 'min:6'],
            'profile_picture' => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'birthdate' => ['required', 'date'],
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
        ];
    }

    public function mutating(MutateRequest $request, array $requestBody, Model $model): void
    {
        $bmi = null;

        $attributes = $requestBody['attributes'] ?? [];

        if (!empty($attributes['weight']) && !empty($attributes['height'])) {
            $heightInMeters = $attributes['height'] / 100;
            if ($heightInMeters > 0) {
                $bmi = $attributes['weight'] / ($heightInMeters * $heightInMeters);
            }
        }

        if (isset($requestBody['operation']) && $requestBody['operation'] === 'create' && $bmi !== null) {
            $model->bmi = $bmi;
        }
    }

    //calculer l'apport journalier pour le metabolisme de base (BMR) et l'apport calorique total (TDEE)
    public function mutated(MutateRequest $request, array $requestBody, Model $model): void
    {
        if (($requestBody['operation'] ?? null) !== 'create') {
            return;
        }

        $attributes = $requestBody['attributes'] ?? [];
        $weight = $attributes['weight'] ?? null;
        $height = $attributes['height'] ?? null;
        $birthdate = $attributes['birthdate'] ?? null;
        $gender = $attributes['gender'] ?? null;
        $activityLevel = $attributes['physical_activity_level'] ?? null;

        $changed = false;

        if ($weight && $height) {
            $heightInMeters = $weight / 100;
            if ($heightInMeters > 0) {
                $model->bmi = round($weight / ($heightInMeters ** 2), 2);
                $changed = true;
            }
            if ($weight && $height && $birthdate && $gender && $activityLevel) {
                $age = Carbon::parse($birthdate)->age;

                $bmr = match (strtolower($gender)) {
                    'homme', 'male' => 88.36 + (13.4 * $weight) + (4.8 * $height) - (5.7 * $age),
                    'femme', 'female' => 447.6 + (9.2 * $weight) + (3.1 * $height) - (4.3 * $age),
                    default => null
                };

                if ($bmr !== null) {
                    $tdee = match (strtolower($activityLevel)) {
                        'sedentary' => $bmr * 1.2,
                        'moderate' => $bmr * 1.55,
                        'active' => $bmr * 1.725,
                        default => $bmr * 1.2
                    };
                    $model->daily_caloric_intake = round($tdee - 400);
                    $changed = true;
                }
            }

            if($changed) {
                $model->save();
            }
        }
    }
}
