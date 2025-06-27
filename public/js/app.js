// JavaScript para el menú de hamburguesa
function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
}

function toggleNoteCard() {
    const noteCard = document.getElementById('noteCreateCard');
    if (noteCard.style.display === 'flex') {
        closeNoteCard();
    } else {
        noteCard.style.display = 'flex';
        document.getElementById('noteTitle').focus();
        autoExpand(document.getElementById('noteContent'));
    }
}

function closeNoteCard() {
    document.getElementById('noteCreateCard').style.display = 'none';
}

function autoExpand(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = (textarea.scrollHeight) + 'px';
}

// --- FUNCIONES PARA EL OVERLAY DE EDICIÓN ---
async function openEditOverlay(noteId) {
    const overlay = document.getElementById('noteEditOverlay');
    const editNoteForm = document.getElementById('editNoteForm');
    const editNoteIdInput = document.getElementById('editNoteId');
    const editNoteTitleInput = document.getElementById('editNoteTitle');
    const editNoteContentTextarea = document.getElementById('editNoteContent');

    try {
        const response = await fetch(`/notes/${noteId}`);

        if (!response.ok) {
            let errorMessage = 'Error desconocido al cargar la nota.';
            try {
                const errorData = await response.json();
                errorMessage = errorData.message || errorMessage;
            } catch (e) {
                errorMessage = `Error ${response.status}: ${response.statusText}`;
            }
            throw new Error(errorMessage);
        }

        const note = await response.json();

        editNoteIdInput.value = note.id;
        editNoteTitleInput.value = note.title;
        editNoteContentTextarea.value = note.content;

        editNoteForm.action = `/notes/${note.id}`;

        autoExpand(editNoteContentTextarea);

        overlay.style.display = 'flex';
        editNoteTitleInput.focus();
    } catch (error) {
        console.error("Error al cargar la nota para edición:", error);
        alert("No se pudo cargar la nota para edición: " + error.message);
    }
}

function closeEditOverlay() {
    document.getElementById('noteEditOverlay').style.display = 'none';
}

function confirmDeleteNote() {
    if (confirm('¿Estás seguro de que quieres eliminar esta nota? Esta acción no se puede deshacer.')) {
        const noteId = document.getElementById('editNoteId').value;
        const deleteForm = document.createElement('form');
        deleteForm.method = 'POST';
        deleteForm.action = `/notes/${noteId}`;
        deleteForm.style.display = 'none';

        // Obtener el token CSRF de la etiqueta meta (Laravel lo genera automáticamente si usas @csrf)
        // Asegúrate de que tu home.blade.php tenga <meta name="csrf-token" content="{{ csrf_token() }}">
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        deleteForm.appendChild(methodInput);

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        deleteForm.appendChild(csrfInput);

        document.body.appendChild(deleteForm);
        deleteForm.submit();
    }
}

// Código que se ejecuta cuando el DOM está completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    const createTextarea = document.getElementById('noteContent');
    if (createTextarea) {
        autoExpand(createTextarea);
    }

    // Manejo de visibilidad de tarjeta de creación de nota al cargar
    // Esto depende de si hay errores de validación o mensajes de sesión.
    // Las directivas Blade @if para $errors->any() o session() no funcionarán aquí,
    // ya que este archivo es puramente JS. Se necesita una variable global desde Blade.
    // Por ahora, se mantendrá oculta por defecto al cargar.
    document.getElementById('noteCreateCard').style.display = 'none';

    // Ocultar overlay de edición al cargar la página
    document.getElementById('noteEditOverlay').style.display = 'none';
});