<?php

namespace App\Http\Controllers;

use App\Enums\AiPredictionStatus;
use App\Enums\AiPredictionType;
use App\Enums\ExerciseCategory;
use App\Jobs\AnalyzeMealJob;
use App\Jobs\RecommendJob;
use App\Models\AiPrediction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Asynchronous AI endpoints: the request creates a prediction record and
 * dispatches a job, then returns 202 with the record. Clients poll show().
 */
class AiController extends Controller
{
    public function analyzeMeal(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        $file = $request->file('image');
        $path = $file->store('ai/meals/'.$request->user()->getKey(), 'public');

        $prediction = AiPrediction::create([
            'user_id' => $request->user()->getKey(),
            'type' => AiPredictionType::AnalyzeMeal,
            'status' => AiPredictionStatus::Pending,
            'input' => [
                'image_path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ],
        ]);

        AnalyzeMealJob::dispatch($prediction->getKey());

        return response()->json($this->present($prediction), 202);
    }

    public function recommend(Request $request): JsonResponse
    {
        $user = $request->user();

        $category = ExerciseCategory::fromLegacy($request->input('favorite_exercise_category'))
            ?? $user->favorite_exercise_category;

        $prediction = AiPrediction::create([
            'user_id' => $user->getKey(),
            'type' => AiPredictionType::Recommend,
            'status' => AiPredictionStatus::Pending,
            'input' => [
                'physical_activity_level' => $user->physical_activity_level->value,
                'bmi' => (float) $user->bmi,
                'birthdate' => $user->birthdate,
                'favorite_exercise_category' => $category->value,
            ],
        ]);

        RecommendJob::dispatch($prediction->getKey());

        return response()->json($this->present($prediction), 202);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $prediction = AiPrediction::query()
            ->where('id', $id)
            ->where('user_id', $request->user()->getKey())
            ->firstOrFail();

        return response()->json($this->present($prediction));
    }

    /**
     * @return array<string, mixed>
     */
    private function present(AiPrediction $prediction): array
    {
        return [
            'id' => $prediction->id,
            'type' => $prediction->type->value,
            'status' => $prediction->status->value,
            'result' => $prediction->result,
            'error' => $prediction->error,
            'created_at' => $prediction->created_at,
            'updated_at' => $prediction->updated_at,
        ];
    }
}
