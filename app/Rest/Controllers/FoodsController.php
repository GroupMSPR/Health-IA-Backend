<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\FoodResource;
use Lomkit\Rest\Http\Resource;

class FoodsController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<\Lomkit\Rest\Http\Resource>
     */
    public static $resource = FoodResource::class;
}
