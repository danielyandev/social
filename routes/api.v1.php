<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:api')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Api\V1\Auth\RegisterController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\V1\Auth\LoginController::class, 'login']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
