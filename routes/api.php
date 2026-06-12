<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PivotController;
use App\Http\Controllers\RegisterController;
use App\Rest\Controllers\ConstraintController;
use App\Rest\Controllers\EquipmentController;
use App\Rest\Controllers\ExercisesController;
use App\Rest\Controllers\FoodsController;
use App\Rest\Controllers\GoalController;
use App\Rest\Controllers\HealthMetricsController;
use App\Rest\Controllers\SubscriptionController;
use App\Rest\Controllers\UsersController;
use Illuminate\Http\Request;
use MSPR2\SdkIA\Http\IAController;
use Illuminate\Support\Facades\Route;
use Lomkit\Rest\Facades\Rest;

Route::middleware('throttle:5,1')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('reset-password', [PasswordResetController::class, 'reset']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('current-user', function (Request $request) {
        return response()->json($request->user());
    });

    Route::get('exercises/count', function () { return response()->json(DB::table('exercises')->count()); });
    Route::get('foods/count', function () { return response()->json(DB::table('foods')->count()); });

    Rest::resource('users', UsersController::class)->withSoftDeletes();
    Rest::resource('foods', FoodsController::class)->withSoftDeletes();
    Rest::resource('exercises', ExercisesController::class)->withSoftDeletes();
    Rest::resource('health-metrics', HealthMetricsController::class)->withSoftDeletes();
    Rest::resource('goals', GoalController::class)->withSoftDeletes();
    Rest::resource('constraints', ConstraintController::class)->withSoftDeletes();
    Rest::resource('equipments', EquipmentController::class)->withSoftDeletes();
    Rest::resource('subscriptions', SubscriptionController::class)->withSoftDeletes();

    Route::post('consume', [PivotController::class, 'consumeFood']);
    Route::post('practice', [PivotController::class, 'practiceExercise']);

    Route::post('ai/analyze-meal', [IAController::class, 'analyzeMeal']);
    Route::post('ai/recommand-workout', [IAController::class, 'recommendWorkout']);
});
