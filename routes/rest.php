<?php

use Lomkit\Rest\Facades\Rest;
use App\Rest\Controllers\ConstraintController;
use App\Rest\Controllers\EquipmentController;
use App\Rest\Controllers\ExercisesController;
use App\Rest\Controllers\FoodsController;
use App\Rest\Controllers\GoalController;
use App\Rest\Controllers\HealthMetricsController;
use App\Rest\Controllers\SubscriptionController;
use App\Rest\Controllers\UsersController;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Rest::resource('users', UsersController::class)->withSoftDeletes();
    Rest::resource('foods', FoodsController::class)->withSoftDeletes();
    Rest::resource('exercises', ExercisesController::class)->withSoftDeletes();
    Rest::resource('health-metrics', HealthMetricsController::class)->withSoftDeletes();
    Rest::resource('goals', GoalController::class)->withSoftDeletes();
    Rest::resource('constraints', ConstraintController::class)->withSoftDeletes();
    Rest::resource('equipments', EquipmentController::class)->withSoftDeletes();
    Rest::resource('subscriptions', SubscriptionController::class)->withSoftDeletes();

});
