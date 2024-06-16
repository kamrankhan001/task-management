<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\GroupController;

use App\Http\Controllers\EmployeeTaskController;


// Public Routes
Route::post('login', [AuthController::class, 'login'])->name('login');

// Protected Routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function(){
    // Auth Routes
    Route::patch('reset-password/{id}', [AuthController::class, 'resetPassword']);
    
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);

    // User Routes
    Route::resource('users', UserController::class)->except('create', 'edit');

    // Category Routes
    Route::resource('categories', CategoryController::class)->except('create', 'edit');

    // Task Routes
    Route::resource('tasks', TaskController::class)->except('create', 'edit');

    // Comment Routes
    Route::resource('comments', CommentController::class)->except('create', 'edit');

    // Group Routes
    Route::resource('groups', GroupController::class)->except('create', 'edit');
});

Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('employees')->group(function(){
        //Dashboard
        Route::get('dashboard/{id}', [EmployeeTaskController::class, 'dashboard']);

        //Task
        Route::get('tasks/{id}', [EmployeeTaskController::class, 'tasks']);
        Route::patch('tasks/{id}/complete', [EmployeeTaskController::class, 'markTaskAsComplete']);

        // Group
        Route::get('groups/{id}', [EmployeeTaskController::class, 'groups']);

    });

});