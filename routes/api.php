<?php

use App\Http\Controllers\api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::apiResource('/user', UserController::class);