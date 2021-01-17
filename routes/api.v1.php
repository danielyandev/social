<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:api')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
});

Route::middleware('auth:api')->group(function (){
    Route::get('/user', [\App\Http\Controllers\Api\V1\UserController::class, 'getAuthUser']);
    Route::get('/users/search', [\App\Http\Controllers\Api\V1\UserController::class, 'search']);
    Route::get('/users/{user}', [\App\Http\Controllers\Api\V1\UserController::class, 'show']);
});
