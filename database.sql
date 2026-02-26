-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS empleados_db;
USE empleados_db;

-- Tabla de Catálogo: Departamentos
CREATE TABLE departamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla de Catálogo: Puestos
CREATE TABLE puestos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla Principal: Empleados
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    departamento_id INT NOT NULL,
    puesto_id INT NOT NULL,
    estatus_activo TINYINT(1) DEFAULT 1, -- 1 = Activo, 0 = Baja Lógica
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (departamento_id) REFERENCES departamentos(id),
    FOREIGN KEY (puesto_id) REFERENCES puestos(id)
);
