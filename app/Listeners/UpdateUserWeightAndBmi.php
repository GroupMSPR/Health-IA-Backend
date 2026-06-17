<?php

namespace App\Listeners;

use App\Models\HealthMetric;

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

        if ($user->height > 0) {
            $heightInMeters = $user->height / 100;
            $user->bmi = round($event->weight / ($heightInMeters ** 2), 2);
        }

        $user->save();
    }
}
