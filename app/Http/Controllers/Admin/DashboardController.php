<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Task;
use App\Models\Category;
use App\Models\Group;


class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('dashboard_stats', 60, function() {
            return [
                'employees' => User::where('is_admin', false)->count(),
                'tasks' => Task::count(),
                'categories' => Category::count(),
                'groups' => Group::count(),
            ];
        });

        return response()->json($stats);
    }
}
