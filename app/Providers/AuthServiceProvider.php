<?php

namespace App\Providers;

use App\Models\Food;
use App\Models\HealthMetric;
use App\Models\User;
use App\Policies\FoodPolicy;
use App\Policies\HealthMetricPolicy;
use App\Models\Exercise;
use App\Models\Food;
use App\Models\User;
use App\Policies\ExercisePolicy;
use App\Policies\FoodPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends \Illuminate\Foundation\Support\Providers\AuthServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Food::class => FoodPolicy::class,
        HealthMetric::class => HealthMetricPolicy::class,
        Exercise::class => ExercisePolicy::class,
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
