<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\ConstraintResource;
use Lomkit\Rest\Http\Resource;

class ConstraintController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<\Lomkit\Rest\Http\Resource>
     */
    public static $resource = ConstraintResource::class;
}
