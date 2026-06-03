<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use OpenApi\Attributes as OA;

class PasswordResetController extends Controller
{
    #[OA\Post(
        path: '/forgot-password',
        summary: 'Envoie un lien de réinitialisation par email',
        tags: ['Auth']
    )]
    public function sendResetLinkEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Nous vous avons envoyé un lien de réinitialisation par email.']);
        }

        return response()->json(['message' => 'Impossible d\'envoyer le lien à cette adresse.'], 400);
    }

    #[OA\Post(
        path: '/reset-password',
        summary: 'Modifie le mot de passe avec le token',
        tags: ['Auth']
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
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Votre mot de passe a été réinitialisé avec succès.']);
        }

        return response()->json(['message' => 'Le token est invalide ou a expiré.'], 400);
    }
}
