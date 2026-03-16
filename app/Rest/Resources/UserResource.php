<?php

namespace App\Rest\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\MutateRequest;
use Lomkit\Rest\Http\Requests\RestRequest;

class UserResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    public static $model = User::class;

    /**
     * The exposed fields that could be provided
     */
    public function fields(\Lomkit\Rest\Http\Requests\RestRequest $request): array
    {
        return [
            'email',
            'password',
            'age',
            'gender',
            'weight',
            'height',
            'body_fat_pct',
            'disease_type',
            'severity',
            'physical_activity_level',
            'daily_caloric_intake',
            'goal',
            'subscription',
        ];
    }

    /**
     * The exposed relations that could be provided
     */
    public function relations(\Lomkit\Rest\Http\Requests\RestRequest $request): array
    {
        return [];
    }

    /**
     * The exposed scopes that could be provided
     */
    public function scopes(\Lomkit\Rest\Http\Requests\RestRequest $request): array
    {
        return [];
    }

    /**
     * The exposed limits that could be provided
     */
    public function limits(\Lomkit\Rest\Http\Requests\RestRequest $request): array
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
    public function actions(\Lomkit\Rest\Http\Requests\RestRequest $request): array
    {
        return [];
    }

    /**
     * The instructions that should be linked
     */
    public function instructions(\Lomkit\Rest\Http\Requests\RestRequest $request): array
    {
        return [];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(RestRequest $request): array
    {
        return [
            'email' => ['string', 'email', 'max:255'],
            'password' => ['string', 'min:6'],
            'age' => ['integer', 'between:1,130'],
            'gender' => ['string', 'in:male,female,other'],
            'weight' => ['numeric', 'between:1,500'],
            'height' => ['integer', 'between:1,300'],
            'body_fat_pct' => ['integer', 'between:1,100'],
            'disease_type' => ['string'],
            'severity' => ['string'],
            'physical_activity_level' => ['string'],
            'daily_caloric_intake' => ['integer'],
            'goal' => ['string', 'max:500'],
            'subscription' => ['string', 'max:50'],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function createRules(RestRequest $request): array
    {
        return [
            'email' => ['required'],
            'password' => ['required'],
            'age' => ['required'],
            'gender' => ['required'],
            'weight' => ['required'],
            'height' => ['required'],
            'body_fat_pct' => ['required'],
            'disease_type' => ['required'],
            'severity' => ['required'],
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
