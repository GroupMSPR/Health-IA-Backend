<?php

namespace App\Providers;

use App\Listeners\UpdateUserWeightAndBmi;
use App\Models\HealthMetric;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        HealthMetric::created([
            UpdateUserWeightAndBmi::class,
            'handle',
        ]);
    }
}
