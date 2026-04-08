<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\ExerciseResource;
use Lomkit\Rest\Http\Resource;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/exercises/search',
    summary: 'Rechercher et filtrer des exercises',
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
        'Exercises',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Exercices trouvés !'),
    ]
)]
#[OA\Post(path: '/exercises/mutate',
    summary: 'Créer ou mettre à jour un/des exercises',
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
                                        new OA\Property(property: 'name', type: 'string', example: 'Abdos'),
                                        new OA\Property(property: 'type', type: 'string', example: 'Poids de Corps'),
                                        new OA\Property(property: 'difficulty_level', type: 'string', example: 'Moyen'),
                                        new OA\Property(property: 'target_muscle', type: 'string', example: 'Abdos'),
                                        new OA\Property(property: 'secondary_muscle', type: 'string', example: '...'),
                                        new OA\Property(property: 'equipment', type: 'string', example: 'Tapis'),
                                        new OA\Property(property: 'instructions', type: 'string', example: '3 Series de 8'),
                                        new OA\Property(property: 'constraints', type: 'array', items: new OA\Items(type: 'string'), example: ['Allergie au gluten', '...'])
                                    ],
                                    type: 'object'
                                ),
                                new OA\Property(property: 'relations', type: 'object', example: new \stdClass),
                            ],
                            type: 'object'
                        )
                    ),
                ],
                type: 'object'
            )
        )
    ),
    tags: [
        'Exercises',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Exercise crée avec succès !'),
    ]
)]
#[OA\Delete(path: '/exercises',
    summary: 'Supprimer un/des exercises',
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
        'Exercises',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/exercises/restore',
    summary: 'Restaurer un/des exercices supprimés',
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
        'Exercises',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Delete(path: '/exercises/force',
    summary: 'Supprimer de façon permanente un/des exercises',
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
        'Exercises',
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
