<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas - Mi Aplicación de Notas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos básicos para el diseño */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header-left, .header-right, .header-center {
            display: flex;
            align-items: center;
        }
        .menu-icon {
            font-size: 24px;
            cursor: pointer;
            margin-right: 15px;
            color: #555;
        }
        .app-logo {
            font-size: 24px;
            color: #007bff;
            margin-right: 10px;
        }
        .app-name {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }
        .search-bar {
            flex-grow: 1; /* Permite que la barra de búsqueda ocupe espacio */
            margin: 0 20px;
            position: relative;
        }
        .search-bar input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 16px;
            background-color: #f0f2f5;
        }
        .search-bar i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        .action-button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            margin-left: 15px;
            color: #555;
            padding: 5px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }
        .action-button:hover {
            background-color: #f0f0f0;
        }
        .main-content {
            padding: 20px;
        }
        .banner {
            background-color: #e0f7fa;
            color: #007bff;
            padding: 30px;
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .notes-list {
            width: 100%;
            max-width: 900px; /* Limita el ancho de la lista de notas */
            margin-top: 20px;
        }
        .note-item {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            margin-bottom: 15px;
            border: 1px solid #eee;
        }
        .note-item h4 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
        }
        .note-item p {
            font-size: 0.95em;
            line-height: 1.5;
            color: #555;
        }
        .note-tags span {
            display: inline-block;
            background-color: #e0e0e0;
            color: #666;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75em;
            margin-right: 5px;
            margin-top: 5px;
        }

        /* Menú de hamburguesa (oculto por defecto) */
        .sidebar {
            height: 100%;
            width: 0; /* Inicialmente oculto */
            position: fixed;
            z-index: 100;
            top: 0;
            left: 0;
            background-color: #fff;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 18px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: #007bff;
            background-color: #f0f0f0;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
            color: #333;
        }
        .sidebar-heading {
            padding: 15px 25px;
            font-size: 16px;
            font-weight: bold;
            color: #666;
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
        }
        .fab-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: background-color 0.3s ease;
            z-index: 50; /* Para que esté sobre otros elementos */
        }
        .fab-button:hover {
            background-color: #0056b3;
        }

        /* Tarjeta de Creación de Nota */
        .note-create-card {
            background-color: #fffacd; /* Amarillo suave */
            padding: 20px;
            border-radius: 15px; /* Bordes redondeados */
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 500px; /* Ancho fijo */
            margin-bottom: 30px; /* Espacio debajo de la tarjeta */
            display: none; /* Oculto por defecto */
            flex-direction: column;
            box-sizing: border-box;
            border: 1px solid #ffe082; /* Borde sutil amarillo */
        }
        .note-create-card input[type="text"] {
            width: 100%;
            padding: 10px 0; /* Menos padding lateral para el título */
            border: none;
            border-bottom: 1px solid #f0e68c; /* Borde inferior suave */
            background-color: transparent;
            font-size: 1.2em;
            font-weight: bold; /* Letra más fuerte para el título */
            margin-bottom: 15px;
            outline: none; /* Quitar el borde de enfoque */
        }
        .note-create-card textarea {
            width: 100%;
            padding: 10px 0; /* Menos padding lateral para la nota */
            border: none;
            background-color: transparent;
            font-size: 1em;
            line-height: 1.6;
            resize: vertical; /* Permite redimensionar verticalmente */
            min-height: 100px; /* Altura mínima */
            overflow-y: hidden; /* Oculta scrollbar inicialmente */
            margin-bottom: 20px;
            outline: none;
        }
        .note-create-card button {
            background-color: #28a745; /* Botón de guardar verde */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            align-self: flex-end; /* Alinea el botón a la derecha */
            transition: background-color 0.3s ease;
        }
        .note-create-card button:hover {
            background-color: #218838;
        }

        /* Mensajes de error */
        .error-message {
            color: red;
            font-size: 0.85em;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        /*:::::::::::::::::CSS de notas::::::::::*/
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f7f6; color: #333; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header-left, .header-right, .header-center { display: flex; align-items: center; }
        .menu-icon { font-size: 24px; cursor: pointer; margin-right: 15px; color: #555; }
        .app-logo { font-size: 24px; color: #007bff; margin-right: 10px; }
        .app-name { font-size: 22px; font-weight: bold; color: #333; }
        .search-bar { flex-grow: 1; margin: 0 20px; position: relative; }
        .search-bar input { width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 20px; font-size: 16px; background-color: #f0f2f5; }
        .search-bar i { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #888; }
        .action-button { background: none; border: none; font-size: 20px; cursor: pointer; margin-left: 15px; color: #555; padding: 5px; border-radius: 5px; transition: background-color 0.2s; }
        .action-button:hover { background-color: #f0f0f0; }
        .main-content { padding: 20px; display: flex; flex-direction: column; align-items: center; position: relative; }
        .banner { background-color: #e0f7fa; color: #007bff; padding: 30px; text-align: center; font-size: 1.5em; font-weight: bold; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); width: 100%; max-width: 900px; box-sizing: border-box; }
        .notes-list { width: 100%; max-width: 900px; margin-top: 20px; }
        .note-item { background-color: #ffffff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); margin-bottom: 15px; border: 1px solid #eee; }
        .note-item h4 { margin-top: 0; margin-bottom: 10px; color: #333; }
        .note-item p { font-size: 0.95em; line-height: 1.5; color: #555; }
        .note-tags span { display: inline-block; background-color: #e0e0e0; color: #666; padding: 4px 8px; border-radius: 12px; font-size: 0.75em; margin-right: 5px; margin-top: 5px; }

        /* Sidebar (Menú de Hamburguesa) */
        .sidebar { height: 100%; width: 0; position: fixed; z-index: 100; top: 0; left: 0; background-color: #fff; overflow-x: hidden; transition: 0.5s; padding-top: 60px; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar a { padding: 15px 25px; text-decoration: none; font-size: 18px; color: #818181; display: block; transition: 0.3s; }
        .sidebar a:hover { color: #007bff; background-color: #f0f0f0; }
        .sidebar .closebtn { position: absolute; top: 0; right: 25px; font-size: 36px; margin-left: 50px; color: #333; }
        .sidebar-heading { padding: 15px 25px; font-size: 16px; font-weight: bold; color: #666; border-bottom: 1px solid #eee; margin-bottom: 10px; }

        /* Botón Circular Flotante */
        .fab-button { position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; background-color: #007bff; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 30px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); cursor: pointer; transition: background-color 0.3s ease; z-index: 50; }
        .fab-button:hover { background-color: #0056b3; }

        /* Tarjeta de Creación de Nota */
        .note-create-card { background-color: #fffacd; padding: 20px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.15); width: 100%; max-width: 500px; margin-bottom: 30px; display: none; flex-direction: column; box-sizing: border-box; border: 1px solid #ffe082; }
        .note-create-card input[type="text"] { width: 100%; padding: 10px 0; border: none; border-bottom: 1px solid #f0e68c; background-color: transparent; font-size: 1.2em; font-weight: bold; margin-bottom: 15px; outline: none; }
        .note-create-card textarea { width: 100%; padding: 10px 0; border: none; background-color: transparent; font-size: 1em; line-height: 1.6; resize: vertical; min-height: 100px; overflow-y: hidden; margin-bottom: 20px; outline: none; }
        .note-create-card button { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 1em; cursor: pointer; align-self: flex-end; transition: background-color 0.3s ease; }
        .note-create-card button:hover { background-color: #218838; }

        /* Mensajes de error */
        .error-message { color: red; font-size: 0.85em; margin-top: 5px; margin-bottom: 10px; }

        /* Estilos para la paginación */
        .pagination-container { margin-top: 30px; display: flex; justify-content: center; width: 100%; max-width: 900px; }
        .pagination { display: flex; list-style: none; padding: 0; margin: 0; border-radius: .25rem; }
        .pagination li { margin: 0 2px; }
        .pagination li a, .pagination li span { position: relative; display: block; padding: .5rem .75rem; line-height: 1.25; color: #007bff; background-color: #fff; border: 1px solid #dee2e6; text-decoration: none; border-radius: .25rem; transition: all 0.3s ease; }
        .pagination li a:hover { z-index: 2; color: #0056b3; background-color: #e9ecef; border-color: #dee2e6; }
        .pagination li.active span { z-index: 3; color: #fff; background-color: #007bff; border-color: #007bff; }
        .pagination li.disabled span { color: #6c757d; pointer-events: none; background-color: #fff; border-color: #dee2e6; }
   
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
            <input type="text" placeholder="Buscar notas...">
            <i class="fas fa-search"></i>
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
                <div class="note-item">
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


    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
        }
        // JavaScript para mostrar/ocultar la tarjeta de nota
        function toggleNoteCard() {
            const noteCard = document.getElementById('noteCreateCard');
            if (noteCard.style.display === 'flex') {
                noteCard.style.display = 'none';
            } else {
                noteCard.style.display = 'flex';
                // Opcional: enfocar el campo de título al abrir
                document.getElementById('noteTitle').focus();
            }
        }

        // JavaScript para auto-expandir el textarea
        function autoExpand(textarea) {
            textarea.style.height = 'auto'; // Restablece la altura para recalcular
            textarea.style.height = (textarea.scrollHeight) + 'px'; // Ajusta a la altura del contenido
        }

        // Ejecutar autoExpand al cargar la página para el contenido preexistente (old('content'))
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('noteContent');
            if (textarea) {
                autoExpand(textarea);
            }
            // Opcional: Ocultar la tarjeta de nota si no hay errores de validación al cargar la página
            // Esto es útil si el usuario recarga la página pero no tenía errores de formulario.
            @if (!$errors->any() && !session('success') && !session('error'))
                document.getElementById('noteCreateCard').style.display = 'none';
            @endif
        });
    </script>
</body>
</html>