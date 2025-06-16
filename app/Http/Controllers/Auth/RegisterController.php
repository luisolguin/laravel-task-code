<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Asegúrate de importar tu modelo User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException; // Importa esto para manejar excepciones de validación

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register'); // Asume que tienes una vista en resources/views/auth/register.blade.php
    }

    /**
     * Maneja la solicitud de registro del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        try {
            // 1. Validar los datos de entrada
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'nickname' => ['required', 'string', 'max:255', 'unique:users'], // 'unique:users' valida unicidad
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // 'unique:users' valida unicidad
                'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' valida que haya un campo password_confirmation
            ]);

            // 2. Crear el nuevo usuario
            $user = User::create([
                'name' => $validatedData['name'],
                'lastname' => $validatedData['lastname'],
                'nickname' => $validatedData['nickname'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), // Hashear la contraseña
            ]);

            // 3. Opcional: Iniciar sesión al usuario recién registrado
            auth()->login($user);

            // 4. Redirigir al usuario después del registro exitoso
            return redirect('/home')->with('success', '¡Registro exitoso! Bienvenido.'); // O a donde quieras redirigirlo

        } catch (ValidationException $e) {
            // Si la validación falla (ej. email/nickname no únicos), Laravel redirigirá
            // automáticamente al formulario con los errores y los datos antiguos.
            // No necesitamos hacer nada aquí más allá de la lógica de validación
            // que ya está manejada por $request->validate().
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
}
