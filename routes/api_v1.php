<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\v1\ProjectController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\SubtasksController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'store']);

Route::apiResource('projects', ProjectController::class);

Route::middleware(['auth:sanctum', 'last_active_at'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('tasks', TasksController::class);
    Route::apiResource('tasks.subtasks', SubtasksController::class);
});
