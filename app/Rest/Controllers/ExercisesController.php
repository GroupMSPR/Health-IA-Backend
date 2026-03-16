<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\ExerciseResource;
use Lomkit\Rest\Http\Resource;

class ExercisesController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<resource>
     */
    public static $resource = ExerciseResource::class;
}
