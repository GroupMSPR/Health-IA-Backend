<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class RegisterController extends Controller
{
    #[OA\Post(
        path: '/register',
        summary: 'Créer un nouveau compte utilisateur avec profil de santé',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    required: [
                        'last_name', 'first_name', 'email', 'password', 'password_confirmation',
                        'birthdate', 'gender', 'weight', 'height', 'body_fat_pct', 'disease_type',
                        'severity', 'physical_activity_level', 'daily_caloric_intake', 'goal',
                    ],
                    properties: [
                        new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                        new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
                        new OA\Property(property: 'password', description: 'Minimum 6 caractères', type: 'string', format: 'password', example: 'password123'),
                        new OA\Property(property: 'birthdate', type: 'date', example: '1999-11-28'),
                        new OA\Property(property: 'gender', type: 'string', enum: ['male', 'female', 'other'], example: 'male'),
                        new OA\Property(property: 'weight', description: 'Poids en kg (entre 1 et 500)', type: 'number', format: 'float', example: 75.5),
                        new OA\Property(property: 'height', description: 'Taille en cm (entre 1 et 300)', type: 'integer', example: 180),
                        new OA\Property(property: 'body_fat_pct', description: 'Pourcentage de masse grasse (entre 1 et 100)', type: 'integer', example: 18),
                        new OA\Property(property: 'disease_type', type: 'string', example: 'Diabète de type 2'),
                        new OA\Property(property: 'severity', type: 'string', example: 'Modérée'),
                        new OA\Property(property: 'physical_activity_level', type: 'string', example: 'Actif'),
                        new OA\Property(property: 'daily_caloric_intake', description: 'Apport calorique journalier ciblé', type: 'integer', example: 2200),
                        new OA\Property(property: 'goal', type: 'string', maxLength: 500, example: 'Perdre du poids tout en stabilisant la glycémie.'),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['Register'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                example: 'application/json'
            )
        ],
        responses: [
            new OA\Response(response: 201, description: 'Utilisateur créé avec succès'),
            new OA\Response(response: 422, description: 'Erreur de validation (ex: email déjà pris, mot de passe trop court, etc.)'),
        ]
    )]
    public function register(RegisterRequest $request): JsonResponse
    {
        $bmi = null;

        if (! empty($request->weight) && ! empty($request->height)) {
            $heightInMeters = $request->height / 100;
            if ($heightInMeters > 0) {
                $bmi = $request->weight / ($heightInMeters * $heightInMeters);
            }
        }

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'weight' => $request->weight,
            'height' => $request->height,
            'bmi' => $bmi,
            'body_fat_pct' => $request->body_fat_pct,
            'disease_type' => $request->disease_type,
            'severity' => $request->severity,
            'physical_activity_level' => $request->physical_activity_level,
            'daily_caloric_intake' => $request->daily_caloric_intake,
            'goal' => $request->goal,
            'subscription' => 'free',
            'date_subscription' => now(),
        ]);

        return response()->json([
            'message' => 'Utilisateur inscrit avec succès',
            'informations' => $user,
        ], 201);
    }
}
