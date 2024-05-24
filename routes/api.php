<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('me', 'me');
        Route::get('logout', 'logout');
    });
});


Route::prefix('tasks')->controller(TaskController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/all', 'all');
    Route::match(['put', 'patch'], '/{task}','update');
    Route::delete('/{task}','destroy');
});
