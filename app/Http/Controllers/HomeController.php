<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Asegúrate de importar Auth

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); // Protege esta ruta para que solo usuarios autenticados puedan acceder
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       // Aquí puedes obtener el usuario autenticado
        $user = Auth::user();

        // Puedes pasar datos a la vista si lo necesitas, por ejemplo, el nombre del usuario
        return view('home', ['user' => $user]);
    }
}
