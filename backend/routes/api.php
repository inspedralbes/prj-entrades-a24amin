<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\OrderController;

Route::get('/events', 'App\Http\Controllers\Api\EventController@index');
Route::get('/events/{id}', 'App\Http\Controllers\Api\EventController@show');

// Auth Routes
Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Rutes protegides per usuari (Compra i Reserva)
    Route::get('/me', [AuthController::class , 'me']);
    Route::post('/logout', [AuthController::class , 'logout']);

    // Reserva Temporal
    Route::post('/reservations', [ReservationController::class , 'reserve']);
    Route::post('/reservations/release', [ReservationController::class , 'release']);

    // Gestió de Comandes
    Route::get('/orders', [OrderController::class , 'index']);
    Route::post('/orders', [OrderController::class , 'store']);

    // Admin / CRUD Routes
    Route::get('/admin/events', 'App\Http\Controllers\Api\Admin\EventController@index');
    Route::post('/admin/events', 'App\Http\Controllers\Api\Admin\EventController@store');
    Route::put('/admin/events/{id}', 'App\Http\Controllers\Api\Admin\EventController@update');
    Route::delete('/admin/events/{id}', 'App\Http\Controllers\Api\Admin\EventController@destroy');
});