<?php

namespace MSPR2\SdkIA\Http;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\Request;
use MSPR2\SdkIA\Facade\IAManager;

class IAController extends Controller
{
    /**
     * analyse un repas a partir d'une image envoyé par l'utilisateur
     **/
    public function analyzeMeal(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
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

    public function recommend(Request $request)
    {
        $categories = ['Musculation', 'Cardio', 'Poids du corps'];

        $categoryMap = array_combine(array_map('strtolower', $categories), $categories);
        $rawCategory = $request->input('favorite_exercise_categorie');
        if ($rawCategory !== null) {
            $normalize = $categoryMap[strtolower($rawCategory ?? $rawCategory)] ?? $rawCategory;
            $request->merge(['favorite_exercise_categorie' => $normalize]);
        }

        $validated = $request->validate([
            'favorite_exercise_categorie' => 'sometimes|string|in:' . implode(',', $categories),
        ]);

        $user = $request->user();

        $userProfile = [
            'physical_activity_level' => $this->mapActivityLevel($user->physical_activity_level),
            'bmi' => (float)$user->bmi,
            'birthdate' => $user->birthdate,
            'favorite_exercise_categorie' => $validated['favorite_exercise_categorie'] ?? $user->favorite_exercise_categorie ?? 'Cardio'
        ];

        $result = IAManager::recommend($userProfile);

        if (empty($result['predictions'])) {
            return response()->json($result);
        }

        $filtered = collect($result['predictions'])
            ->filter(function ($prediction) use ($user) {
                $exercise = Exercise::where('name', $prediction['exercise'])->first();
                if (!$exercise) {
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
            'predictions' => $filtered
        ]);
    }

    private function mapActivityLevel(string $level): string
    {
        $level = strtolower($level);
        return match (true) {
            str_contains($level, 'sédentaire'),
            str_contains($level, 'sedentaire'),
            str_contains($level, 'sedentary') => 'sedentary',

            str_contains($level, 'actif'),
            str_contains($level, 'active') => 'active',
            default => 'moderate',
        };

    }
}
