<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);

    Route::prefix('{id}/tasks')->group(function () {
        Route::get('/stats', [TaskController::class, 'statsByUser']);
        Route::post('/', [TaskController::class, 'store']);
        Route::get('/', [TaskController::class, 'index']);
        Route::get('/{taskId}', [TaskController::class, 'show']);
        Route::put('/{taskId}', [TaskController::class, 'update']);
        Route::delete('/{taskId}', [TaskController::class, 'destroy']);
        Route::delete('/', [TaskController::class, 'destroyUnprocessed']);
    });
});

    Route::get('/tasks/stats', [TaskController::class, 'statsForAllUsers']);
