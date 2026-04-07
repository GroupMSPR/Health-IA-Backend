<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\FoodResource;
use Lomkit\Rest\Http\Resource;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/foods/search',
    summary: 'Rechercher et filtrer des aliments',
    security: [
        ['sanctum' => []],
    ],
    requestBody: new OA\RequestBody(
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'search',
                        properties: [
                            new OA\Property(property: 'page', type: 'integer', example: 1),
                            new OA\Property(property: 'limit', type: 'integer', example: 10),
                        ],
                        type: 'object'
                    ),
                ],
                type: 'object'
            )
        )
    ),
    tags: [
        'Foods',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/foods/mutate',
    summary: 'Créer ou mettre à jour un/des aliments',
    security: [
        ['sanctum' => []],
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
                            properties: [
                                new OA\Property(property: 'operation', type: 'string', example: 'create or update'),
                                new OA\Property(
                                    property: 'attributes',
                                    properties: [
                                        new OA\Property(property: 'name', type: 'string', example: 'Poulet grillé'),
                                        new OA\Property(property: 'category', type: 'string', example: 'Viandes'),
                                        new OA\Property(property: 'calories', type: 'number', format: 'float', example: 165.0),
                                        new OA\Property(property: 'protein', type: 'number', format: 'float', example: 31.0),
                                        new OA\Property(property: 'carbohydrates', type: 'number', format: 'float', example: 0.0),
                                        new OA\Property(property: 'fat', type: 'number', format: 'float', example: 3.6),
                                        new OA\Property(property: 'fiber', type: 'number', format: 'float', example: 0.0),
                                        new OA\Property(property: 'sugars', type: 'number', format: 'float', example: 0.0),
                                        new OA\Property(property: 'sodium', type: 'integer', example: 74),
                                        new OA\Property(property: 'cholesterol', type: 'integer', example: 85),
                                    ],
                                    type: 'object'
                                ),
                                new OA\Property(property: 'relations', type: 'object', example: new \stdClass),
                            ],
                            type: 'object'
                        )
                    )],
                type: 'object'
            )
        )
    ),
    tags: [
        'Foods',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Delete(path: '/foods',
    summary: 'Supprimer un/des aliments',
    security: [
        ['sanctum' => []],
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
                            type: 'uuid',
                            example: '123e4567-e89b-12d3-a456-426614174000'
                        )
                    ),
                ],
                type: 'object'
            )
        )
    ),
    tags: [
        'Foods',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/foods/restore',
    summary: 'Restaurer un/des aliments supprimés',
    security: [
        ['sanctum' => []],
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
                            type: 'uuid',
                            example: '123e4567-e89b-12d3-a456-426614174000'
                        )
                    ),
                ],
                type: 'object'
            )
        )
    ),
    tags: [
        'Foods',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Delete(path: '/foods/force',
    summary: 'Supprimer de façon permanente un/des aliments',
    security: [
        ['sanctum' => []],
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
                            type: 'uuid',
                            example: '123e4567-e89b-12d3-a456-426614174000'
                        )
                    ),
                ],
                type: 'object'
            )
        )
    ),
    tags: [
        'Foods',
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
     * @var class-string<resource>
     */
    public static $resource = FoodResource::class;
}
