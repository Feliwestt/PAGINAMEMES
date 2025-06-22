Ejecutar en base de datos 

XAMPP PORT 3306

CREATE DATABASE db_memes_indie;
USE db_memes_indie;
CREATE TABLE memes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    imagen VARCHAR(255),
    descripcion TEXT,
    fecha DATETIME
);


DESCARGA EL ARCHIVO DE LA BASE DE DATOS PARA VERLO EN OTRO FORMATO
[BD.txt](https://github.com/user-attachments/files/20850720/BD.txt)
        
