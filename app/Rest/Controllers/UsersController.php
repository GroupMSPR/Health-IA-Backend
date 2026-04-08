<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\UserResource;
use Lomkit\Rest\Http\Resource;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/users/search',
    summary: 'Rechercher et filtrer des utilisateurs',
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
        'Users',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/users/mutate',
    summary: 'Créer ou mettre à jour un/des utilisateurs',
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
                                        new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                                        new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                                        new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
                                        new OA\Property(property: 'password', type: 'string', example: 'password123'),
                                        new OA\Property(property: 'birthdate', type: 'string', format: 'date', example: '1990-05-15'),
                                        new OA\Property(property: 'gender', type: 'string', example: 'male'),
                                        new OA\Property(property: 'weight', type: 'number', format: 'float', example: 75.5),
                                        new OA\Property(property: 'height', type: 'integer', example: 180),
                                        new OA\Property(property: 'bmi', type: 'number', format: 'float', example: 23.3),
                                        new OA\Property(property: 'body_fat_pct', type: 'number', format: 'float', example: 15.2),
                                        new OA\Property(property: 'constraints', type: 'array', items: new OA\Items(type: 'string'), example: ['Allergie au gluten', '...']),
                                        new OA\Property(property: 'physical_activity_level', type: 'string', example: 'Actif'),
                                        new OA\Property(property: 'daily_caloric_intake', type: 'integer', example: 2500),
                                        new OA\Property(property: 'goal', type: 'string', example: 'Perte de poids'),
                                        new OA\Property(property: 'subscription', type: 'string', example: 'Premium'),
                                        new OA\Property(property: 'date_subscription', type: 'string', format: 'date-time', example: '2026-04-01T10:00:00Z'),
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
        'Users',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Delete(path: '/users',
    summary: 'Supprimer un/des utilisateurs',
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
        'Users',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/users/restore',
    summary: 'Restaurer un/des utilisateurs supprimés',
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
        'Users',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Delete(path: '/users/force',
    summary: 'Supprimer de façon permanente un/des utilisateurs',
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
        'Users',
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
     * @var class-string<resource>
     */
    public static $resource = UserResource::class;
}
