<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Food;
use App\Policies\FoodPolicy;
use App\Models\Exercise;
use App\Policies\ExercisePolicy;
use App\Models\HealthMetric;
use App\Policies\HealthMetricPolicy;

class AuthServiceProvider extends \Illuminate\Foundation\Support\Providers\AuthServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Food::class => FoodPolicy::class,
        Exercise::class => ExercisePolicy::class,
        HealthMetric::class => HealthMetricPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
