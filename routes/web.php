<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController; // Importa el controlador
use App\Http\Controllers\Auth\LoginController;    //Importa el controlador de Login
use App\Http\Controllers\HomeController;    //Importa el controlador del Home
use App\Http\Controllers\NoteController;  //Importa el Controlador de Notas

Route::get('/', function () {
    return view('welcome');
});

// Rutas de registro ========================================================
// Ruta para mostrar el formulario de registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Ruta para procesar el registro (envío del formulario)
Route::post('/register', [RegisterController::class, 'register']);

// Rutas de autenticación por defecto de Laravel (opcional si no usas laravel/ui)
// Si no tienes login, estas rutas son para el logout.
// Si ya usas `laravel/ui` o `breeze`, estas ya existen.
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // Necesitas un showLoginForm en LoginController
Route::post('/login', [LoginController::class, 'login']); // Necesitas un login en LoginController
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // Esta es la importante para el logout

// Ruta protegida para el home/dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // Ruta para guardar una nueva nota
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    // ... (otras rutas relacionadas con notas irán aquí más adelante)
});

// Redirección inicial a la página de login si no estás autenticado
/*Route::get('/', function () {
    return redirect()->route('welcome');
});
*/