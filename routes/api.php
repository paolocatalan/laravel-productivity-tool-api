<?php

use App\Enums\UserRolesEnums;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubtasksController;
use App\Http\Controllers\TasksController;
use App\Http\Resources\TaskResource;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum', 'last_active_at'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('tasks', TasksController::class);
    Route::apiResource('tasks.subtasks', SubtasksController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('v2')->group(function () {
    Route::get('/tasks', function() {
        return TaskResource::collection(
            Task::with('user')->get()
        );
    });
    Route::get('/tasks/{task}', function(Task $task) {
        return new TaskResource($task);
    });

});

