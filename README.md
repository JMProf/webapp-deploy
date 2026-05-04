# webapp-deploy

Esta práctica muestra dos formas de desplegar una aplicación web sencilla en Ubuntu Server. Una utilizando el entorno LAMP (similar a XAMPP en Windows) y otra creando y lanzando la aplicación como un contenedor en Docker.

# Índice

- [Aplicación básica: Apache + PHP + MySQL](#aplicación-básica-apache--php--mysql)
- [Aplicación básica en Docker](#aplicación-básica-en-docker)
- [Aplicación básica con Laravel](#aplicación-básica-con-laravel)
- [Aplicación básica con Laravel en Docker](#aplicación-básica-con-laravel-en-docker)

# Aplicación básica: Apache + PHP + MySQL

Para esta parte utilizaremos los ficheros de la carpeta `todo_list`.

### 1. Instalar el software necesario para lanzar la aplicación, que es el mismo que se utiliza con XAMPP: Apache, PHP y MySQL

```Bash
sudo apt update
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql -y
```

### 2. Configurar MySQL de forma segura

```Bash
sudo mysql_secure_installation
```

Podemos responder afirmativamente a todo y escoger el filtro de contraseñas más fuerte.

### 3. Descargar el repositorio

```Bash
wget https://github.com/JMProf/webapp-deploy/archive/main.zip
unzip main.zip
rm main.zip
```

### 4. Copiar la carpeta con la aplicación al directorio de Apache

```Bash
sudo cp -r webapp-deploy-main/todo_list /var/www/html/
cd /var/www/html/todo_list/
```

### 5. Ajustar permisos para el servidor web

```Bash
sudo chown -R www-data:www-data /var/www/html/todo_list
sudo chmod -R 755 /var/www/html/todo_list
```

### 6. Crear la base de datos y del usuario de la aplicación

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

### 7. Importar datos

```Bash
sudo mysql -u root todo_list_db < datos.sql
```

### 8. Reiniciar el servicio web

```Bash
sudo systemctl restart apache2
```

Ya podrás visitar la web en `http://IP_ADDRESS/todo_list`.

# Aplicación básica en Docker

Para esta parte utilizaremos los ficheros de la carpeta `todo_list_docker`.

### 1. Instalar Docker

```Bash
# Add Docker's official GPG key:
sudo apt update
sudo apt install ca-certificates curl
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

# Add the repository to Apt sources:
sudo tee /etc/apt/sources.list.d/docker.sources <<EOF
Types: deb
URIs: https://download.docker.com/linux/ubuntu
Suites: $(. /etc/os-release && echo "${UBUNTU_CODENAME:-$VERSION_CODENAME}")
Components: stable
Architectures: $(dpkg --print-architecture)
Signed-By: /etc/apt/keyrings/docker.asc
EOF

sudo apt update
```

```Bash
sudo apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

### 2. Obtener permisos sobre Docker

```Bash
sudo usermod -aG docker $USER
```

Para que se apliquen es necesario desconectarse y volver a conectarse al servidor.

### 3. Descargar el repositorio

```Bash
wget https://github.com/JMProf/webapp-deploy/archive/main.zip
unzip main.zip
rm main.zip
cd webapp-deploy-main/todo_list_docker
```

### 4. Crear cuenta en Docker Hub e iniciar sesión

Visita [https://app.docker.com/signup](https://app.docker.com/signup) y crea una cuenta para subir tus contenedores.

Cuando la tengas, inicia sesión en Ubuntu server.

```Bash
docker login
```

### 5. Crear el contenedor

Lee detenidamente el fichero `Dockerfile` para comprobar los parámetros con los que se va a crear tu contenedor. Cambia `tu_usuario` por tu nombre de usuario en Docker Hub.

```Bash
docker build -t tu_usuario/todo_list_docker:v1 -t tu_usuario/todo_list_docker:latest .
```

### 6. Subir el contenedor a Docker Hub

Cambia `tu_usuario` por tu nombre de usuario en Docker Hub.

```Bash
docker push tu_usuario/todo_list_docker:v1
docker push tu_usuario/todo_list_docker:latest
```

### 7. Desplegar `docker-dompose`

Edita el fichero `docker-compose.yml` y cambia `tu_usuario` por tu nombre de usuario en Docker Hub.

```Bash
docker compose up -d
```

Ya podrás visitar la web en `http://IP_ADDRESS:8080`.

# Aplicación básica con Laravel

Para esta parte utilizaremos los ficheros de la carpeta `todo_list_laravel`.

### 1. Instalar el software necesario para lanzar la aplicación, que es el mismo que se utiliza con XAMPP: Apache, PHP y MySQL

```Bash
sudo apt update
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql php-xml php-mbstring php-curl php-zip php-bcmath unzip curl -y
```

### 2. Descargar el repositorio

```Bash
wget https://github.com/JMProf/webapp-deploy/archive/main.zip
unzip main.zip
rm main.zip
```

### 4. Copiar la carpeta con la aplicación al directorio de Apache

```Bash
sudo cp -r webapp-deploy-main/todo_list_laravel /var/www/html/
```

### 5. Descargar composer

```Bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
cd /var/www/html/todo_list_laravel/
```

### 6. Instalar librerías

```Bash
sudo chown -R $USER:$USER /var/www/todo_list_laravel
composer install --optimize-autoloader --no-dev
```

### 7. Dar permisos de lectura a Laravel

```Bash
sudo chown -R www-data:www-data /var/www/html/todo_list_laravel/storage
sudo chown -R www-data:www-data /var/www/html/todo_list_laravel/bootstrap/cache
```

### 8. Crear base de datos y usuario

```Bash
sudo mysql
```

```SQL
CREATE DATABASE todo_list_laravel_db;
CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'my-secure-password';
GRANT ALL PRIVILEGES ON todo_list_laravel_db.* TO 'laravel_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 9. Importar base de datos

```Bash
php artisan migrate --seed
```

### 10. Permitir a Laravel modificar URLs en Apache

```Bash
sudo sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
```

### 11. Activar módulo rewrite y reiniciar

```Bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Ya podrás visitar la web en `http://IP_ADDRESS/todo_list_laravel/public`.

# Aplicación básica con Laravel en Docker

Para esta parte utilizaremos los ficheros de la carpeta `todo_list_laravel_docker`.

### 1. Instalar Docker

```Bash
# Add Docker's official GPG key:
sudo apt update
sudo apt install ca-certificates curl
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

# Add the repository to Apt sources:
sudo tee /etc/apt/sources.list.d/docker.sources <<EOF
Types: deb
URIs: https://download.docker.com/linux/ubuntu
Suites: $(. /etc/os-release && echo "${UBUNTU_CODENAME:-$VERSION_CODENAME}")
Components: stable
Architectures: $(dpkg --print-architecture)
Signed-By: /etc/apt/keyrings/docker.asc
EOF

sudo apt update
```

```Bash
sudo apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

### 2. Obtener permisos sobre Docker

```Bash
sudo usermod -aG docker $USER
```

Para que se apliquen es necesario desconectarse y volver a conectarse al servidor.

### 3. Descargar el repositorio

```Bash
wget https://github.com/JMProf/webapp-deploy/archive/main.zip
unzip main.zip
rm main.zip
cd webapp-deploy-main/todo_list_laravel_docker
```

### 4. Crear cuenta en Docker Hub e iniciar sesión

Visita [https://app.docker.com/signup](https://app.docker.com/signup) y crea una cuenta para subir tus contenedores.

Cuando la tengas, inicia sesión en Ubuntu server.

```Bash
docker login
```

### 5. Crear el contenedor

Lee detenidamente el fichero `Dockerfile` para comprobar los parámetros con los que se va a crear tu contenedor. Cambia `tu_usuario` por tu nombre de usuario en Docker Hub.

```Bash
docker build -t tu_usuario/todo_list_laravel_docker:v1 -t tu_usuario/todo_list_laravel_docker:latest .
```

### 6. Subir el contenedor a Docker Hub

Cambia `tu_usuario` por tu nombre de usuario en Docker Hub.

```Bash
docker push tu_usuario/todo_list_laravel_docker:v1
docker push tu_usuario/todo_list_laravel_docker:latest
```

### 7. Desplegar `docker-dompose`

Edita el fichero `docker-compose.yml` y cambia `tu_usuario` por tu nombre de usuario en Docker Hub.

```Bash
docker compose up -d
```

Ya podrás visitar la web en `http://IP_ADDRESS:8000`.