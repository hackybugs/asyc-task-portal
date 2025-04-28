<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/tasks/submit', [TaskController::class, 'submit']);
Route::get('/tasks/status/{id}', [TaskController::class, 'status']);
Route::get('/tasks/result/{id}', [TaskController::class, 'result']);