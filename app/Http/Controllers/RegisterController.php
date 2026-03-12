<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $bmi = null;

        if (! empty($request->weight) && ! empty($request->height)) {
            $heightInMeters = $request->height / 100;
            $bmi = $request->weight / ($heightInMeters * $heightInMeters);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'age' => $request->age,
            'gender' => $request->gender,
            'weight' => $request->weight,
            'height' => $request->height,
            'bmi' => $bmi,
            'body_fat_pct' => $request->body_fat_pct,
            'goal' => $request->goal,
            'subscription' => 'free',
        ]);

        return response()->json([
            'message' => 'Utilisateur inscrit avec succès',
            'informations' => $user,
        ], 201);
    }
}
