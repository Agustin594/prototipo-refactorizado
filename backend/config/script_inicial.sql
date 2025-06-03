-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS students_db
CHARACTER SET utf8 
COLLATE utf8_unicode_ci;

-- Crear usuario de la base de datos
CREATE USER 'students_user'@'localhost' IDENTIFIED BY '12345';

-- Otorgar todos los permisos sobre la base de datos
GRANT ALL PRIVILEGES ON students_db.* TO 'students_user'@'localhost';

-- Aplicar los cambios en los permisos​
FLUSH PRIVILEGES;​

-- Usar la base de datos​
USE students_db;

-- Crear la tabla students
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    age INT NOT NULL
) ENGINE=INNODB;

-- Insertar algunos datos de prueba
INSERT INTO students (first_name, last_name, email, age) VALUES
('Ana', 'García', 'ana@example.com', 21),
('Lucas', 'Torres', 'lucas@example.com', 24),
('Marina', 'Díaz', 'marina@example.com', 22);

-- Crear la tabla subjects
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    teacher VARCHAR(100) NOT NULL
) ENGINE=INNODB;

-- Insertar algunos datos de prueba
INSERT INTO subjects (subject_name, teacher) VALUES
('Programación A', 'Ivonne'),
('Programación B', 'Sandra'),
('Tecnologías Informáticas B', 'Genin');

-- Crear la tabla academic_history
CREATE TABLE IF NOT EXISTS academic_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    approved BOOLEAN DEFAULT FALSE,
    UNIQUE (student_id,subject_id)
    FOREIGN KEY(student_id) references students(id),
    FOREIGN KEY(subject_id) references subjects(id)
) ENGINE=INNODB;

-- Insertar algunos datos de prueba
INSERT INTO academic_history (student_id, subject_id, approved) VALUES
(1, 1, 1),
(1, 2, 1),
(1, 3, 1),
(2, 1, 1),
(2, 2, 0),
(2, 3, 0),
(3, 1, 0),
(3, 2, 0),
(3, 3, 0),



--VOLVER TODO A CERO, BORRAR BASE DE DATOS Y USUARIO
--REVOKE ALL PRIVILEGES, GRANT OPTION FROM 'students_user'@'localhost';
--DROP USER 'students_user'@'localhost';
--DROP DATABASE students_db;