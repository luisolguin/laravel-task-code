<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importa Auth
use Illuminate\Support\Facades\Session; // Importa Session

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     * (Placeholder por ahora, se desarrollará más adelante)
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Asume que tienes una vista en resources/views/auth/login.blade.php
    }

    /**
     * Maneja la solicitud de inicio de sesión.
     * (Placeholder por ahora, se desarrollará más adelante)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Lógica de inicio de sesión aquí
        // Por ahora, solo un placeholder.
        // Ejemplo simplificado:
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/home'); // Redirige al home o a la URL anterior
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión del usuario actual

        $request->session()->invalidate(); // Invalida la sesión actual
        $request->session()->regenerateToken(); // Regenera el token CSRF para seguridad

        return redirect('/'); // Redirige a la raíz del sitio
    }
}
