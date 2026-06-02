<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\SubscriptionResource;
use Lomkit\Rest\Http\Resource;

class SubscriptionController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<\Lomkit\Rest\Http\Resource>
     */
    public static $resource = SubscriptionResource::class;
}
