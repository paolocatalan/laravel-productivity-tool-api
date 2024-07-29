<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TaskResource::collection(
            Task::with('user')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, Task $task)
    {
        if (Gate::denies('createTask', $task)) {
            return $this->error('', 'You are not authorized to make this request.', 403);
        }

        $task = Task::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority
        ]);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource. laravel implicit binding
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaskRequest $request, Task $task)
    {
        if (Gate::denies('updateTask', $task)) {
            return $this->error('', 'You are not authorized to make this request.', 403);
        }

        $task->update($request->validated());

        return new TaskResource($task);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (Gate::denies('deleteTask', $task)) {
            return $this->error('', 'You are not authorized to make this request.', 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task has been deleted from the database.'
        ]);
    }
}
