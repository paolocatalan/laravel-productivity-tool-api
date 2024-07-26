<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\SubtaskResource;
use App\Models\Subtask;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class SubtasksController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(string $task)
    {
        $data = Subtask::where('task_id', $task)->get();

        if ($data->isEmpty()) {
            return $this->error('', 'Server cannot find the requested resource.', 404);
        }

        return new SubtaskResource($data[0]);
        // return SubtaskResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, Subtask $subtask, string $task)
    {
        if (Gate::denies('createSubtask', $subtask)) {
            return $this->error('', 'You are not authorized to make this request.', 403);
        }

        $data = Subtask::create([
            'task_id' => $task,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority
        ]);

        return new SubtaskResource($data[0]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $task, string $subtask)
    {
        $data = Subtask::where('task_id', $task)->where('id', $subtask)->get();

        if ($data->isEmpty()) {
            return $this->error('', 'Server cannot find the requested resource.', 404);
        }

        return new SubtaskResource($data[0]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $task, string $subtask)
    {
        $data = Subtask::where('task_id', $task)->where('id', $subtask)->get();

        if ($data->isEmpty()) {
            return $this->error('', 'Server cannot find the requested resource.', 404);
        }

        if (Gate::denies('updateSubtask', Subtask::find($subtask))) {
            return $this->error('', 'You are not authorized to make this request.', 403);
        }

        $data[0]->update($request->all());

        return new SubtaskResource($data[0]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $task, string $subtask)
    {
        $data = Subtask::where('task_id', $task)->where('id', $subtask)->get();

        if ($data->isEmpty()) {
            return $this->error('', 'Server cannot find the requested resource.', 404);
        }

        if (Gate::denies('deleteSubtask', Subtask::find($subtask))) {
            return $this->error('', 'You are not authorized to make this request.', 403);
        }

        $data[0]->delete();
    }

}
