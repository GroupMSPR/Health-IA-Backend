<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\FoodResource;
use Lomkit\Rest\Http\Resource;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/foods/search',
    summary: 'Rechercher et filtrer des aliments',
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
        'Foods'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/foods/mutate',
    summary: 'Créer ou mettre à jour un/des aliments',
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
        'Foods'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Delete(path: '/foods/destroy',
    summary: 'Supprimer un/des aliments',
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
        'Foods'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/foods/restore',
    summary: 'Restaurer un/des aliments supprimés',
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
        'Foods'
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
class FoodsController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<Resource>
     */
    public static $resource = FoodResource::class;
}
