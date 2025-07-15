<?php
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
    <title>IndieMemes</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header class="header-indie">
        <h1 id="logo-indie">INDIE MEMES</h1>
    </header>
    <div id="toast"></div>
    <script src="js/toast.js"></script>
    <script>
    <?php if (isset($_GET['mensaje'])): ?>
        window.addEventListener('DOMContentLoaded', function() {
            mostrarToast("<?= htmlspecialchars($_GET['mensaje']) ?>");
        });
    <?php endif; ?>
    </script>
    <!-- Filtro de etiquetas -->
    <form method="GET" action="index.php" class="form-filtro-etiquetas" id="formFiltroEtiquetas">
        <div class="filtro-etiquetas">
            <button type="submit" name="filtro_etiqueta" value="" class="<?= $filtro===''?'activo':'' ?>">Todas</button>
            <button type="submit" name="filtro_etiqueta" value="IA" class="<?= $filtro==='IA'?'activo':'' ?>">IA</button>
            <button type="submit" name="filtro_etiqueta" value="NSFW" class="<?= $filtro==='NSFW'?'activo':'' ?>">NSFW</button>
            <button type="submit" name="filtro_etiqueta" value="SFW" class="<?= $filtro==='SFW'?'activo':'' ?>">SFW</button>
            <button type="submit" name="filtro_etiqueta" value="CLIPS" class="<?= $filtro==='CLIPS'?'activo':'' ?>">CLIPS</button>
            <button type="submit" name="filtro_etiqueta" value="politico" class="<?= $filtro==='politico'?'activo':'' ?>">POLITICO</button>
            <button type="submit" name="filtro_etiqueta" value="anime" class="<?= $filtro==='anime'?'activo':'' ?>">ANIME</button>
            <button type="submit" name="filtro_etiqueta" value="gaming" class="<?= $filtro==='gaming'?'activo':'' ?>">GAMING</button>
            <div class="dropdown-etiquetas">
                <button type="button" id="btnDropdownEtiquetas" aria-haspopup="true" aria-expanded="false">&#9660;</button>
                <div class="menu-etiquetas-extra" id="menuEtiquetasExtra">
                    <button type="submit" name="filtro_etiqueta" value="GACHA" class="<?= $filtro==='GACHA'?'activo':'' ?>">GACHA</button>
                    <button type="submit" name="filtro_etiqueta" value="animales" class="<?= $filtro==='animales'?'activo':'' ?>">ANIMALES</button>
                    <button type="submit" name="filtro_etiqueta" value="NEWJEANS" class="<?= $filtro==='NEWJEANS'?'activo':'' ?>">NEWJEANS</button>
                    <button type="submit" name="filtro_etiqueta" value="HAPPY V" class="<?= $filtro==='HAPPY V'?'activo':'' ?>">HAPPY V</button>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Modal de subir meme -->
    <button class="btn-flotante" id="abrirModal">Subir Meme</button>
    <div class="contactanos">
    <button class="btn-contacto" id="abrirModalContacto">Contáctanos</button>
    </div>
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
                <option value="ANIMALES">ANIMALES</option>
                <option value="CLIPS">CLIPS</option>
                <option value="politico">POLITICO</option>
                <option value="anime">ANIME</option>
                <option value="gaming">GAMING</option>
                <option value="GACHA">GACHA</option>
                <option value="NEWJEANS">NEWJEANS</option>
                <option value="HAPPY V">HAPPY V</option>
            </select><br>
            <label for="imagen">Imagen o Video(hasta 8mb):</label><br>
            <input type="file" name="imagen" id="imagen" accept="image/*,video/mp4,video/webm,video/ogg" required><br>
            <button type="submit">Subir Meme</button>
        </form>
    </div>
    <!-- Galería de memes -->
    <h1 style="text-align:center;">Galería de Memes</h1>
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
            <h2><?= htmlspecialchars($meme['titulo']) ?></h2>
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
                    <span>Comentar</span>
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

    <!-- Modal de contacto -->
    <div class="modal-fondo" id="modalFondoContacto"></div>
    <div class="modal-formulario" id="modalContacto">
        <button class="cerrar-modal" id="cerrarModalContacto" title="Cerrar">&times;</button>
        <h2 style="text-align:center;">Formulario de Contacto</h2>
        <form action="enviar_correo.php" method="POST">
            <label for="nombre">Tu Nombre Completo:</label><br>
            <input type="text" name="nombre" id="nombre" required><br>

            <label for="correo">Tu Correo Electrónico:</label><br>
            <input type="email" name="correo" id="correo" required><br>

            <label for="mensaje">Mensaje:</label><br>
            <textarea name="mensaje" id="mensaje" rows="4" required></textarea><br>

            <button type="submit">Enviar</button>
        </form>
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
    <footer class="footer-flotante">
        <p>Todos los derechos reservados Indie Company</p>

    </footer>
    <script src="js/funcion_index.js"></script>
    <script src="js/scroll_galeria.js"></script>
    <script>
    // Mostrar/ocultar menú de etiquetas extra
    const btnDropdown = document.getElementById('btnDropdownEtiquetas');
    const menuExtra = document.getElementById('menuEtiquetasExtra');
    if(btnDropdown && menuExtra) {
        btnDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            menuExtra.classList.toggle('visible');
            btnDropdown.setAttribute('aria-expanded', menuExtra.classList.contains('visible'));
        });
        document.addEventListener('click', function(e) {
            if(menuExtra.classList.contains('visible')) {
                menuExtra.classList.remove('visible');
                btnDropdown.setAttribute('aria-expanded', 'false');
            }
        });
        menuExtra.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    </script>

        <script>
    document.getElementById('abrirModalContacto').addEventListener('click', function() {
        document.getElementById('modalFondoContacto').classList.add('activo');
        document.getElementById('modalContacto').classList.add('activo');
    });

    document.getElementById('cerrarModalContacto').addEventListener('click', function() {
        document.getElementById('modalFondoContacto').classList.remove('activo');
        document.getElementById('modalContacto').classList.remove('activo');
    });

    document.getElementById('modalFondoContacto').addEventListener('click', function() {
        document.getElementById('modalFondoContacto').classList.remove('activo');
        document.getElementById('modalContacto').classList.remove('activo');
    });
    </script>



</body>
</html>
