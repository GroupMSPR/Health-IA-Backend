<?php

namespace App\Jobs;

use App\Enums\AiPredictionStatus;
use App\Models\AiPrediction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use MSPR2\SdkIA\Facade\IAManager;
use Throwable;

class AnalyzeMealJob implements ShouldQueue
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

        $input = $prediction->input ?? [];
        $path = (string) ($input['image_path'] ?? '');

        $base64 = base64_encode((string) Storage::disk('public')->get($path));
        $result = IAManager::analyzeMeal($base64, (string) ($input['original_name'] ?? 'image.png'));

        $prediction->update([
            'status' => AiPredictionStatus::Completed,
            'result' => $result,
        ]);

        if ($path !== '') {
            Storage::disk('public')->delete($path);
        }
    }

    public function failed(Throwable $exception): void
    {
        $prediction = AiPrediction::find($this->predictionId);

        if ($prediction === null) {
            return;
        }

        $path = (string) ($prediction->input['image_path'] ?? '');
        if ($path !== '') {
            Storage::disk('public')->delete($path);
        }

        $prediction->update([
            'status' => AiPredictionStatus::Failed,
            'error' => $exception->getMessage(),
        ]);
    }
}
