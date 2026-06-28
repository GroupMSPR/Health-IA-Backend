<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\PostResource;
use Lomkit\Rest\Http\Resource;

class PostController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<\Lomkit\Rest\Http\Resource>
     */
    public static $resource = PostResource::class;
}
