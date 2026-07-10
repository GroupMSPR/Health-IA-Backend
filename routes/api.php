<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PivotController;
use App\Http\Controllers\PostMediaController;
use App\Http\Controllers\RegisterController;
use App\Rest\Controllers\CommentController;
use App\Rest\Controllers\ConstraintController;
use App\Rest\Controllers\EquipmentController;
use App\Rest\Controllers\ExercisesController;
use App\Rest\Controllers\FoodsController;
use App\Rest\Controllers\GoalController;
use App\Rest\Controllers\HealthMetricsController;
use App\Rest\Controllers\MuscleController;
use App\Rest\Controllers\PostController;
use App\Rest\Controllers\SubscriptionController;
use App\Rest\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lomkit\Rest\Facades\Rest;
use MSPR2\SdkIA\Http\IAController;

Route::middleware('throttle:5,1')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
    Route::post('reset-password', [PasswordResetController::class, 'reset']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('current-user', function (Request $request) {
        return response()->json($request->user());
    });

    Route::get('exercises/count', function () {
        return response()->json(
            DB::table('exercises')
                ->whereNull('deleted_at')
                ->count()
        );
    });
    Route::get('foods/count', function () {
        return response()->json(
            DB::table('foods')
                ->whereNull('deleted_at')
                ->count()
        );
    });
    Route::get('health-metrics/count', function (Request $request) {
        return response()->json(
            DB::table('health_metrics')
                ->where('user_id', $request->user()->id)
                ->whereNull('deleted_at')
                ->count()
        );
    });

    Rest::resource('users', UsersController::class)->withSoftDeletes();
    Rest::resource('foods', FoodsController::class)->withSoftDeletes();
    Rest::resource('exercises', ExercisesController::class)->withSoftDeletes();
    Rest::resource('health-metrics', HealthMetricsController::class)->withSoftDeletes();
    Rest::resource('goals', GoalController::class)->withSoftDeletes();
    Rest::resource('constraints', ConstraintController::class)->withSoftDeletes();
    Rest::resource('muscles', MuscleController::class)->withSoftDeletes()->only('search');
    Rest::resource('equipments', EquipmentController::class)->withSoftDeletes();
    Rest::resource('subscriptions', SubscriptionController::class)->withSoftDeletes();
    Rest::resource('posts', PostController::class)->withSoftDeletes();
    Rest::resource('comments', CommentController::class)->withSoftDeletes();

    Route::post('consume', [PivotController::class, 'consumeFood']);
    Route::post('practice', [PivotController::class, 'practiceExercise']);

    Route::post('ai/analyze-meal', [IAController::class, 'analyzeMeal']);
    Route::post('ai/recommend', [IAController::class, 'recommend']);

    Route::post('update-avatar', [AvatarController::class, 'updateAvatar']);
    Route::post('posts/upload', [PostMediaController::class, 'upload']);
    Route::post('posts/{id}/update', [PostMediaController::class, 'update']);
    Route::delete('posts/{id}/delete', [PostMediaController::class, 'destroy']);

    Route::post('like', [PivotController::class, 'likePost']);
});
