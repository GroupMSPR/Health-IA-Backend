<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\HealthMetricResource;
use Lomkit\Rest\Http\Resource;

class HealthMetricsController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<\Lomkit\Rest\Http\Resource>
     */
    public static $resource = HealthMetricResource::class;
}
