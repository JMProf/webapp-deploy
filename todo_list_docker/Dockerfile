# 1. Usamos una imagen oficial de PHP con Apache ya configurado
FROM php:8.2-apache

# 2. Instalamos la extensión mysqli para que PHP pueda hablar con MySQL
# (Por defecto, la imagen de PHP es muy limpia y no la trae)
RUN docker-php-ext-install mysqli

# 3. Copiamos los archivos de nuestra carpeta actual al interior del contenedor
# La ruta /var/www/html es donde Apache busca la web por defecto en Linux
COPY . /var/www/html/

# Normalizamos rutas a tipo Linux
RUN sed -i 's/\r$//' /var/www/html/docker-entrypoint.sh

# 4. Exponemos el puerto 80 (el estándar de la web)
EXPOSE 80

# 5. Damos permisos de ejecución al script
RUN chmod +x /var/www/html/docker-entrypoint.sh

# 6. Comando para esperar a la DB e inyectar el SQL antes de lanzar Apache
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
