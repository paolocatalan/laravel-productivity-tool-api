<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubtaskRequest;
use App\Http\Resources\SubtaskResource;
use App\Models\Subtask;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Gate;

class SubtasksController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Task $task, Subtask $subtask)
    {
        return SubtaskResource::collection(
            Subtask::where('task_id', $task->id)->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubtaskRequest $request, Task $task, Subtask $subtask)
    {
        $data = Subtask::where('task_id', $task->id)->get();

        if ($data->isEmpty()) {
            abort(404);
        }

        $subtask = Subtask::create($request->validated());

        return new SubtaskResource($subtask);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, Subtask $subtask)
    {
        if ($this->isNotFound($task->id, $subtask->id)) {
            abort(404);
        }

        return new SubtaskResource($subtask);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSubtaskRequest $request, Task $task, Subtask $subtask)
    {
        if ($this->isNotFound($task->id, $subtask->id)) {
            abort(404);
        }

        $subtask->update($request->validated());

        return new SubtaskResource($subtask);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Subtask $subtask)
    {
        if ($this->isNotFound($task->id, $subtask->id)) {
            abort(404);
        }

        Gate::authorize('deleteSubtask', $subtask);

        $subtask->delete();

        return $this->success('', 'Task has been deleted from the database.', 200);
    }

    private function isNotFound($taskId, $subtaskId): bool
    {
        $subtask = Subtask::where('task_id', $taskId)->where('id', $subtaskId)->get();

        if ($subtask->isEmpty()) {
            return true;
        }

        return false;
    }

}
