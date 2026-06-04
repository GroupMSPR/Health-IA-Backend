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
use Illuminate\Support\Facades\Route;
use Lomkit\Rest\Facades\Rest;

Route::get('/test-connection', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Connecté avec succès au Backend Laravel !',
    ]);
});

Route::middleware('throttle:5,1')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [PasswordResetController::class, 'reset']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user/me', function (Request $request) {
        return $request->user();
    });

    Rest::resource('/users', UsersController::class)->withSoftDeletes();
    Rest::resource('/foods', FoodsController::class)->withSoftDeletes();
    Rest::resource('/exercises', ExercisesController::class)->withSoftDeletes();
    Rest::resource('/health-metrics', HealthMetricsController::class)->withSoftDeletes();
    Rest::resource('/goals', GoalController::class)->withSoftDeletes();
    Rest::resource('/constraints', ConstraintController::class)->withSoftDeletes();
    Rest::resource('/equipments', EquipmentController::class)->withSoftDeletes();
    Rest::resource('/subscriptions', SubscriptionController::class)->withSoftDeletes();

    Route::post('/consume', [PivotController::class, 'consumeFood']);
    Route::post('/practice', [PivotController::class, 'practiceExercise']);
});
