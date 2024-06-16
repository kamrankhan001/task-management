<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['category', 'user', 'group']);

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        $tasks = $query->latest()->paginate(10);

        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated());

        return response()->json(
            [
                'success' => 'Task created successfully',
                'task' => new TaskResource($task),
            ],
            201,
        );
    }

    public function show($id)
    {
        try {
            $task = Cache::remember("task_{$id}", 60, function () use ($id) {
                return Task::with(['category', 'user', 'group'])->findOrFail($id);
            });

            return new TaskResource($task);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    }

    public function update(TaskRequest $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->update($request->validated());

            Cache::forget("task_{$id}");

            return response()->json(
                [
                    'success' => 'Task updated successfully',
                    'task' => new TaskResource($task),
                ],
                200,
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            Cache::forget("task_{$id}");

            return response()->json(['success' => 'Task deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    }
}
