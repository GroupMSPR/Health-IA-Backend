<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\MuscleResource;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/muscles/mutate',
    summary: 'Créer ou mettre à jour un/des muscles',
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
                                        new OA\Property(property: 'name', type: 'string', example: 'Quadriceps'),
                                        // ← "group" RETIRÉ, Muscle n'a que "name"
                                    ],
                                    type: 'object'
                                ),
                                new OA\Property(
                                    property: 'relations',
                                    properties: [
                                        new OA\Property(
                                            property: 'primaryExercises',
                                            type: 'array',
                                            items: new OA\Items(
                                                properties: [
                                                    new OA\Property(property: 'operation', type: 'string', example: 'attach'),
                                                    new OA\Property(property: 'key', type: 'string', example: '019ec79a-5447-734f-a786-543a6e0a5a98'),
                                                ],
                                                type: 'object'
                                            )
                                        ),
                                        new OA\Property(
                                            property: 'secondaryExercises',
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
    tags: ['Muscles'],
    responses: [new OA\Response(response: 200, description: 'Succès')]
)]
class MuscleController extends Controller
{
    public static $resource = MuscleResource::class;
}
