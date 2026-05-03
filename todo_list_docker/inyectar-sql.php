<?php
// Incluimos la conexión a la base de datos
require_once 'config.php';

// 1. Esperamos a que el contenedor de MySQL esté listo
$conn = null;
while ($conn === null) {
    try {
        $conn = @new mysqli($host, $user, $pass);
        if ($conn->connect_error) {
            $conn = null;
        }
    } catch (Exception $e) {
        $conn = null;
    }
    
    if ($conn === null) {
        echo "Base de datos no disponible todavía - esperando...\n";
        sleep(2);
    }
}

echo "¡Base de datos detectada! Inyectando SQL...\n";

// 2. Creamos la DB si no existe y la seleccionamos
$conn->query("CREATE DATABASE IF NOT EXISTS `$db`");
$conn->select_db($db);

// 3. Inyectamos tu archivo datos.sql intacto
$sql = file_get_contents(__DIR__ . '/datos.sql');
if ($conn->multi_query($sql)) {
    // Vaciamos la memoria para que no haya errores de "Commands out of sync"
    do { if ($res = $conn->store_result()) $res->free(); } while ($conn->more_results() && $conn->next_result());
    echo "SQL inyectado con éxito.\n";
} else {
    echo "Error inyectando SQL: " . $conn->error . "\n";
}
?>
