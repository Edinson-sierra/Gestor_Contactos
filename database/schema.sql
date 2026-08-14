/*

 Contact Manager Database

 Estructura de la base de datos.
Compatible con MySQL 8 (XAMPP).

*/

CREATE DATABASE IF NOT EXISTS contact_manager
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE contact_manager;

--Tabla: contacts


CREATE TABLE IF NOT EXISTS contacts (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(100) NOT NULL,

    email VARCHAR(150) NOT NULL UNIQUE,

    Telefono VARCHAR(20) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);