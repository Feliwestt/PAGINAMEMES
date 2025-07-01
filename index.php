<?php
session_start();
include 'includes/conexion.php';
$filtro = $conexion->real_escape_string($_GET['filtro_etiqueta'] ?? '');
if ($filtro) {
    $resultado = $conexion->query("SELECT * FROM memes WHERE etiqueta = '$filtro' ORDER BY fecha DESC");
} else {
    $resultado = $conexion->query("SELECT * FROM memes ORDER BY fecha DESC");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Galería de Memes</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header class="header-indie">
        <div style="flex:1;"></div>
        <h1 id="logo-indie" style="margin:0;position:absolute;left:50%;transform:translateX(-50%);">INDIE MEMES</h1>
        <nav style="margin-left:auto;display:flex;align-items:center; margin-right:70px;">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <span style="color:#a7bfff;font-weight:500;margin-right:18px;">
                    ¡Hola, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>!
                </span>
                <a href="usuarios/logout.php" class="btn-header">Cerrar sesión</a>
            <?php else: ?>
                <a href="usuarios/registro.php" class="btn-header">Registrarse</a>
                <a href="usuarios/login.php" class="btn-header" style="margin-left:10px;">Iniciar sesión</a>
            <?php endif; ?>
        </nav>
    </header>
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="mensaje">
            <?= htmlspecialchars($_GET['mensaje']) ?>
        </div>
    <?php endif; ?>
    <form method="GET" action="index.php" style="text-align:center; margin-bottom: 24px;">
        <label for="filtro_etiqueta" style="color:#a7bfff;">Filtrar por etiqueta:</label>
        <select name="filtro_etiqueta" id="filtro_etiqueta" onchange="this.form.submit()">
            <option value="">Todas</option>
            <option value="IA" <?= $filtro==='IA'?'selected':'' ?>>IA</option>
            <option value="NSFW" <?= $filtro==='NSFW'?'selected':'' ?>>NSFW</option>
            <option value="SFW" <?= $filtro==='SFW'?'selected':'' ?>>SFW</option>
            <option value="animales" <?= $filtro==='animales'?'selected':'' ?>>animales</option>
            <option value="politico" <?= $filtro==='politico'?'selected':'' ?>>politico</option>
            <option value="anime" <?= $filtro==='anime'?'selected':'' ?>>anime</option>
            <option value="gaming" <?= $filtro==='gaming'?'selected':'' ?>>gaming</option>
        </select>
    </form>
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <button class="btn-flotante" id="abrirModal">Subir Meme</button>
    <?php else: ?>
        <div class="btn-flotante" style="background:#232946;color:#a7bfff;cursor:default;pointer-events:none;box-shadow:0 2px 8px #0004;">
            Necesitas una cuenta para subir memes
        </div>
    <?php endif; ?>
    <div class="modal-fondo" id="modalFondo"></div>
    <div class="modal-formulario" id="modalFormulario">
        <button class="cerrar-modal" id="cerrarModal" title="Cerrar">&times;</button>
        <h2 style="text-align:center;">Subir un nuevo meme</h2>
        <form action="subir_meme.php" method="POST" enctype="multipart/form-data">
            <label for="titulo">Título:</label><br>
            <input type="text" name="titulo" id="titulo" required><br>
            <label for="descripcion">Descripción:</label><br>
            <textarea name="descripcion" id="descripcion" rows="3" required></textarea><br>
            <label for="etiqueta">Etiqueta:</label><br>
            <select name="etiqueta" id="etiqueta" required>
                <option value="IA">IA</option>
                <option value="NSFW">NSFW</option>
                <option value="SFW" selected>SFW</option>
                <option value="animales">animales</option>
                <option value="politico">politico</option>
                <option value="anime">anime</option>
                <option value="gaming">gaming</option>
            </select><br>
            <label for="imagen">Imagen o Video(hasta 8mb):</label><br>
            <input type="file" name="imagen" id="imagen" accept="image/*,video/mp4,video/webm,video/ogg" required><br>
            <button type="submit">Subir Meme</button>
        </form>
    </div>
    <div class="galeria">
    <?php while ($meme = $resultado->fetch_assoc()): ?>
        <div class="meme">
            <button class="opciones-btn" title="Opciones" onclick="toggleMenuOpciones(event, 'menu-<?= $meme['id'] ?>')">
                &#8942;
            </button>
            <div class="menu-opciones" id="menu-<?= $meme['id'] ?>">
                <button onclick="descargarMeme('imagenes/<?= htmlspecialchars($meme['imagen']) ?>', '<?= addslashes(htmlspecialchars($meme['titulo'])) ?>')">Descargar</button>
                <button onclick="abrirModalReporte(<?= $meme['id'] ?>)">Reportar</button>
            </div>
            <div style="display:flex;align-items:center;width:100%;margin-bottom:8px;">
                <div style="flex:1;text-align:left;color:#7fffd4;font-weight:600;min-width:90px;">👤 <?= htmlspecialchars($meme['autor'] ?? 'Anónimo') ?></div>
                <div style="flex:2;display:flex;justify-content:center;">
                    <h2 style="margin:0;text-align:center;"><?= htmlspecialchars($meme['titulo']) ?></h2>
                </div>
                <div style="flex:1;"></div>
            </div>
            <span class="etiqueta-meme"><?= htmlspecialchars($meme['etiqueta']) ?></span>
            
            <?php if ($meme['tipo'] === 'video'): ?>
                <video controls style="width:100%;height:440px;background:rgb(20,22,26);border-radius:10px;margin-bottom:18px;box-shadow:0 2px 12px #0006;object-fit:contain;display:block;">
                    <source src="imagenes/<?= htmlspecialchars($meme['imagen']) ?>" type="video/mp4">
                    Tu navegador no soporta el video.
                </video>
            <?php else: ?>
                <img class="meme-img-ampliable" src="imagenes/<?= htmlspecialchars($meme['imagen']) ?>" alt="Meme" style="cursor:default;" />
            <?php endif; ?>
            <p><?= nl2br(htmlspecialchars($meme['descripcion'])) ?></p>
            <small><?= $meme['fecha'] ?></small>
            <a class="comentario-link" href="meme.php?id=<?= $meme['id'] ?>" title="Comentar">
                <span style="display:inline-flex;align-items:center;gap:6px;">
                    <svg width="24" height="24" fill="none" stroke="#a7bfff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span style="color:#a7bfff;font-weight:500;">Comentar</span>
                </span>
            </a>
        </div>
    <?php endwhile; ?>
    </div>
    <div class="modal-img-fondo" id="modalImgFondo"></div>
    <div class="modal-img" id="modalImg">
        <button class="cerrar-modal-img" id="cerrarModalImg" title="Cerrar">&times;</button>
        <img id="imgModalGrande" src="" alt="Meme grande">
    </div>
    <!-- Modal de reporte -->
    <div class="modal-fondo" id="modalFondoReporte" style="display:none;"></div>
    <div class="modal-formulario" id="modalReporte" style="display:none;">
        <button class="cerrar-modal" id="cerrarModalReporte" title="Cerrar">&times;</button>
        <h2 style="text-align:center;">Reportar Meme</h2>
        <form action="reportar_meme.php" method="POST">
            <input type="hidden" name="meme_id" id="reporteMemeId">
            <label for="motivo">Motivo:</label><br>
            <select name="motivo" id="motivo" required style="width:100%;margin-bottom:10px;">
                <option value="">Selecciona un motivo</option>
                <option value="Ofensivo">Ofensivo</option>
                <option value="Grotesco">Grotesco</option>
                <option value="NSFW no etiquetado">NSFW no etiquetado</option>
                <option value="Otro">Otro</option>
            </select><br>
            <label for="detalles">Detalles (opcional):</label><br>
            <textarea name="detalles" id="detalles" rows="3" style="width:100%;margin-bottom:10px;"></textarea><br>
            <button type="submit" style="width:100%;padding:10px;background:#e53935;color:#fff;border:none;border-radius:4px;">Enviar Reporte</button>
        </form>
    </div>

    <script src="js/funcion_index.js"></script>
    <script src="js/scroll_memes.js"></script>
    
</body>
</html>
