#!/bin/bash
echo "Esperando 10 segundos a que MySQL arranque..."
sleep 10

echo "Ejecutando migraciones y plantando semillas..."
php artisan migrate --seed --force

echo "Iniciando Apache..."
apache2-foreground