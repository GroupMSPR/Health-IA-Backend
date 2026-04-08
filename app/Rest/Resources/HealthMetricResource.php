<?php

namespace App\Rest\Resources;

use App\Models\HealthMetric;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\MutateRequest;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsTo;

class HealthMetricResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = HealthMetric::class;

    /**
     * The exposed fields that could be provided
     */
    public function fields(RestRequest $request): array
    {
        return [
            'date',
            'start_weight',
            'current_weight',
            'avg_bpm',
            'max_bpm',
            'resting_bpm',
            'steps_count',
            'sleep_time',
            'calories_burned',
            'active_minute',
            'workout_type',
        ];
    }

    /**
     * The exposed relations that could be provided
     */
    public function relations(RestRequest $request): array
    {
        return [
            BelongsTo::make('user', User::class),
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
            'start_weight' => ['numeric', 'min:0'],
            'current_weight' => ['numeric', 'min:0'],
            'resting_bpm' => ['integer', 'min:0', 'max:250'],
            'avg_bpm' => ['integer', 'min:0', 'max:250'],
            'max_bpm' => ['integer', 'min:0', 'max:250'],
            'steps_count' => ['integer', 'min:0'],
            'active_minute' => ['integer', 'min:0', 'max:1440'],
            'sleep_time' => ['date_format:H:i:s'],
            'calories_burned' => ['numeric', 'min:0'],
            'workout_type' => ['string', 'in:none,walk,run,cycling,hiit,strength,swim,yoga'],
        ];
    }

    /**
     * @return array[]
     */
    public function createRules(RestRequest $request): array
    {
        return [
            'start_weight' => ['required'],
            'current_weight' => ['required'],
            'avg_bpm' => ['required'],
            'max_bpm' => ['required'],
            'resting_bpm' => ['required'],
            'steps_count' => ['required'],
            'sleep_time' => ['required'],
            'calories_burned' => ['required'],
            'active_minute' => ['required'],
            'workout_type' => ['required'],
        ];
    }

    public function mutating(MutateRequest $request, array $requestBody, Model $model): void
    {
        $user = auth()->user();

        if ($requestBody['operation'] === 'create') {
            $model->user_id = $user->id;
            $model->date = now();
        }
    }
}
