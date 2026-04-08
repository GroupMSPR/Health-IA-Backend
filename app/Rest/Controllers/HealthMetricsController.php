<?php

namespace App\Rest\Controllers;

use App\Rest\Resources\HealthMetricResource;
use Lomkit\Rest\Http\Resource;
use OpenApi\Attributes as OA;

#[OA\Post(path: '/health-metrics/search',
    summary: 'Rechercher et filtrer des métriques de santé',
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
        'Health Metrics',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/health-metrics/mutate',
    summary: 'Créer ou mettre à jour une/des métriques de santé',
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
                                        new OA\Property(property: 'start_weight', type: 'number', format: 'float', example: 80.0),
                                        new OA\Property(property: 'current_weight', type: 'number', format: 'float', example: 78.5),
                                        new OA\Property(property: 'avg_bpm', type: 'number', format: 'float', example: 72.5),
                                        new OA\Property(property: 'max_bpm', type: 'number', format: 'float', example: 145.0),
                                        new OA\Property(property: 'resting_bpm', type: 'number', format: 'float', example: 60.0),
                                        new OA\Property(property: 'steps_count', type: 'integer', example: 10500),
                                        new OA\Property(property: 'sleep_time', type: 'string', format: 'time', example: '07:30:00'),
                                        new OA\Property(property: 'calories_burned', type: 'number', format: 'float', example: 450.5),
                                        new OA\Property(property: 'active_minute', type: 'integer', example: 45),
                                        new OA\Property(property: 'workout_type', type: 'string', example: 'Course à pied'),
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
        'Health Metrics',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Delete(path: '/health-metrics',
    summary: 'Supprimer une/des métriques de santé',
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
        'Health Metrics',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Post(path: '/health-metrics/restore',
    summary: 'Restaurer une/des métriques de santé supprimées',
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
        'Health Metrics',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
#[OA\Delete(path: '/health-metrics/force',
    summary: 'Supprimer de façon permanente une/des métriques de santé',
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
        'Health Metrics',
    ],
    responses: [
        new OA\Response(response: 200, description: 'Succès'),
    ]
)]
class HealthMetricsController extends Controller
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<resource>
     */
    public static $resource = HealthMetricResource::class;
}
