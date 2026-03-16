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
            if ($heightInMeters > 0) {
                $bmi = $request->weight / ($heightInMeters * $heightInMeters);
            }
        }

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'age' => $request->age,
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
        ]);

        return response()->json([
            'message' => 'Utilisateur inscrit avec succès',
            'informations' => $user,
        ], 201);
    }
}
