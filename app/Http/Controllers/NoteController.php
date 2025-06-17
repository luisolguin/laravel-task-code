<?php

namespace App\Http\Controllers;


use App\Models\Note; // Importa tu modelo Note
use App\Models\Tag;  // Importa tu modelo Tag
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para obtener el usuario autenticado
use Illuminate\Validation\ValidationException; // Para manejar errores de validación

class NoteController extends Controller
{
    /**
     * Asegúrate de que solo los usuarios autenticados puedan acceder a este controlador.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Guarda una nueva nota en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // 1. Validar los datos de la nota
            $validatedData = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string'],
                // 'is_important' no viene del formulario por ahora, se puede añadir después
            ]);

            // 2. Crear la nota asociada al usuario autenticado
            $note = Auth::user()->notes()->create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'is_important' => false, // Por defecto no es importante al crear
            ]);

            // 3. Extraer y guardar las etiquetas
            $this->processTags($note, $validatedData['content']);

            // 4. Redirigir de vuelta al home con un mensaje de éxito
            return redirect()->route('home')->with('success', 'Nota guardada exitosamente.');

        } catch (ValidationException $e) {
            // Si la validación falla, redirige de vuelta con los errores y los datos antiguos
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Captura cualquier otra excepción general
            return redirect()->back()->with('error', 'Ocurrió un error al guardar la nota: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Método auxiliar para extraer y sincronizar etiquetas.
     *
     * @param \App\Models\Note $note
     * @param string $content
     * @return void
     */
    protected function processTags(Note $note, string $content)
    {
        // Expresión regular para encontrar #palabra (solo letras y números)
        // \B# - asegura que # no sea precedido por un carácter de palabra (para evitar #tag dentro depalabra#tag)
        // [\w]+ - una o más letras, números o guiones bajos (se ajusta a tus requerimientos)
        preg_match_all('/#([a-zA-Z0-9]+)\b/', $content, $matches);

        $tagNames = collect($matches[1])->unique()->map(function ($name) {
            return strtolower($name); // Convertir a minúsculas para consistencia
        });

        $tagIds = [];
        foreach ($tagNames as $tagName) {
            // Busca la etiqueta existente o crea una nueva
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }

        // Sincroniza las etiquetas con la nota
        // Esto adjunta las nuevas etiquetas y desvincula las etiquetas que ya no están en el contenido
        $note->tags()->sync($tagIds);
    }
}
