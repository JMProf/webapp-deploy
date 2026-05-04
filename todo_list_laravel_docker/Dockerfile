# 1. Partimos de una imagen oficial con PHP 8.2 y Apache
FROM php:8.2-apache

# 2. Instalamos extensiones que Laravel necesita para MySQL y archivos zip
RUN apt-get update && apt-get install -y libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql zip \
    && a2enmod rewrite

# 3. Descargamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Le decimos a Apache que la web está en la carpeta /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Copiamos nuestro código al contenedor
WORKDIR /var/www/html
COPY . .

# 6. Instalamos las dependencias de Laravel (la carpeta vendor)
RUN composer install --optimize-autoloader --no-dev

# 7. Damos permisos a Apache para que pueda escribir en caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Metemos nuestro script de arranque y le damos permisos
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# 9. Exponemos el puerto 80 (el estándar de la web)
EXPOSE 80

# 10. Cuando el contenedor despierte, ejecutará el script
CMD ["start.sh"]