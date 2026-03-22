<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/login',
    summary: 'Se connecter et récupérer un token Sanctum',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123')
                ],
                type: 'object'
            )
        )
    ),
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'Authentification réussie, token retourné'),
        new OA\Response(response: 401, description: 'Identifiants invalides')
    ]
)]
#[OA\Post(
    path: '/logout',
    summary: 'Se déconnecter (Révocation du token actuel)',
    security: [['sanctum' => []]],
    tags: ['Auth'], // Protégé par Sanctum
    responses: [
        new OA\Response(response: 200, description: 'Déconnexion réussie'),
        new OA\Response(response: 401, description: 'Non authentifié')
    ]
)]
class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $token = $user?->currentAccessToken();

        if ($token) {
            $token->delete();
        } elseif ($user) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }
}
