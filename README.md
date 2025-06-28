

XAMPP PORT 3306




COPIA ESTO Y PEGALO EN SQL DE TU PHPMYADMIN

       -- Crear la base de datos
       CREATE DATABASE db_memes_indie;
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
       
       -- Tabla de reportes
       CREATE TABLE IF NOT EXISTS reportes (
           id INT AUTO_INCREMENT PRIMARY KEY,
           meme_id INT NOT NULL,
           motivo VARCHAR(255) NOT NULL,
           detalles TEXT,
           fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
           FOREIGN KEY (meme_id) REFERENCES memes(id) ON DELETE CASCADE
       );
       
       



       




