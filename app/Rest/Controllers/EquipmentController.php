<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\ExerciseResource;
use Lomkit\Rest\Http\Resource;

class EquipmentController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<\Lomkit\Rest\Http\Resource>
     */
    public static $resource = ExerciseResource::class;
}
