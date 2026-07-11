<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use OpenApi\Attributes as OA;

class PasswordResetController extends Controller
{
    #[OA\Post(
        path: '/forgot-password',
        summary: 'Envoie un lien de réinitialisation par email',
        tags: ['Auth'],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
        ]
    )]
    public function sendResetLinkEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        Password::sendResetLink($request->only('email'));

        return response()->json([
            'message' => 'Si un compte correspond à cette adresse, un lien de réinitialisation a été envoyé.',
        ]);
    }

    #[OA\Post(
        path: '/reset-password',
        summary: 'Modifie le mot de passe avec le token',
        tags: ['Auth'],
        responses: [
            new OA\Response(response: 200, description: 'Succès'),
        ]

    )]
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();

                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json(['message' => 'Votre mot de passe a été réinitialisé avec succès.']);
        }

        return response()->json(['message' => 'Le token est invalide ou a expiré.'], 400);
    }
}
