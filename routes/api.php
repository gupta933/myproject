<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\ApiKeyMiddleware;

Route::middleware(ApiKeyMiddleware::class)->group(function () {
    Route::post('/todo/add', [TaskController::class, 'addTask']);
    Route::post('/todo/status', [TaskController::class, 'updateStatus']);
});
