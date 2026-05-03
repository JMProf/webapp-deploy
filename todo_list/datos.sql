USE todo_list_db;

CREATE TABLE IF NOT EXISTS tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);

INSERT INTO tareas (nombre) VALUES ('Aprender Docker'), ('Configurar el Compose'), ('Aprobar el examen');
