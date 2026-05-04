#!/bin/bash
set -e

echo "Ajustando permisos de Laravel..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R ug+rw /var/www/html/storage /var/www/html/bootstrap/cache

echo "Esperando a que MySQL acepte conexiones..."
until php -r '
try {
	new PDO(
		sprintf("mysql:host=%s;port=%s;dbname=%s", getenv("DB_HOST"), getenv("DB_PORT") ?: "3306", getenv("DB_DATABASE")),
		getenv("DB_USERNAME"),
		getenv("DB_PASSWORD"),
		[PDO::ATTR_TIMEOUT => 2]
	);
	exit(0);
} catch (Throwable $e) {
	fwrite(STDERR, $e->getMessage() . PHP_EOL);
	exit(1);
}
'; do
	echo "MySQL aun no esta listo; reintentando en 2 segundos..."
	sleep 2
done

echo "Ejecutando migraciones y plantando semillas..."
php artisan migrate --seed --force

echo "Iniciando Apache..."
exec apache2-foreground