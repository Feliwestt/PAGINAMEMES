

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
           etiqueta VARCHAR(20) NOT NULL DEFAULT 'SFW' -- IA, NSFW, SFW, animales, politico, anime, gaming
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
       
       

ADMINISTRADOR
---------------------------------------

----crear la tabla admins

    CREATE TABLE admins (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        username      VARCHAR(50)  NOT NULL UNIQUE,
        contra_hash VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
       

---- Luego de esto abiri la pagina " generar_hash.php " para crear un usuario, pueden cambiarle el nombre si quieren


---

---tabla log_Actividad, esta se encarga de registrar los datos para crear un informe de flujo

       CREATE TABLE log_actividad (
           id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
           tipo          ENUM('meme_creado','comentario_creado','reporte_creado',
                              'meme_borrado','comentario_borrado') NOT NULL,
           referencia_id BIGINT UNSIGNED NULL,    -- ID del meme o comentario relacionado
           usuario       VARCHAR(80) NULL,        -- opcional: quién realizó la acción
           fecha         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
           detalles      TEXT NULL                -- opcional: descripción adicional
       );



----Triggers, estos se van a activar cuando ocurra alguna de las acciones mencionadas





        -- ══════════════════════════════════════════════════════════
        --  Meme creado
        -- ══════════════════════════════════════════════════════════
        DROP TRIGGER IF EXISTS trg_meme_ai;
        DELIMITER $$
        CREATE TRIGGER trg_meme_ai
        AFTER INSERT ON memes
        FOR EACH ROW
        BEGIN
        INSERT INTO log_actividad (
            tipo, referencia_id, usuario, detalles
        ) VALUES (
            'meme_creado',
            NEW.id,
            NULL,                                   -- no tienes columna autor en memes
            CONCAT('Título: ', NEW.titulo)
        );
        END$$
        DELIMITER ;

        -- ══════════════════════════════════════════════════════════
        --  Meme borrado
        -- ══════════════════════════════════════════════════════════
        DROP TRIGGER IF EXISTS trg_meme_bd;
        DELIMITER $$
        CREATE TRIGGER trg_meme_bd
        BEFORE DELETE ON memes
        FOR EACH ROW
        BEGIN
        INSERT INTO log_actividad (
            tipo, referencia_id, usuario, detalles
        ) VALUES (
            'meme_borrado',
            OLD.id,
            NULL,
            CONCAT('Título: ', OLD.titulo)
        );
        END$$
        DELIMITER ;

        -- ══════════════════════════════════════════════════════════
        --  Comentario creado
        -- ══════════════════════════════════════════════════════════
        DROP TRIGGER IF EXISTS trg_coment_ai;
        DELIMITER $$
        CREATE TRIGGER trg_coment_ai
        AFTER INSERT ON comentarios
        FOR EACH ROW
        BEGIN
        INSERT INTO log_actividad (
            tipo, referencia_id, usuario, detalles
        ) VALUES (
            'comentario_creado',
            NEW.id,
            NEW.autor,
            CONCAT('meme_id=', NEW.meme_id)
        );
        END$$
        DELIMITER ;

        -- ══════════════════════════════════════════════════════════
        --  Comentario borrado
        -- ══════════════════════════════════════════════════════════
        DROP TRIGGER IF EXISTS trg_coment_bd;
        DELIMITER $$
        CREATE TRIGGER trg_coment_bd
        BEFORE DELETE ON comentarios
        FOR EACH ROW
        BEGIN
        INSERT INTO log_actividad (
            tipo, referencia_id, usuario, detalles
        ) VALUES (
            'comentario_borrado',
            OLD.id,
            OLD.autor,
            CONCAT('meme_id=', OLD.meme_id)
        );
        END$$
        DELIMITER ;

        -- ══════════════════════════════════════════════════════════
        --  Reporte creado
        -- ══════════════════════════════════════════════════════════
        DROP TRIGGER IF EXISTS trg_reporte_ai;
        DELIMITER $$
        CREATE TRIGGER trg_reporte_ai
        AFTER INSERT ON reportes
        FOR EACH ROW
        BEGIN
        INSERT INTO log_actividad (
            tipo, referencia_id, usuario, detalles
        ) VALUES (
            'reporte_creado',
            NEW.id,
            NULL,   -- no se almacena usuario en reportes
            CONCAT('meme_id=', NEW.meme_id, ', motivo=', NEW.motivo)
        );
        END$$
        DELIMITER ;


