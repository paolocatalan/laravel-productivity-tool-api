<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\v1\Project;
use App\Traits\HttpResponses;
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
        Gate::authorize('manageTask', $task);

        $task = Task::create([
            'user_id' => Auth::id(),
            'project_id' => $this->getProjectId($request->project),
            'name' => $request->name,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'stage' => $request->stage
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
        Gate::authorize('manageTask', $task);

        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('deleteTask', $task);

        $task->delete();

        return $this->success('', 'Task has been deleted from the database.', 200);
    }

    private function getProjectId($title): int
    {
        $projectId = Project::where('title', $title)->first()->id;
        
        return $projectId;
    }
}
