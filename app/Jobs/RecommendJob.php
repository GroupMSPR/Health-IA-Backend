<?php

namespace App\Jobs;

use App\Enums\AiPredictionStatus;
use App\Models\AiPrediction;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MSPR2\SdkIA\Facade\IAManager;
use Throwable;

class RecommendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(public string $predictionId) {}

    public function handle(): void
    {
        $prediction = AiPrediction::find($this->predictionId);

        if ($prediction === null) {
            return;
        }

        $prediction->update(['status' => AiPredictionStatus::Processing]);

        $result = IAManager::recommend($prediction->input ?? []);

        $predictions = is_array($result['predictions'] ?? null) ? $result['predictions'] : [];

        if ($predictions !== []) {
            $user = $prediction->user;
            $names = collect($predictions)->pluck('exercise')->filter()->all();
            $exercises = Exercise::whereIn('name', $names)->get()->keyBy('name');

            $filtered = collect($predictions)
                ->filter(function ($prediction) use ($exercises, $user): bool {
                    $name = is_array($prediction) ? ($prediction['exercise'] ?? null) : null;
                    $exercise = $name !== null ? $exercises->get($name) : null;

                    // Keep the recommendation unless it is a known exercise that
                    // is medically illegal for this user.
                    if (! $exercise instanceof Exercise || ! $user instanceof User) {
                        return true;
                    }

                    try {
                        return IAManager::isLegal($exercise, $user);
                    } catch (Throwable $e) {
                        return true;
                    }
                })
                ->take(5)
                ->values()
                ->all();

            $result = [
                'status' => 'success',
                'is_working' => $result['is_working'] ?? 1,
                'predictions' => $filtered,
            ];
        }

        $prediction->update([
            'status' => AiPredictionStatus::Completed,
            'result' => $result,
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $prediction = AiPrediction::find($this->predictionId);

        if ($prediction === null) {
            return;
        }

        $prediction->update([
            'status' => AiPredictionStatus::Failed,
            'error' => $exception->getMessage(),
        ]);
    }
}
