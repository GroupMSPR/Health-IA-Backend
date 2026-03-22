<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Models\User;
use App\Rest\Controllers\ExercisesController;
use App\Rest\Controllers\FoodsController;
use App\Rest\Controllers\HealthMetricsController;
use App\Rest\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lomkit\Rest\Facades\Rest;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('user', function (Request $request) {
        return User::all();
    });

    Rest::resource('users', UsersController::class)->withSoftDeletes();
    Rest::resource('foods', FoodsController::class)->withSoftDeletes();
    Rest::resource('exercises', ExercisesController::class)->withSoftDeletes();
    Rest::resource('health-metrics', HealthMetricsController::class)->withSoftDeletes();
});
