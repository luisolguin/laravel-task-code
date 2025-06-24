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
    // Ruta para mostrar notas importantes
    Route::get('/home/important', [HomeController::class, 'index', 'filter' => 'important'])->name('notes.important');

    // Ruta para mostrar notas por etiqueta (con parámetro comodín para el nombre de la etiqueta)
    // El 'whereAlphaNumeric' asegura que el nombre de la etiqueta solo contenga letras y números.
    Route::get('/home/tag/{tag}', [HomeController::class, 'index'])
        ->name('notes.byTag')
        ->where('tag', '[a-zA-Z0-9]+'); // Restricción para solo letras y números

    // Ruta para guardar una nueva nota
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    // Ruta para mostrar una nota específica para edición
    Route::get('/notes/{note}', [NoteController::class, 'show'])->name('notes.show');
    // Ruta para actualizar una nota
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    // Ruta para eliminar una nota
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

});

// Redirección inicial a la página de login si no estás autenticado
/*Route::get('/', function () {
    return redirect()->route('welcome');
});
*/