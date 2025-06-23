<?php
include 'conexion.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Meme no encontrado.');
}
$id = intval($_GET['id']);
// Obtener el meme
$meme = $conexion->query("SELECT * FROM memes WHERE id = $id")->fetch_assoc();
if (!$meme) {
    die('Meme no encontrado.');
}
// Obtener comentarios
$comentarios = $conexion->query("SELECT * FROM comentarios WHERE meme_id = $id ORDER BY fecha ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($meme['titulo']) ?> - Indie Memes</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
            color: #f1f1f1;
            margin: 0;
            min-height: 100vh;
        }
        .detalle-meme {
            background: #23272f;
            border-radius: 14px;
            box-shadow: 0 4px 24px #000a;
            max-width: 700px;
            margin: 40px auto 24px auto;
            padding: 32px 28px 18px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .detalle-meme h2 {
            color: #a7bfff;
            margin-bottom: 10px;
            text-align: center;
        }
        .detalle-meme small {
            color: #7f8fa6;
            margin-bottom: 18px;
        }
        .detalle-meme img, .detalle-meme video {
            width: 100%;
            max-width: 600px;
            height: 440px;
            object-fit: contain;
            background: rgb(20,22,26);
            border-radius: 10px;
            margin-bottom: 18px;
            box-shadow: 0 2px 12px #0006;
            display: block;
        }
        .detalle-meme p {
            color: #b0b8d1;
            font-size: 1.1em;
            margin-bottom: 18px;
            text-align: center;
        }
        .comentarios-section {
            background: #1e222b;
            border-radius: 10px;
            box-shadow: 0 2px 12px #0008;
            max-width: 700px;
            margin: 0 auto 40px auto;
            padding: 24px 20px 18px 20px;
        }
        .comentarios-section h3 {
            color: #a7bfff;
            margin-top: 0;
        }
        .comentario {
            border-bottom: 1px solid #333a;
            padding: 12px 0 8px 0;
        }
        .comentario:last-child {
            border-bottom: none;
        }
        .comentario-autor {
            color: #7fffd4;
            font-weight: bold;
        }
        .comentario-fecha {
            color: #7f8fa6;
            font-size: 0.9em;
            margin-left: 8px;
        }
        .comentario-texto {
            color: #f1f1f1;
            margin: 4px 0 0 0;
        }
        .form-comentario {
            margin-top: 18px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .form-comentario input, .form-comentario textarea {
            background: #23272f;
            color: #f1f1f1;
            border: none;
            border-radius: 6px;
            padding: 8px;
            font-size: 1em;
        }
        .form-comentario button {
            background: linear-gradient(90deg, #7f53ac 0%, #647dee 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .form-comentario button:hover {
            background: linear-gradient(90deg, #647dee 0%, #7f53ac 100%);
        }
        .volver-link {
            display: inline-block;
            margin: 24px auto 0 24px;
            color: #a7bfff;
            text-decoration: none;
            font-weight: 500;
            font-size: 1.1em;
            transition: color 0.2s;
        }
        .volver-link:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <a class="volver-link" href="index.php">&larr; Volver a la galería</a>
    <div class="detalle-meme">
        <h2><?= htmlspecialchars($meme['titulo']) ?></h2>
        <small><?= $meme['fecha'] ?></small>
        <?php if ($meme['tipo'] === 'video'): ?>
            <video controls>
                <source src="imagenes/<?= htmlspecialchars($meme['imagen']) ?>" type="video/mp4">
                Tu navegador no soporta el video.
            </video>
        <?php else: ?>
            <img src="imagenes/<?= htmlspecialchars($meme['imagen']) ?>" alt="Meme" />
        <?php endif; ?>
        <p><?= nl2br(htmlspecialchars($meme['descripcion'])) ?></p>
    </div>
    <div class="comentarios-section">
        <h3>Comentarios</h3>
        <?php if ($comentarios->num_rows > 0): ?>
            <?php while($coment = $comentarios->fetch_assoc()): ?>
                <div class="comentario">
                    <span class="comentario-autor"><?= htmlspecialchars($coment['autor']) ?></span>
                    <span class="comentario-fecha"><?= $coment['fecha'] ?></span>
                    <div class="comentario-texto"><?= nl2br(htmlspecialchars($coment['texto'])) ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay comentarios aún. ¡Sé el primero en comentar!</p>
        <?php endif; ?>
        <form class="form-comentario" action="procesar_comentario.php" method="POST">
            <input type="hidden" name="meme_id" value="<?= $meme['id'] ?>">
            <input type="text" name="autor" placeholder="Tu nombre" maxlength="100" required>
            <textarea name="texto" rows="3" placeholder="Escribe tu comentario..." required></textarea>
            <button type="submit">Comentar</button>
        </form>
    </div>
</body>
</html> 