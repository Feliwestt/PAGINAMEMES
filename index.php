<?php
include 'conexion.php';
$resultado = $conexion->query("SELECT * FROM memes .ORDER BY fecha DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Galería de Memes</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; }
        .meme { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #ccc; margin: 20px auto; padding: 20px; width: 400px; }
        .meme img { max-width: 100%; border-radius: 8px; }
        .meme h2 { margin: 0 0 10px 0; }
        .meme p { color: #555; }
        .meme small { color: #888; }
    </style>
</head>
<body>
    <?php if (isset($_GET['mensaje'])): ?>
        <div style="max-width:400px;margin:20px auto;background:#e0ffe0;padding:10px;border-radius:6px;color:#256029;text-align:center;">
            <?= htmlspecialchars($_GET['mensaje']) ?>
        </div>
    <?php endif; ?>
    <h1 style="text-align:center;">Galería de Memes</h1>
    <h2 style="text-align:center;">Subir un nuevo meme</h2>
    <form action="subir_meme.php" method="POST" enctype="multipart/form-data" style="max-width:400px;margin:0 auto 30px auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px #ccc;">
        <label for="titulo">Título:</label><br>
        <input type="text" name="titulo" id="titulo" required style="width:100%;margin-bottom:10px;"><br>
        <label for="descripcion">Descripción:</label><br>
        <textarea name="descripcion" id="descripcion" rows="3" required style="width:100%;margin-bottom:10px;"></textarea><br>
        <label for="imagen">Imagen:</label><br>
        <input type="file" name="imagen" id="imagen" accept="image/*" required style="margin-bottom:10px;"><br>
        <button type="submit" style="width:100%;padding:10px;background:#4caf50;color:#fff;border:none;border-radius:4px;">Subir Meme</button>
    </form>
    <?php while ($meme = $resultado->fetch_assoc()): ?>
        <div class="meme">
            <h2><?= htmlspecialchars($meme['titulo']) ?></h2>
            <img src="imagenes/<?= htmlspecialchars($meme['imagen']) ?>" alt="Meme">
            <p><?= nl2br(htmlspecialchars($meme['descripcion'])) ?></p>
            <small><?= $meme['fecha'] ?></small>
        </div>
    <?php endwhile; ?>
</body>
</html>
