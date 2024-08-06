<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\v1\ProjectController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\SubtasksController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'store']);

Route::apiResource('projects', ProjectController::class);

Route::middleware(['auth:sanctum', 'lastActiveAt'])->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user/approve', [UserController::class, 'approve']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('tasks', TasksController::class)->middleware('approved');
    Route::apiResource('tasks.subtasks', SubtasksController::class)->middleware('approved');
});
