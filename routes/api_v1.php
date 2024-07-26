<?php

use App\Http\Controllers\v1\TasksController;
use App\Http\Controllers\v1\SubtasksController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('tasks', TasksController::class);
    Route::apiResource('tasks.subtasks', SubtasksController::class);
});
