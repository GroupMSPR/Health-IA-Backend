<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\ConstraintResource;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/constraints/search',
    summary: 'Rechercher et filtrer des contraintes médicales',
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
    tags: ['Constraints'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Post(
    path: '/constraints/mutate',
    summary: 'Créer ou mettre à jour une/des contraintes médicales',
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
                                        new OA\Property(property: 'name', type: 'string', example: 'Blessure genou'),
                                        new OA\Property(property: 'description', type: 'string', example: 'Douleur au genou limitant les exercices en flexion'),
                                        new OA\Property(property: 'severity', type: 'string', enum: ['low', 'medium', 'high'], example: 'high'),
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
    tags: ['Constraints'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Delete(
    path: '/constraints',
    summary: 'Supprimer une/des contraintes médicales',
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
    tags: ['Constraints'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Post(
    path: '/constraints/restore',
    summary: 'Restaurer une/des contraintes supprimées',
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
    tags: ['Constraints'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Delete(
    path: '/constraints/force',
    summary: 'Supprimer définitivement une/des contraintes',
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
    tags: ['Constraints'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
class ConstraintController extends Controller
{
    public static $resource = ConstraintResource::class;
}
