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
#[OA\Delete(path: '/health-metrics/destroy',
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
                            type: 'integer'
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
                            type: 'integer'
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
