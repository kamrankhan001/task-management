<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Group;

class EmployeeTaskController extends Controller
{
    public function dashboard($userId)
    {
        $individualTasks = Task::where('user_id', $userId)->get();

        $groupTasks = Task::whereHas('group.users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        $completedTasksCount = $individualTasks->where('status', 'complete')->count() + $groupTasks->where('status', 'complete')->count();
        $pendingTasksCount = $individualTasks->where('status', 'pending')->count() + $groupTasks->where('status', 'pending')->count();
        $individualTasksCount = $individualTasks->count();
        $groupTasksCount = $groupTasks->count();

        return response()->json([
            'completed_tasks' => $completedTasksCount,
            'pending_tasks' => $pendingTasksCount,
            'individual_tasks' => $individualTasksCount,
            'group_tasks' => $groupTasksCount,
        ]);
    }

    public function tasks(Request $request, $userId)
    {
        $user = User::with('groups')->findOrFail($userId);

        $individualTasks = Task::with(['category', 'group'])
            ->where('user_id', $userId)
            ->get();

        $groupIds = $user->groups->pluck('id');
        $groupTasks = Task::with(['category', 'group'])
            ->whereIn('group_id', $groupIds)
            ->get();

        $tasks = $individualTasks->merge($groupTasks);

        $completedTasks = $tasks->where('status', 'complete')->values();
        $reviewTasks = $tasks->where('status', 'review')->values();
        $pendingTasks = $tasks->where('status', 'pending')->values();

        return response()->json([
            'completed' => $completedTasks,
            'review' => $reviewTasks,
            'pending' => $pendingTasks,
        ]);
    }

    public function markTaskAsComplete($taskId)
    {
        $task = Task::findOrFail($taskId);

        if ($task->status !== 'complete') {
            $task->status = 'review';
            $task->save();
        }

        return response()->json(['message' => 'Task status updated to review'], 200);
    }

    public function groups($userId)
    {
        $groups = Group::with('users', 'tasks')
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        return response()->json($groups);
    }
}
