<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Rest\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lomkit\Rest\Facades\Rest;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('user', function (Request $request) {
        return \App\Models\User::all();
    });
    Rest::resource('foods', \App\Rest\Controllers\FoodsController::class);

    Rest::resource('users', UsersController::class)->withSoftDeletes();
});
