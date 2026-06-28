<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\CommentResource;
use Lomkit\Rest\Http\Resource;

class CommentController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<\Lomkit\Rest\Http\Resource>
     */
    public static $resource = CommentResource::class;
}
