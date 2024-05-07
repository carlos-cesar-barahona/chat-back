<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\V1\ChatController;
use App\Http\Controllers\API\V1\RegisterController;
use App\Http\Controllers\API\V1\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->namespace('API\V1')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'logIn']);

    Route::prefix('customer')->middleware('auth:api')->group(function () {
        Route::get('/me', [LoginController::class, 'isLoggedIn']);
        Route::get('/logout', [LoginController::class, 'logOut']);

        Route::prefix('chat')->group(function () {
            Route::get('/get-all', [ChatController::class, 'getAll']);
            Route::get('/get-by-id/{userId}', [ChatController::class, 'getById']);
            Route::post('/store', [ChatController::class, 'store']);
            Route::get('/get-user-info-by-id/{userId}', [ChatController::class, 'getUserInfoById']);
            Route::post('/file-upload', [ChatController::class, 'flieUpload']);
        });
    });
});
