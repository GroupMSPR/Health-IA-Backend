<?php

namespace MSPR2\SdkIA\Http;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MSPR2\SdkIA\Facade\IAManager;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/ai/analyze-meal',
    description: 'Envoie une image de repas au modèle LLaVA (via Ollama) qui identifie les aliments et calcule les apports nutritionnels (macros, calories, allergènes). Retourne un statut "degraded" si le service IA est indisponible.',
    summary: 'Analyser une photo de repas et obtenir les informations nutritionnelles',
    security: [['sanctum' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['image'],
                properties: [
                    new OA\Property(
                        property: 'image',
                        description: 'Photo du repas à analyser (jpeg, png, jpg)',
                        type: 'string',
                        format: 'binary'
                    ),
                ],
                type: 'object'
            )
        )
    ),
    tags: ['IA'],
    parameters: [
        new OA\Parameter(name: 'Accept', in: 'header', required: true, example: 'application/json'),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Succès ou mode dégradé',
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    oneOf: [
                        new OA\Schema(
                            title: 'Succès',
                            properties: [
                                new OA\Property(property: 'status', type: 'string', example: 'success'),
                                new OA\Property(property: 'is_working', type: 'integer', example: 1),
                                new OA\Property(
                                    property: 'data',
                                    properties: [
                                        new OA\Property(property: 'name', type: 'string', example: 'Grilled chicken breast'),
                                        new OA\Property(property: 'portion_size_g', type: 'integer', example: 200),
                                        new OA\Property(property: 'confidence', type: 'integer', example: 85),
                                        new OA\Property(property: 'cooking_method', type: 'string', example: 'grilled'),
                                        new OA\Property(property: 'meal_type', type: 'string', example: 'lunch'),
                                        new OA\Property(
                                            property: 'nutrition',
                                            properties: [
                                                new OA\Property(property: 'calories', type: 'number', example: 330),
                                                new OA\Property(property: 'protein', type: 'number', example: 62.0),
                                                new OA\Property(property: 'carbs', type: 'number', example: 0.0),
                                                new OA\Property(property: 'fat', type: 'number', example: 7.2),
                                            ],
                                            type: 'object'
                                        ),
                                    ],
                                    type: 'object'
                                ),
                            ],
                            type: 'object'
                        ),
                        new OA\Schema(
                            title: 'Mode dégradé',
                            properties: [
                                new OA\Property(property: 'status', type: 'string', example: 'degraded'),
                                new OA\Property(property: 'is_working', type: 'integer', example: 0),
                                new OA\Property(property: 'data', type: 'string', example: null, nullable: true),
                                new OA\Property(property: 'message', type: 'string', example: 'Analyse automatique impossible. Veuillez saisir les aliments manuellement.'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            )
        ),
        new OA\Response(response: 401, description: 'Non authentifié'),
        new OA\Response(response: 422, description: 'Image manquante ou format invalide'),
    ]
)]
#[OA\Post(
    path: '/ai/recommend',
    description: 'Interroge le modèle Random Forest pour générer des recommandations d\'exercices basées sur le profil de l\'utilisateur connecté (IMC, niveau d\'activité, âge, catégorie préférée). Les exercices sont filtrés selon les contraintes médicales et les objectifs du user avant renvoi des 5 meilleurs résultats.',
    summary: 'Obtenir des recommandations d\'exercices personnalisées par IA',
    security: [['sanctum' => []]],
    requestBody: new OA\RequestBody(
        required: false,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'favorite_exercise_categorie',
                        description: 'Catégorie d\'exercice souhaitée pour cette session. Si absent, utilise la valeur du profil utilisateur. Insensible à la casse.',
                        type: 'string',
                        enum: ['Musculation', 'Cardio', 'Poids du corps'],
                        example: 'Cardio'
                    ),
                ],
                type: 'object'
            )
        )
    ),
    tags: ['IA'],
    parameters: [
        new OA\Parameter(name: 'Accept', in: 'header', required: true, example: 'application/json'),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Succès ou mode dégradé',
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    oneOf: [
                        new OA\Schema(
                            title: 'Recommandations générées',
                            properties: [
                                new OA\Property(property: 'status', type: 'string', example: 'success'),
                                new OA\Property(property: 'is_working', type: 'integer', example: 1),
                                new OA\Property(
                                    property: 'predictions',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'exercise', type: 'string', example: 'Course en Côte'),
                                            new OA\Property(property: 'confidence', type: 'number', format: 'float', example: 0.153),
                                        ],
                                        type: 'object'
                                    ),
                                    maxItems: 5
                                ),
                            ],
                            type: 'object'
                        ),
                        new OA\Schema(
                            title: 'Mode dégradé',
                            properties: [
                                new OA\Property(property: 'status', type: 'string', example: 'degraded'),
                                new OA\Property(property: 'is_working', type: 'integer', example: 0),
                                new OA\Property(property: 'predictions', type: 'array', items: new OA\Items, example: []),
                                new OA\Property(property: 'message', type: 'string', example: 'Service de recommandation indisponible'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            )
        ),
        new OA\Response(response: 401, description: 'Non authentifié'),
        new OA\Response(response: 422, description: 'Catégorie d\'exercice invalide'),
    ]
)]
class IAController extends Controller
{
    /**
     * analyse un repas a partir d'une image envoyé par l'utilisateur
     **/
    public function analyzeMeal(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        $file = $request->file('image');

        $originalName = $file->getClientOriginalName();

        $imageBase64 = base64_encode(
            file_get_contents($request->file('image')->getPathname())
        );

        $result = IAManager::analyzeMeal($imageBase64, $originalName);

        return response()->json($result);
    }

    /**
     * donne une recommendation d'exercice a partir du profil utilisateur
     **/
    public function recommend(Request $request): JsonResponse
    {
        $categories = ['Musculation', 'Cardio', 'Poids du corps'];

        $categoryMap = array_combine(array_map('strtolower', $categories), $categories);
        $rawCategory = $request->input('favorite_exercise_category');
        if ($rawCategory !== null) {
            $normalize = $categoryMap[strtolower($rawCategory ?? $rawCategory)] ?? $rawCategory;
            $request->merge(['favorite_exercise_category' => $normalize]);
        }

        $validated = $request->validate([
            'favorite_exercise_category' => 'sometimes|string|in:'.implode(',', $categories),
        ]);

        $user = $request->user();

        $userProfile = [
            // physical_activity_level is a backed enum on the User model (canonical
            // EN value); fall back to the previous default when it is not set.
            'physical_activity_level' => $user->physical_activity_level?->value ?? 'moderate',
            'bmi' => (float) $user->bmi,
            'birthdate' => $user->birthdate,
            'favorite_exercise_category' => $validated['favorite_exercise_category'] ?? $user->favorite_exercise_category?->value ?? 'Cardio',
        ];

        $result = IAManager::recommend($userProfile);

        if (empty($result['predictions'])) {
            return response()->json($result);
        }

        $filtered = collect($result['predictions'])
            ->filter(function ($prediction) use ($user) {
                $exercise = Exercise::where('name', $prediction['exercise'])->first();
                if (! $exercise) {
                    return true;
                }
                try {
                    return IAManager::isLegal($exercise, $user);
                } catch (\Throwable $e) {
                    return true;
                }
            })
            ->take(5)
            ->values();

        return response()->json([
            'status' => 'success',
            'is_working' => $result['is_working'] ?? 1,
            'predictions' => $filtered,
        ]);
    }
}
