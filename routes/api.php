<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\CommentController;
use App\Http\Controllers\api\v1\FollowController;
use App\Http\Controllers\api\v1\LikeController;
use App\Http\Controllers\api\v1\PostController;
use App\Http\Controllers\api\v1\ProfileController;
use App\Http\Controllers\api\v1\UserController;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('user', UserController::class);
    Route::apiResource('profile', ProfileController::class);
    Route::apiResource('post', PostController::class);
    Route::apiResource('post.comments', CommentController::class)->shallow();
    Route::post('follow', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('unfollow', [FollowController::class, 'destroy'])->name('follow.destroy');
    Route::post('like', [LikeController::class, 'store'])->name('like.store');
    Route::delete('unlike', [LikeController::class, 'destroy'])->name('like.destroy');

});
