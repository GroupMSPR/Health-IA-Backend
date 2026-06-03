<?php

namespace App\Providers;

use App\Models\Equipment;
use App\Models\Exercise;
use App\Models\Food;
use App\Models\Goal;
use App\Models\HealthMetric;
use App\Models\Muscle;
use App\Models\Subscription;
use App\Models\User;
use App\Policies\EquipmentPolicy;
use App\Policies\ExercisePolicy;
use App\Policies\FoodPolicy;
use App\Policies\GoalPolicy;
use App\Policies\HealthMetricPolicy;
use App\Policies\MusclePolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends \Illuminate\Foundation\Support\Providers\AuthServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Food::class => FoodPolicy::class,
        Exercise::class => ExercisePolicy::class,
        HealthMetric::class => HealthMetricPolicy::class,
        Goal::class => GoalPolicy::class,
        Muscle::class => MusclePolicy::class,
        Subscription::class => SubscriptionPolicy::class,
        Equipment::class => EquipmentPolicy::class,
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
