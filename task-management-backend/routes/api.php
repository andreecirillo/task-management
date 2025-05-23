<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TasksController;

// Users routes
Route::post('/users', [UsersController::class, 'register']);
Route::post('/users/login', [UsersController::class, 'login']);

// Users logged routes
Route::group(['middleware' => ['jwt']], function () {

    // Users routes
    Route::post('/users/logout', [UsersController::class, 'logout']);
    Route::put('/users', [UsersController::class, 'update']);

    //Tasks routes
    Route::get('/tasks', [TasksController::class, 'index']);
    Route::post('/tasks', [TasksController::class, 'store']);
    Route::put('/tasks/{id}', [TasksController::class, 'update']);
    Route::delete('/tasks/{id}', [TasksController::class, 'destroy']);
});
