<?php

use App\Http\Controllers\api\v1\FollowController;
use App\Http\Controllers\api\v1\PostController;
use App\Http\Controllers\api\v1\ProfileController;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/user', UserController::class);
Route::apiResource('/profile', ProfileController::class);
Route::apiResource('/post', PostController::class);
Route::post('/follow', [FollowController::class, 'store'])->name('follow.store');
Route::delete('/unfollow', [FollowController::class, 'destroy'])->name('follow.destroy');
Route::post('/like', [LikeController::class, 'store'])->name('like.store');
Route::delete('/unlike', [LikeController::class, 'destroy'])->name('like.destroy');
