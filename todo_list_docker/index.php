<?php
// Incluimos la conexión a la base de datos
require_once 'config.php';

// --- Lógica para AÑADIR tarea ---
if (isset($_POST['nueva_tarea']) && !empty($_POST['nueva_tarea'])) {
    $tarea = $conn->real_escape_string($_POST['nueva_tarea']);
    $conn->query("INSERT INTO tareas (nombre) VALUES ('$tarea')");
    
    // PATRÓN PRG: Redirigimos a la misma página mediante GET y cortamos la ejecución
    header("Location: index.php");
    exit;
}

// --- Lógica para BORRAR tarea ---
if (isset($_GET['borrar'])) {
    $id = (int)$_GET['borrar'];
    $conn->query("DELETE FROM tareas WHERE id = $id");
    
    // PATRÓN PRG: Redirigimos para limpiar el "?borrar=id" de la URL
    header("Location: index.php");
    exit;
}

// Obtener las tareas
$resultado = $conn->query("SELECT * FROM tareas");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi App Dockerizada</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; padding: 50px; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { color: #333; text-align: center; }
        form { display: flex; gap: 10px; margin-bottom: 20px; }
        input[type="text"] { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        ul { list-style: none; padding: 0; }
        li { background: #eee; margin-bottom: 5px; padding: 10px; display: flex; justify-content: space-between; align-items: center; border-radius: 4px; }
        .btn-borrar { background: #dc3545; color: white; text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; }
    </style>
</head>
<body>

<div class="container">
    <h2>Lista de Tareas</h2>
    
    <form method="POST">
        <input type="text" name="nueva_tarea" placeholder="Nueva tarea..." required>
        <button type="submit">Añadir</button>
    </form>

    <ul>
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while($row = $resultado->fetch_assoc()): ?>
                <li>
                    <?php echo htmlspecialchars($row['nombre']); ?>
                    <a href="?borrar=<?php echo $row['id']; ?>" class="btn-borrar">Borrar</a>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <li>No hay tareas pendientes.</li>
        <?php endif; ?>
    </ul>
</div>

</body>
</html>
