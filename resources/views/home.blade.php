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
    </style>
</head>
<body>
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="#">Todas las Notas</a>
        <div class="sidebar-heading">ETIQUETAS</div>
        <a href="#">#Trabajo</a>
        <a href="#">#Personal</a>
        <a href="#">#Ideas</a>
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

        <section class="notes-list">
            <h3>Tus Notas</h3>
            <div class="note-item">
                <h4>Título de la Nota Importante <i class="fas fa-star" style="color: gold;"></i></h4>
                <p>Este es el contenido de una nota. Puede ser largo o corto.</p>
                <div class="note-tags">
                    <span>#Etiqueta1</span>
                    <span>#Etiqueta2</span>
                </div>
            </div>
            <div class="note-item">
                <h4>Otra Nota Normal</h4>
                <p>Aquí va más contenido de una nota cualquiera.</p>
                <div class="note-tags">
                    <span>#General</span>
                </div>
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
        });
    </script>
</body>
</html>