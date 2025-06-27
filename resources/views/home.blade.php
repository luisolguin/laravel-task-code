<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas - Mi Aplicación de Notas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     {{-- Agrega el meta tag para el token CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Enlace a tu archivo CSS personalizado --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        
    </style>
</head>
<body>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

        <a href="{{ route('home') }}" class="{{ $currentFilter === 'Todas las Notas' ? 'active-filter' : '' }}">Todas las Notas</a>
        <a href="{{ route('notes.important') }}" class="{{ $currentFilter === 'Notas Importantes' ? 'active-filter' : '' }}">Notas Importantes</a>

        <div class="sidebar-heading">ETIQUETAS</div>
        @forelse ($userTags as $tag)
            <a href="{{ route('notes.byTag', ['tag' => $tag->name]) }}" class="{{ $currentFilter === 'Etiqueta: #' . $tag->name ? 'active-filter' : '' }}">#{{ $tag->name }}</a>
        @empty
            <a href="#" style="color: #bbb; cursor: default;">No tienes etiquetas aún.</a>
        @endforelse
    </div>

    <div class="header">
        <div class="header-left">
            <span class="menu-icon" onclick="openNav()"><i class="fas fa-bars"></i></span>
            <i class="fas fa-sticky-note app-logo"></i>
            <span class="app-name">Notas</span>
        </div>

        <div class="search-bar">
             {{-- Formulario de Búsqueda --}}
            <form action="{{ route('home') }}" method="GET" style="display: flex; width: 100%;">
                <input type="text" name="search" id="search-input" placeholder="Buscar notas..." value="{{ $searchTerm ?? '' }}">
                <i class="fas fa-search search-icon" onclick="this.closest('form').submit()"></i>
            </form>
        </div>

        <div class="header-right">
            <button class="action-button" title="Ajustes"><i class="fas fa-cog"></i></button>
            <button class="action-button" title="Cambiar Tema"><i class="fas fa-moon"></i></button>

            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="action-button" title="Cerrar Sesión"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="banner">
            Bienvenido a tu aplicación de notas, {{ Auth::user()->name ?? 'Usuario' }}!
        </div>

        <div id="noteCreateCard" class="note-create-card">
            <button type="button" class="close-card-button" onclick="closeNoteCard()">
                &times; 
            </button>
            <form id="createNoteForm" method="POST" action="{{ route('notes.store') }}">
                @csrf
                <input type="text" name="title" id="noteTitle" placeholder="Título" value="{{ old('title') }}">
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <textarea name="content" id="noteContent" placeholder="Escribe tu nota aquí..." oninput="autoExpand(this)">{{ old('content') }}</textarea>
                @error('content')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <button type="submit">Guardar Nota</button>
            </form>
        </div>

         @if (session('success'))
            <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px; width: 100%; max-width: 500px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px; width: 100%; max-width: 500px; text-align: center;">
                {{ session('error') }}
            </div>
        @endif

       <section class="notes-list">
            <h3>Tus Notas</h3>

            @forelse ($notes as $note)
                <div class="note-item" onclick="openEditOverlay({{ $note->id }})">
                    <h4>
                        {{ $note->title }}
                        @if ($note->is_important)
                            <i class="fas fa-star" style="color: gold; margin-left: 5px;" title="Nota Importante"></i>
                        @endif
                    </h4>
                    <p>{{ Str::limit($note->content, 200) }}</p> <div class="note-tags">
                        @forelse ($note->tags as $tag)
                            <span>#{{ $tag->name }}</span>
                        @empty
                            @endforelse
                    </div>
                    <small style="color: #888; font-size: 0.8em; display: block; margin-top: 10px;">
                        Creada: {{ $note->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            @empty
             @if (!$filterMessage) <p style="text-align: center; color: #777;">No tienes notas aqui</p>
                @endif
                <p style="text-align: center; color: #777;">No tienes notas aún. ¡Crea tu primera nota usando el botón '+'!</p>
            @endforelse

            <div class="pagination-container">
                {{ $notes->links() }}
            </div>

        </section>
    </div>
    <div class="fab-button" onclick="toggleNoteCard()">
        <i class="fas fa-plus"></i>
    </div>

    {{-- Overlay de Edición de Nota --}}
    <div id="noteEditOverlay" class="note-edit-overlay">
        <div class="note-edit-card">
            <button type="button" class="close-card-button" onclick="closeEditOverlay()">
                &times;
            </button>

            <form id="editNoteForm" method="POST">
                @csrf
                @method('PUT') <input type="hidden" name="note_id" id="editNoteId"> <input type="text" name="title" id="editNoteTitle" placeholder="Título" required>
                {{-- No se usa @error aquí porque los errores se manejan con JS para el overlay --}}

                <textarea name="content" id="editNoteContent" placeholder="Escribe tu nota aquí..." oninput="autoExpand(this)" required></textarea>
                {{-- No se usa @error aquí --}}

                <div class="action-buttons-bottom">
                    <button type="button" class="delete-button" onclick="confirmDeleteNote()">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>

                    <button type="submit" class="save-button">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
    {{-- Enlace a tu archivo JavaScript personalizado --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
         document.addEventListener('DOMContentLoaded', function() {
            const createTextarea = document.getElementById('noteContent');
            if (createTextarea) {
                autoExpand(createTextarea);
            }
            // Aquí mantenemos la lógica de Blade para la visibilidad inicial de la tarjeta
            // para que se muestre si hay errores de validación al recargar la página.
            @if (!$errors->any() && !session('success') && !session('error'))
                document.getElementById('noteCreateCard').style.display = 'none';
            @endif
        });
    </script>
</body>
</html>