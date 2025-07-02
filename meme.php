<?php
include 'includes/conexion.php';
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
    <link rel="stylesheet" href="css/meme_comentario.css">
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