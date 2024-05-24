<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function all() : JsonResource
    {
        return new TaskCollection(Task::where('user_id',auth()->user()->id)->get());
    }
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResource
    {
        return TaskResource::collection(Task::with(['subTasks', 'parent'])->where('user_id',auth()->user()->id)->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request) : JsonResponse
    {
        $task = Task::create([
            'title' => $request->title,
            'parent_id' => $request->parent_id ?? null,
            'user_id' => auth()->user()->id,
            'status' => $request->status
        ]);
        return response()->json(['message' => 'Task created successfully', 'task' => new TaskResource($task)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task) : JsonResource
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task) : JsonResponse
    {
        $task->title = $request->title;
        $task->parent_id = $request->parent_id ?? null;
        $task->user_id = auth()->user()->id;
        $task->status = $request->status;
        $task->save();

        return response()->json(['message' => 'Task updated successfully', 'task' => new TaskResource($task)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task) : JsonResponse
    {
        $task->delete();
        return response()->json(['message' => 'Task has been deleted successfully']);
    }
}
