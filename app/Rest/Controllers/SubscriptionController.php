<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\SubscriptionResource;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/subscriptions/search',
    summary: 'Rechercher et filtrer des abonnements',
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
    tags: ['Subscriptions'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Post(
    path: '/subscriptions/mutate',
    summary: 'Créer ou mettre à jour un/des abonnements',
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
                                new OA\Property(
                                    property: 'operation',
                                    type: 'string',
                                    enum: ['create', 'update'],
                                    example: 'create'
                                ),
                                new OA\Property(
                                    property: 'attributes',
                                    properties: [
                                        new OA\Property(
                                            property: 'subscription_type',
                                            description: 'Type d\'abonnement (free, premium, premium_plus)',
                                            type: 'string',
                                            example: 'premium'
                                        ),
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
                                                    new OA\Property(
                                                        property: 'key',
                                                        description: 'UUID de l\'utilisateur à attacher',
                                                        type: 'string',
                                                        example: '019ecfb4-5dc7-7352-a963-0661b21386a6'
                                                    ),
                                                    new OA\Property(
                                                        property: 'pivot',
                                                        properties: [
                                                            new OA\Property(property: 'started_at', type: 'string', format: 'date', example: '2026-06-16'),
                                                            new OA\Property(property: 'ended_at', type: 'string', format: 'date', example: '2026-07-16'),
                                                        ],
                                                        type: 'object'
                                                    ),
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
    tags: ['Subscriptions'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Delete(
    path: '/subscriptions',
    summary: 'Supprimer un/des abonnements (soft delete)',
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
    tags: ['Subscriptions'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Post(
    path: '/subscriptions/restore',
    summary: 'Restaurer un/des abonnements supprimés',
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
    tags: ['Subscriptions'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Delete(
    path: '/subscriptions/force',
    summary: 'Supprimer définitivement un/des abonnements',
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
    tags: ['Subscriptions'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
class SubscriptionController extends Controller
{
    public static $resource = SubscriptionResource::class;
}
