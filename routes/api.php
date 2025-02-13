<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

define('SWAGGER_API_URL', config('app.url') . '/api');

Route::middleware('guest:api')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth:api')->group(function (){
    Route::get('/user', [UserController::class, 'getAuthUser']);
    Route::get('/user/friends', [UserController::class, 'getFriends']);

    Route::get('/users/search', [UserController::class, 'search']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::get('/users/{user}/posts', [PostController::class, 'index']);
    Route::post('/users/{user}/posts', [PostController::class, 'store']);

    Route::apiResource('/relationships', 'RelationshipController')->only(['index','store', 'update', 'destroy']);
});
