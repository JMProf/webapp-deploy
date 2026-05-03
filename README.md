# webapp-deploy

# Aplicación básica: Apache + PHP + MySQL

Para esta parte utilizaremos los ficheros de la carpeta `todo_list`.

1. Instalación del software necesario para lanzar la aplicación, que es el mismo que se utiliza con XAMPP: Apache, PHP y MySQL.

```Bash
sudo apt update
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql -y
```

2. Configuración segura de MySQL.

```Bash
sudo mysql_secure_installation
```

Podemos responder afirmativamente a todo y escoger el filtro de contraseñas más fuerte.

3. Descarga del repositorio

```Bash
wget https://github.com/JMProf/webapp-deploy/archive/main.zip
unzip main.zip
rm main.zip
```

4. Copia de la carpeta con la aplicación al directorio de Apache.

```Bash
sudo cp webapp-deploy/todo_list /var/www/html/
cd /var/www/html/
```

5. Ajuste de permisos para el servidor web

```Bash
sudo chown -R www-data:www-data /var/www/html/todo_list
sudo chmod -R 755 /var/www/html/todo_list
```

6. Creación de la base de datos y del usuario de la aplicación

Recuerda revisar el fichero `config.php` y modificarlo si fuera necesario para ajustarlo con la base de datos y el usuario a crear.

```Bash
sudo mysql -u root
```

```SQL
CREATE DATABASE IF NOT EXISTS todo_list_db;
CREATE USER IF NOT EXISTS 'user_db'@'localhost' IDENTIFIED BY '&Ks*Ko!N78UeMax3';
GRANT ALL PRIVILEGES ON todo_list_db.* TO 'user_db'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

7. Importación de datos

```Bash
sudo mysql -u root todo_list_db < datos.sql
```

8. Reinicio del servicio web

```Bash
sudo systemctl restart apache2
```

Ya podrás visitar la web en `http://IP_ADDRESS/todo_list`.