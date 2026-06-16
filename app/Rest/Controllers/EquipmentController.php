<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\EquipmentResource;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/equipments/search',
    summary: 'Rechercher et filtrer des équipements sportifs',
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
    tags: ['Equipments'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Post(
    path: '/equipments/mutate',
    summary: 'Créer ou mettre à jour un/des équipements',
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
                                        new OA\Property(property: 'name', type: 'string', example: 'Haltères'),
                                        new OA\Property(property: 'description', type: 'string', example: 'Paire d\'haltères réglables'),
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
    tags: ['Equipments'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
#[OA\Delete(
    path: '/equipments',
    summary: 'Supprimer un/des équipements',
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
    tags: ['Equipments'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
class EquipmentController extends Controller
{
    public static $resource = EquipmentResource::class;
}
