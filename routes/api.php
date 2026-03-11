<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('users', function (Request $request) {
        if ($request->user()->cannot('viewAny', \App\Models\User::class)) {
            return $request->user();
        }

        return \App\Models\User::all();
    });

});
