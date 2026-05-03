#!/bin/bash
set -e

# Llamamos a nuestro script de PHP que nunca falla
php /var/www/html/inyectar-sql.php

echo "Arrancando Apache..."
exec apache2-foreground
