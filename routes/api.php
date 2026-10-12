<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Tasks API Endpoints
Route::get('/tasks', [TaskController::class, 'index'])->name('api.tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('api.tasks.store');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('api.tasks.show');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('api.tasks.update');
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('api.tasks.toggle');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('api.tasks.destroy');
