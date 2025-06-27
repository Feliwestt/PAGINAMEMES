[db memes.txt](https://github.com/user-attachments/files/20869645/db.memes.txt)[db memes.txt](https://github.com/user-attachments/files/20869611/db.memes.txt)Ejecutar en base de datos 

XAMPP PORT 3306

CREATE DATABASE db_memes_indie;
USE db_memes_indie;


CREATE TABLE IF NOT EXISTS memes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    descripcion TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    tipo VARCHAR(20) NOT NULL DEFAULT 'imagen' -- 'imagen' o 'video'
);


CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meme_id INT NOT NULL,
    autor VARCHAR(100) NOT NULL,
    texto TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (meme_id) REFERENCES memes(id) ON DELETE CASCADE
);



DESCARGA EL ARCHIVO DE LA BASE DE DATOS PARA VERLO EN OTRO FORMATO

       CREATE DATABASE IF NOT EXISTS db_memes_indie DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        USE db_memes_indie;
        
        -- Tabla de memes
        CREATE TABLE IF NOT EXISTS memes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            imagen VARCHAR(255) NOT NULL,
            descripcion TEXT,
            fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
            tipo VARCHAR(20) NOT NULL DEFAULT 'imagen', -- 'imagen' o 'video'
            etiqueta VARCHAR(20) NOT NULL DEFAULT 'SFW' -- IA, NSFW, SFW, animales, politico
        );
        
        -- Tabla de comentarios
        CREATE TABLE IF NOT EXISTS comentarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            meme_id INT NOT NULL,
            autor VARCHAR(100) NOT NULL,
            texto TEXT NOT NULL,
            fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (meme_id) REFERENCES memes(id) ON DELETE CASCADE
        );





       




