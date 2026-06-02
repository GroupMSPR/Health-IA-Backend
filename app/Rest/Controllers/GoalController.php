<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\GoalResource;
use Lomkit\Rest\Http\Resource;

class GoalController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<\Lomkit\Rest\Http\Resource>
     */
    public static $resource = GoalResource::class;
}
