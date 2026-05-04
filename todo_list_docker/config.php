<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');

// Esperamos a que el contenedor de MySQL esté listo
$conn = null;
while ($conn === null) {
    try {
        $conn = @new mysqli($host, $user, $pass, $db);
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