<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\GoalResource;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/goals/search',
    summary: 'Rechercher et filtrer des objectifs santé',
    security: [['sanctum' => []]],
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
    tags: ['Goals'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Post(
    path: '/goals/mutate',
    summary: 'Créer ou mettre à jour un/des objectifs santé',
    security: [['sanctum' => []]],
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
                                        new OA\Property(property: 'goal', type: 'string', example: 'Perte de poids'),
                                    ],
                                    type: 'object'
                                ),
                                new OA\Property(
                                    property: 'relations',
                                    properties: [
                                        new OA\Property(
                                            property: 'users',
                                            type: 'array',
                                            items: new OA\Items(
                                                properties: [
                                                    new OA\Property(property: 'operation', type: 'string', example: 'attach'),
                                                    new OA\Property(property: 'key', type: 'string', example: '019ec79a-5447-734f-a786-543a6e0a5a98'),
                                                ],
                                                type: 'object'
                                            )
                                        ),
                                    ],
                                    type: 'object'
                                ),
                            ],
                            type: 'object'
                        )
                    ),
                ],
                type: 'object'
            )
        )
    ),
    tags: ['Goals'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Delete(
    path: '/goals',
    summary: 'Supprimer un/des objectifs santé',
    security: [['sanctum' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'resources',
                        type: 'array',
                        items: new OA\Items(type: 'string', example: '123e4567-e89b-12d3-a456-426614174000')
                    ),
                ],
                type: 'object'
            )
        )
    ),
    tags: ['Goals'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Post(
    path: '/goals/restore',
    summary: 'Restaurer un/des objectifs supprimés',
    security: [['sanctum' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'resources',
                        type: 'array',
                        items: new OA\Items(type: 'string', example: '123e4567-e89b-12d3-a456-426614174000')
                    ),
                ],
                type: 'object'
            )
        )
    ),
    tags: ['Goals'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Delete(
    path: '/goals/force',
    summary: 'Supprimer définitivement un/des objectifs',
    security: [['sanctum' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'resources',
                        type: 'array',
                        items: new OA\Items(type: 'string', example: '123e4567-e89b-12d3-a456-426614174000')
                    ),
                ],
                type: 'object'
            )
        )
    ),
    tags: ['Goals'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
class GoalController extends Controller
{
    public static $resource = GoalResource::class;
}
