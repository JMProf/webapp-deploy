<?php
$host = "localhost";
$user = "user_db";
$pass = "My-secure-password1"; // CAMBIAR EN PRODUCCIÓN, NO USAR SÍMBOLO $
$db   = "todo_list_db";

// Crear la conexión
$conn = new mysqli($host, $user, $pass, $db);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
