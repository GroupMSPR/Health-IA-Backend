<?php

namespace App\Rest\Resources;

use App\Models\Food;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;

class FoodResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = Food::class;

    /**
     * The exposed fields that could be provided
     */
    public function fields(RestRequest $request): array
    {
        return [
            'name',
            'category',
            'calories',
            'protein',
            'carbohydrates',
            'fat',
            'fiber',
            'sugars',
            'sodium',
            'cholesterol',
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
            'category' => ['string'],
            'calories' => ['decimal:8'],
            'protein' => ['decimal:8'],
            'carbohydrates' => ['decimal:8'],
            'fat' => ['decimal:8'],
            'fiber' => ['decimal:8'],
            'sugars' => ['decimal:8'],
            'sodium' => ['integer'],
            'cholesterol' => ['integer'],
        ];
    }

    /**
     * @return array[]
     */
    public function createRules(RestRequest $request): array
    {
        return [
            'name' => ['required'],
            'category' => ['required'],
            'calories' => ['required'],
            'protein' => ['required'],
            'carbohydrates' => ['required'],
            'fat' => ['required'],
            'fiber' => ['required'],
            'sugars' => ['required'],
            'sodium' => ['required'],
            'cholesterol' => ['required'],
        ];
    }
}
