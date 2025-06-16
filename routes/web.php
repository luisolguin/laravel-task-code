<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController; // Importa el controlador

Route::get('/', function () {
    return view('welcome');
});

// Ruta para mostrar el formulario de registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Ruta para procesar el registro (envío del formulario)
Route::post('/register', [RegisterController::class, 'register']);