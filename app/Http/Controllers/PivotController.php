<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PivotController extends Controller
{
    #[OA\Post(
        path: '/consume',
        summary: 'Associer un aliment à l\'utilisateur connecté',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    required: ['food_id'],
                    properties: [
                        new OA\Property(
                            property: 'food_id',
                            type: 'string',
                            format: 'uuid',
                            example: '019d6d18-4173-722d-bcf0-d1c0ce8dcb35'
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['Pivot'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                example: 'application/json'
            ),
        ],
        responses: [
            new OA\Response(response: 201, description: 'Aliment associé avec succès'),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 422, description: 'Données invalides'),
        ]
    )]
    public function consumeFood(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'food_id' => ['required', 'uuid', 'exists:foods,id'],
        ]);

        $user = $request->user();
        $user->foods()->attach($validated['food_id']);

        return response()->json([
            'message' => 'Aliment ajouté',
        ], 201);
    }

    #[OA\Post(
        path: '/practice',
        summary: 'Associer un exercice à l\'utilisateur connecté',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    required: ['exercise_id'],
                    properties: [
                        new OA\Property(
                            property: 'exercise_id',
                            type: 'string',
                            format: 'uuid',
                            example: '019d6d18-41a8-73e6-a32c-0e1cc18e83cf'
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['Pivot'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                example: 'application/json'
            ),
        ],
        responses: [
            new OA\Response(response: 201, description: 'Exercice associé avec succès'),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 422, description: 'Données invalides'),
        ]
    )]
    public function practiceExercise(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'exercise_id' => ['required', 'uuid', 'exists:exercises,id'],
        ]);

        $user = $request->user();
        $user->exercises()->attach($validated['exercise_id']);

        return response()->json([
            'message' => 'Exercice ajouté',
        ], 201);
    }
}
