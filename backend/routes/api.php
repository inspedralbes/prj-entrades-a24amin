<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AuthController;

Route::get('/events/{id}', 'App\Http\Controllers\Api\EventController@show');

// Auth Routes
Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class , 'logout']);
    Route::get('/me', [AuthController::class , 'me']);

    // Admin / CRUD Routes
    Route::get('/admin/events', 'App\Http\Controllers\Api\Admin\EventController@index');
    Route::post('/admin/events', 'App\Http\Controllers\Api\Admin\EventController@store');
    Route::put('/admin/events/{id}', 'App\Http\Controllers\Api\Admin\EventController@update');
    Route::delete('/admin/events/{id}', 'App\Http\Controllers\Api\Admin\EventController@destroy');
});