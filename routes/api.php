<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']); 

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Game routes
    Route::get('/games', [GameController::class, 'index']);
    Route::post('/games/start', [GameController::class, 'start']);
    Route::get('/games/{gameId}', [GameController::class, 'show']);
    Route::post('/games/{gameId}/characters/{characterId}/message', [GameController::class, 'sendMessage']);
    Route::post('/games/{gameId}/guess', [GameController::class, 'guess']);
});
