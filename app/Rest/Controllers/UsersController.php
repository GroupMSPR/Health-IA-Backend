<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\UserResource;
use Lomkit\Rest\Http\Resource;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/users/search',
    summary: 'Rechercher et filtrer des utilisateurs',
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
        'Users'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/users/mutate',
    summary: 'Créer ou mettre à jour un/des utilisateurs',
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
        'Users'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Delete(path: '/users/destroy',
    summary: 'Supprimer un/des utilisateurs',
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
        'Users'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/users/restore',
    summary: 'Restaurer un/des utilisateurs supprimés',
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
        'Users'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
class UsersController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<Resource>
     */
    public static $resource = UserResource::class;
}
