# webapp-deploy

Esta práctica muestra dos formas de desplegar una aplicación web sencilla en Ubuntu Server. Una utilizando el entorno LAMP (similar a XAMPP en Windows) y otra creando y lanzando la aplicación como un contenedor en Docker.

# Índice

- [Aplicación básica: Apache + PHP + MySQL](#aplicación-básica-apache--php--mysql)
- [Aplicación básica en Docker](#aplicación-básica-en-docker)

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
sudo cp webapp-deploy-main/todo_list /var/www/html/
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

### 3. Descargar el repositorio

```Bash
wget https://github.com/JMProf/webapp-deploy/archive/main.zip
unzip main.zip
rm main.zip
cd webapp-deploy-main/todo_list_docker
```

### 4. Crear cuenta en Docker Hub

Visita [https://app.docker.com/signup](https://app.docker.com/signup) y crea una cuenta para subir tus contenedores.

### 5. Crear el contenedor

Lee detenidamente el fichero `Dockerfile` para comprobar los parámetros con los que se va a crear tu contenedor.

```Bash
docker build -t tu_usuario/todo_list_docker:v1 -t tu_usuario/todo_list_docker:latest .
```

### 6. Subir el contenedor a Docker Hub

```Bash
docker push tu_usuario/todo_list_docker:v1
docker push tu_usuario/todo_list_docker:latest
```

### 7. Desplegar `docker-dompose`

```Bash
docker compose up -d
```

Ya podrás visitar la web en `http://IP_ADDRESS:8080`.