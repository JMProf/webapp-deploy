<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi App Laravel</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; padding: 50px; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { color: #333; text-align: center; }
        form { display: flex; gap: 10px; margin-bottom: 20px; }
        input[type="text"] { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        ul { list-style: none; padding: 0; }
        li { background: #eee; margin-bottom: 5px; padding: 10px; display: flex; justify-content: space-between; align-items: center; border-radius: 4px; }
        .btn-borrar { background: #dc3545; color: white; text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; cursor: pointer; border: none; }
    </style>
</head>
<body>

<div class="container">
    <h2>Lista de Tareas</h2>
    
    <form action="{{ route('tareas.store') }}" method="POST">
        @csrf
        <input type="text" name="nueva_tarea" placeholder="Nueva tarea..." required>
        <button type="submit">Añadir</button>
    </form>

    <ul>
        @if ($tareas->count() > 0)
            @foreach ($tareas as $tarea)
                <li>
                    {{ $tarea->nombre }}
                    
                    <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-borrar">Borrar</button>
                    </form>
                </li>
            @endforeach
        @else
            <li>No hay tareas pendientes.</li>
        @endif
    </ul>
</div>

</body>
</html>