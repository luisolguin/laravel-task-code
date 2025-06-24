<?php

namespace App\Http\Controllers;


use App\Models\Note; // Importa tu modelo Note
use App\Models\Tag;  // Importa tu modelo Tag
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para obtener el usuario autenticado
use Illuminate\Validation\ValidationException; // Para manejar errores de validación
use Illuminate\Support\Str;

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
     * Muestra una nota específica (útil para la edición).
     * Laravel automáticamente inyecta la instancia de Note gracias al Model Binding.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\RedirectResponse
     */
    public function show(Note $note)
    {
        // Asegúrate de que el usuario autenticado sea el dueño de la nota
        if (Auth::id() !== $note->user_id) {
            //abort(403, 'Acceso no autorizado.'); // O redirige con un mensaje de error
            return response()->json(['message' => 'Acceso no autorizado a esta nota.'], 403);
        }

        // Si se solicita vía AJAX, se devuelve la nota como JSON.
        // Esto es útil si construyes el overlay dinámicamente con JS.
        //if (request()->expectsJson()) {
        //    return response()->json($note->load('tags')); // Carga las etiquetas también
        //}

        // Si se accede directamente vía URL, puedes redirigir o mostrar una vista
        // Por ahora, para este flujo, generalmente se llama vía AJAX.
        //return redirect()->route('home')->with('error', 'Acceso directo a la edición de nota no permitido.');
        return response()->json($note->load('tags'));
    }

    /**
     * Actualiza la nota especificada en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Note $note)
    {
        // Asegúrate de que el usuario autenticado sea el dueño de la nota
        if (Auth::id() !== $note->user_id) {
            return redirect()->route('home')->with('error', 'No tienes permiso para editar esta nota.');
        }

        try {
            // 1. Validar los datos de entrada
            $validatedData = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string'],
                // Puedes añadir 'is_important' aquí si lo conviertes en un checkbox en el overlay
                // 'is_important' => ['boolean'], // Ejemplo: si fuera un checkbox
            ]);

            // 2. Actualizar la nota
            $note->update([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                // 'is_important' => $request->has('is_important'), // Ejemplo para un checkbox
            ]);

            // 3. Re-procesar las etiquetas del contenido (para actualizar si cambiaron)
            $this->processTags($note, $validatedData['content']);

            return redirect()->route('home')->with('success', 'Nota actualizada exitosamente.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar la nota: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Elimina la nota especificada de la base de datos.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Note $note)
    {
        // Asegúrate de que el usuario autenticado sea el dueño de la nota
        if (Auth::id() !== $note->user_id) {
            return redirect()->route('home')->with('error', 'No tienes permiso para eliminar esta nota.');
        }

        try {
            $note->delete(); // Esto también eliminará las entradas en note_tag por 'onDelete('cascade')'

            return redirect()->route('home')->with('success', 'Nota eliminada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar la nota: ' . $e->getMessage());
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
