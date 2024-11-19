<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ReservationController;

/* Ruta para crear los recursos**/
Route::post('/alta', [ResourceController::class, 'store']);

/* Ruta listar los recursos disponibles**/
Route::get('/resources', [ResourceController::class, 'index']);

/* Ruta para listar un recurso disponible en horario especifico**/
Route::get('/resources/{id}/availability', [ResourceController::class, 'availability']);

/* Ruta para realizar la reserva del recurso**/
Route::post('/reservations', [ReservationController::class, 'store']);

/* Ruta para cancelar la reserva por el id de la tabla reservations**/
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
