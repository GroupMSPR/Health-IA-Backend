<?php

namespace App\Providers;

use App\Models\Exercise;
use App\Models\Food;
use App\Models\HealthMetric;
use App\Models\User;
use App\Policies\ExercisePolicy;
use App\Policies\FoodPolicy;
use App\Policies\HealthMetricPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Food::class, FoodPolicy::class);
        Gate::policy(Exercise::class, ExercisePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(HealthMetric::class, HealthMetricPolicy::class);
    }
}
