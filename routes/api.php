<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('guest:api')->group(function () {
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
});

Route::middleware('auth:api')->group(function (){
    Route::get('/user', [\App\Http\Controllers\UserController::class, 'getAuthUser']);
    Route::get('/users/search', [\App\Http\Controllers\UserController::class, 'search']);
    Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show']);
});
