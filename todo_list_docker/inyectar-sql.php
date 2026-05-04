<?php
// Incluimos la conexión a la base de datos
require_once 'config.php';

echo "¡Base de datos detectada! Inyectando SQL...\n";

// Creamos la DB si no existe y la seleccionamos
$conn->query("CREATE DATABASE IF NOT EXISTS `$db`");
$conn->select_db($db);

// Inyectamos tu archivo datos.sql intacto
$sql = file_get_contents(__DIR__ . '/datos.sql');
if ($conn->multi_query($sql)) {
    // Vaciamos la memoria para que no haya errores de "Commands out of sync"
    do { if ($res = $conn->store_result()) $res->free(); } while ($conn->more_results() && $conn->next_result());
    echo "SQL inyectado con éxito.\n";
} else {
    echo "Error inyectando SQL: " . $conn->error . "\n";
}
?>
