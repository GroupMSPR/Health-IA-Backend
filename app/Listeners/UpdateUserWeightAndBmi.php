<?php

namespace App\Listeners;

use App\Models\HealthMetric;
use App\Support\HealthCalculator;

class UpdateUserWeightAndBmi
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(HealthMetric $event): void
    {
        $user = auth()->user();

        if (! $user || ! $event->weight) {
            return;
        }

        $user->weight = $event->weight;

        $bmi = app(HealthCalculator::class)->bmi((float) $event->weight, (float) $user->height);

        if ($bmi !== null) {
            $user->bmi = $bmi;
        }

        $user->save();
    }
}
