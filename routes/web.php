<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
    Log::info('Esto es un mensaje de prueba para verificar los logs.');
    return 'Log generado.';
});
