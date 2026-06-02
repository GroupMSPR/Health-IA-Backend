<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\MuscleResource;
use Lomkit\Rest\Http\Resource;

class MuscleController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<\Lomkit\Rest\Http\Resource>
     */
    public static $resource = MuscleResource::class;
}
