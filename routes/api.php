<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubtasksController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'store']);

Route::middleware(['auth:sanctum', 'lastActiveAt'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('tasks', TasksController::class);
    Route::apiResource('tasks.subtasks', SubtasksController::class);
});
