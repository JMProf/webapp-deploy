#!/bin/bash

echo "Esperando a que MySQL esté completamente listo..."

# Bucle: Mientras Artisan no pueda conectarse a la BD, hace una pausa de 2 segundos y repite
while ! php artisan migrate:status > /dev/null 2>&1; do
    echo "MySQL aún no responde. Reintentando en 2 segundos..."
    sleep 2
done

echo "¡MySQL ha arrancado correctamente!"

echo "Ejecutando migraciones y plantando semillas..."
php artisan migrate --seed --force

echo "Iniciando Apache..."
apache2-foreground