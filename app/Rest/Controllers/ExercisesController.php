<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\ExerciseResource;
use Lomkit\Rest\Http\Resource;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/exercises/search',
    summary: 'Rechercher et filtrer des exercises',
    security: [
        ['sanctum' => []]
    ],
    requestBody: new OA\RequestBody(
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object'
            )
        )
    ),
    tags: [
        'Exercises'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/exercises/mutate',
    summary: 'Créer ou mettre à jour un/des exercises',
    security: [
        ['sanctum' => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'mutate',
                        type: 'array',
                        items: new OA\Items(
                            type: 'object'
                        )
                    )],
                type: 'object'
            )
        )
    ),
    tags: [
        'Exercises'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Delete(path: '/exercises/destroy',
    summary: 'Supprimer un/des exercises',
    security: [
        ['sanctum' => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'resources',
                        type: 'array',
                        items: new OA\Items(
                            type: 'integer'
                        )
                    )
                ],
                type: 'object'
            )
        )
    ),
    tags: [
        'Exercises'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/exercises/restore',
    summary: 'Restaurer un/des exercices supprimés',
    security: [
        ['sanctum' => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'resources',
                        type: 'array',
                        items: new OA\Items(
                            type: 'integer'
                        )
                    )
                ],
                type: 'object'
            )
        )
    ),
    tags: [
        'Exercises'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
class ExercisesController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<resource>
     */
    public static $resource = ExerciseResource::class;
}
