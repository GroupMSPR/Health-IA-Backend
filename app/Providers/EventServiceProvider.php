<?php

namespace App\Providers;

use App\Listeners\HandleUserCreated;
use App\Listeners\UpdateUserWeightAndBmi;
use App\Models\HealthMetric;
use App\Models\User;
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
        User::created([
            HandleUserCreated::class, 'handle',
        ]);

        HealthMetric::created([
            UpdateUserWeightAndBmi::class, 'handle',
        ]);
    }
}
