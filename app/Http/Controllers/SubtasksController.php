<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubtaskRequest;
use App\Http\Resources\SubtaskResource;
use App\Models\Subtask;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

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
        if (Gate::denies('createSubtask', $subtask)) {
            return $this->error('', 'You are not authorized to make this request.', 403);
        }

        $subtask = Subtask::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority
        ]);

        return new SubtaskResource($subtask);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, Subtask $subtask)
    {
        return new SubtaskResource($subtask);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSubtaskRequest $request, Task $task, Subtask $subtask)
    {
        if (Gate::denies('updateSubtask', $subtask)) {
            return $this->error('', 'You are not authorized to make this request.', 403);
        }

        $subtask->update($request->validated());

        return new SubtaskResource($subtask);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Subtask $subtask)
    {
        Gate::authorize('deleteSubtask', $subtask);

        $subtask->delete();

        return $this->success('', 'Task has been deleted from the database.', 200);
    }

}
