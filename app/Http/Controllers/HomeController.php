<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Asegúrate de importar Auth

use App\Models\Note; // Importa el modelo Note
use App\Models\Tag;  // Importa el modelo Tag

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
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $tag  // Aquí se inyecta el valor del comodín {tag} si la ruta coincide
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $tag = null)
    {
       // Aquí puedes obtener el usuario autenticado
        $user = Auth::user();
        $notesQuery = $user->notes()->latest(); // Consulta base, ordenadas por más reciente
        $currentFilter = ''; // Para informar a la vista qué filtro está activo
        $filterMessage = null; // Mensaje a mostrar si no hay notas importantes
        $searchTerm = $request->query('search');

        // Obtener todas las etiquetas únicas que pertenecen a las notas del usuario actual
        // Esto es más eficiente que cargar todas las etiquetas y luego filtrar.
        $userTags = $user->notes()->with('tags')
                            ->get() // Obtener todas las notas del usuario con sus etiquetas
                            ->pluck('tags') // Extraer solo las colecciones de etiquetas de cada nota
                            ->flatten() // Aplanar todas las colecciones de etiquetas en una sola
                            ->unique('id') // Obtener etiquetas únicas por ID
                            ->sortBy('name') // Opcional: ordenar alfabéticamente por nombre
                            ->values(); // Reindexar el array si es necesario

        // Lógica de filtrado
        // Detectar si el filtro es 'important' (desde la ruta /home/important)
        // Usamos Request::routeIs() para verificar si la ruta actual es 'notes.important'
        if ($searchTerm) {
            $notes = $notesQuery->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('content', 'like', '%' . $searchTerm . '%');
            })->paginate(21);
            $currentFilter = "Búsqueda: \"{$searchTerm}\"";
        }
        elseif ($request->routeIs('notes.important')) {
            $notes = $notesQuery->where('is_important', true)->paginate(21);
            $currentFilter = 'Notas Importantes';
            if (!$notesQuery->count() && $request->has('page') === false) {
                $filterMessage = 'No tienes notas importantes en este momento.';
            }
        }
        // Detectar si el filtro es por etiqueta (desde la ruta /home/tag/{tag})
        // Y si el parámetro $tag está presente.
        elseif ($tag) { // $tag contendrá el nombre de la etiqueta si se accede desde /home/tag/{nombre_etiqueta}
            $notes= $notesQuery->whereHas('tags', function ($query) use ($tag) {
                $query->where('name', $tag);
            })->paginate(21);
            $currentFilter = 'Etiqueta: #' . $tag;
        }
        // Si no hay filtros específicos, mostrar todas las notas
        else {
            $currentFilter = 'Todas las Notas';
            $notes = $user->notes()->latest()->paginate(21);
        }

       

        // Puedes pasar datos a la vista si lo necesitas, por ejemplo, el nombre del usuario
        return view('home', [
            'user' => $user,
            'notes' => $notes, // Pasa las notas paginadas a la vista
            'userTags' => $userTags, // Pasar las etiquetas del usuario a la vista
            'currentFilter' => $currentFilter, // Qué filtro está activo
            'filterMessage' => $filterMessage, // Mensaje para notas importantes vacías
            'searchTerm' => $searchTerm, // Pasa el término de búsqueda a la vista
        ]);
    }
}
