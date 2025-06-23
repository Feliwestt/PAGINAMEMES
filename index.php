<?php
include 'conexion.php';
$resultado = $conexion->query("SELECT * FROM memes ORDER BY fecha DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Galería de Memes</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
            color: #f1f1f1;
            margin: 0;
            min-height: 100vh;
            padding-top: 70px;
        }
        h1, h2 {
            color: #a7bfff;
            text-shadow: 0 2px 8px #1118;
        }
        .mensaje {
            max-width: 400px;
            margin: 20px auto;
            background: #263238;
            padding: 10px;
            border-radius: 6px;
            color: #7fffd4;
            text-align: center;
            box-shadow: 0 2px 8px #0006;
        }
        /* Botón flotante arriba a la izquierda */
        .btn-flotante {
            position: fixed;
            top: 100px;
            left: 32px;
            z-index: 1001;
            background: linear-gradient(90deg, #7f53ac 0%, #647dee 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            font-weight: bold;
            padding: 12px 28px;
            cursor: pointer;
            box-shadow: 0 2px 8px #0004;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-flotante:hover {
            background: linear-gradient(90deg, #647dee 0%, #7f53ac 100%);
            transform: scale(1.05);
        }
        /* Fondo difuminado para el modal */
        .modal-fondo {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100vw; height: 100vh;
            background: rgba(30, 34, 43, 0.7);
            backdrop-filter: blur(6px);
            z-index: 1000;
        }
        .modal-fondo.activo {
            display: block;
        }
        /* Modal centrado */
        .modal-formulario {
            display: none;
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1002;
            background: #1e222b;
            padding: 32px 28px 18px 28px;
            border-radius: 14px;
            box-shadow: 0 8px 32px #000b;
            min-width: 320px;
            max-width: 95vw;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalIn 0.25s;
        }
        .modal-formulario.activo {
            display: block;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: translate(-50%, -60%) scale(0.95); }
            to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        }
        .modal-formulario label {
            color: #b0b8d1;
            font-weight: 500;
        }
        .modal-formulario input[type="text"],
        .modal-formulario textarea {
            width: 100%;
            margin-bottom: 12px;
            padding: 8px;
            border: none;
            border-radius: 5px;
            background: #23272f;
            color: #f1f1f1;
            font-size: 1em;
        }
        .modal-formulario input[type="file"] {
            margin-bottom: 12px;
            color: #b0b8d1;
        }
        .modal-formulario button[type="submit"] {
            width: 100%;
            padding: 10px;
            background: linear-gradient(90deg, #7f53ac 0%, #647dee 100%);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 2px 8px #0004;
        }
        .modal-formulario button[type="submit"]:hover {
            background: linear-gradient(90deg, #647dee 0%, #7f53ac 100%);
        }
        .cerrar-modal {
            position: absolute;
            top: 10px;
            right: 16px;
            background: none;
            border: none;
            color: #b0b8d1;
            font-size: 1.7em;
            cursor: pointer;
            transition: color 0.2s;
        }
        .cerrar-modal:hover {
            color: #fff;
        }
        .galeria {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 32px;
            max-width: 900px;
            margin: 40px auto 0 auto;
            padding: 0 20px 40px 20px;
        }
        .meme {
            background: #23272f;
            border-radius: 12px;
            box-shadow: 0 4px 16px #0007;
            padding: 28px 24px 20px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.15s, box-shadow 0.15s;
            width: 100%;
            max-width: 700px;
            min-width: 340px;
            min-height: 650px;
            max-height: 650px;
            justify-content: flex-start;
            position: relative;
        }
        .meme:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 8px 32px #000b;
        }
        .meme img {
            width: 100%;
            height: 440px;
            object-fit: contain;
            border-radius: 10px;
            margin-bottom: 18px;
            box-shadow: 0 2px 12px #0006;
            background:rgb(20, 22, 26);
            display: block;
            cursor: default;
        }
        .meme h2 {
            margin: 0 0 10px 0;
            color: #a7bfff;
            font-size: 1.3em;
            text-align: center;
        }
        .meme p {
            color: #b0b8d1;
            font-size: 1em;
            margin-bottom: 8px;
            text-align: center;
        }
        .meme small {
            color: #7f8fa6;
            font-size: 0.9em;
        }
        .opciones-btn {
            position: absolute;
            top: 18px;
            right: 18px;
            background: none;
            border: none;
            color: #b0b8d1;
            font-size: 1.7em;
            cursor: pointer;
            z-index: 10;
            padding: 4px;
            border-radius: 50%;
            transition: background 0.2s, color 0.2s;
        }
        .opciones-btn:hover {
            background: #23272f;
            color: #fff;
        }
        .menu-opciones {
            display: none;
            position: absolute;
            top: 44px;
            right: 18px;
            background: #23272f;
            border-radius: 8px;
            box-shadow: 0 2px 12px #000a;
            min-width: 120px;
            z-index: 20;
            padding: 6px 0;
        }
        .menu-opciones.activo {
            display: block;
        }
        .menu-opciones button {
            background: none;
            border: none;
            color: #b0b8d1;
            width: 100%;
            text-align: left;
            padding: 10px 18px;
            font-size: 1em;
            cursor: pointer;
            border-radius: 6px;
            transition: background 0.2s, color 0.2s;
        }
        .menu-opciones button:hover {
            background: #647dee;
            color: #fff;
        }
        @media (max-width: 600px) {
            .galeria { grid-template-columns: 1fr; }
            .modal-formulario { min-width: 0; padding: 18px 6vw 12px 6vw; }
            .btn-flotante { left: 10px; top: 10px; padding: 10px 16px; font-size: 1em; }
        }
        .header-indie {
            position: fixed;
            top: 0; left: 0; right: 0;
            width: 100vw;
            height: 64px;
            background: rgba(30,34,43,0.98);
            box-shadow: 0 2px 12px #0008;
            z-index: 1100;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #logo-indie {
            color: #a7bfff;
            font-size: 2.1em;
            font-family: 'Segoe UI', Arial, sans-serif;
            letter-spacing: 2px;
            margin: 0;
            cursor: pointer;
            user-select: none;
            transition: color 0.2s, text-shadow 0.2s;
            text-shadow: 0 2px 12px #1118;
        }
        #logo-indie:hover {
            color: #fff;
            text-shadow: 0 4px 24px #647dee;
        }
        .modal-img-fondo {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100vw; height: 100vh;
            background: rgba(30,34,43,0.85);
            backdrop-filter: blur(6px);
            z-index: 2000;
        }
        .modal-img-fondo.activo {
            display: block;
        }
        .modal-img {
            display: none;
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2001;
            background: none;
            padding: 0;
            border-radius: 0;
            box-shadow: none;
            max-width: 98vw;
            max-height: 98vh;
            text-align: center;
        }
        .modal-img.activo {
            display: block;
        }
        #imgModalGrande {
            max-width: 90vw;
            max-height: 85vh;
            border-radius: 12px;
            box-shadow: 0 4px 32px #000b;
            background: #181a20;
        }
        .cerrar-modal-img {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #23272f;
            border: none;
            color: #fff;
            font-size: 2.2em;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            cursor: pointer;
            box-shadow: 0 2px 8px #0008;
            z-index: 2002;
            transition: background 0.2s, color 0.2s;
        }
        .cerrar-modal-img:hover {
            background: #7f53ac;
            color: #fff;
        }
        .comentario-link {
            display: block;
            margin: 18px auto 0 auto;
            text-align: center;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            border-radius: 8px;
            padding: 6px 0 0 0;
            width: 100%;
            max-width: 180px;
        }
        .comentario-link:hover span {
            color: #fff;
        }
        .comentario-link:hover svg {
            stroke: #fff;
        }
    </style>
</head>
<body>
    <header class="header-indie">
        <h1 id="logo-indie">INDIE MEMES</h1>
    </header>
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="mensaje">
            <?= htmlspecialchars($_GET['mensaje']) ?>
        </div>
    <?php endif; ?>
    <button class="btn-flotante" id="abrirModal">Subir Meme</button>
    <div class="modal-fondo" id="modalFondo"></div>
    <div class="modal-formulario" id="modalFormulario">
        <button class="cerrar-modal" id="cerrarModal" title="Cerrar">&times;</button>
        <h2 style="text-align:center;">Subir un nuevo meme</h2>
        <form action="subir_meme.php" method="POST" enctype="multipart/form-data">
            <label for="titulo">Título:</label><br>
            <input type="text" name="titulo" id="titulo" required><br>
            <label for="descripcion">Descripción:</label><br>
            <textarea name="descripcion" id="descripcion" rows="3" required></textarea><br>
            <label for="imagen">Imagen o Video(hasta 8mb):</label><br>
            <input type="file" name="imagen" id="imagen" accept="image/*,video/mp4,video/webm,video/ogg" required><br>
            <button type="submit">Subir Meme</button>
        </form>
    </div>
    <h1 style="text-align:center;">Galería de Memes</h1>
    <div class="galeria">
    <?php while ($meme = $resultado->fetch_assoc()): ?>
        <div class="meme">
            <button class="opciones-btn" title="Opciones" onclick="toggleMenuOpciones(event, 'menu-<?= $meme['id'] ?>')">
                &#8942;
            </button>
            <div class="menu-opciones" id="menu-<?= $meme['id'] ?>">
                <button onclick="descargarMeme('imagenes/<?= htmlspecialchars($meme['imagen']) ?>', '<?= addslashes(htmlspecialchars($meme['titulo'])) ?>')">Descargar</button>
            </div>
            <h2><?= htmlspecialchars($meme['titulo']) ?></h2>
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
    <script>
        const abrirModal = document.getElementById('abrirModal');
        const cerrarModal = document.getElementById('cerrarModal');
        const modalFondo = document.getElementById('modalFondo');
        const modalFormulario = document.getElementById('modalFormulario');

        function mostrarModal() {
            modalFondo.classList.add('activo');
            modalFormulario.classList.add('activo');
            document.body.style.overflow = 'hidden';
        }
        function ocultarModal() {
            modalFondo.classList.remove('activo');
            modalFormulario.classList.remove('activo');
            document.body.style.overflow = '';
        }
        abrirModal.addEventListener('click', mostrarModal);
        cerrarModal.addEventListener('click', ocultarModal);
        modalFondo.addEventListener('click', ocultarModal);
        // Cerrar con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') ocultarModal();
        });

        document.getElementById('logo-indie').onclick = function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        // Modal de imagen grande
        const modalImgFondo = document.getElementById('modalImgFondo');
        const modalImg = document.getElementById('modalImg');
        const cerrarModalImg = document.getElementById('cerrarModalImg');
        const imgModalGrande = document.getElementById('imgModalGrande');

        function mostrarImgModal(src, alt) {
            imgModalGrande.src = src;
            imgModalGrande.alt = alt || 'Meme grande';
            modalImgFondo.classList.add('activo');
            modalImg.classList.add('activo');
            document.body.style.overflow = 'hidden';
        }
        function ocultarImgModal() {
            modalImgFondo.classList.remove('activo');
            modalImg.classList.remove('activo');
            imgModalGrande.src = '';
            document.body.style.overflow = '';
        }
        cerrarModalImg.addEventListener('click', ocultarImgModal);
        modalImgFondo.addEventListener('click', ocultarImgModal);
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') ocultarImgModal();
        });
        // Asignar evento a todas las imágenes de memes
        window.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.meme-img-ampliable').forEach(function(img) {
                img.addEventListener('click', function() {
                    mostrarImgModal(img.src, img.alt);
                });
                img.style.cursor = 'default';
            });
        });

        function toggleMenuOpciones(event, id) {
            event.stopPropagation();
            document.querySelectorAll('.menu-opciones').forEach(function(menu) {
                if (menu.id !== id) menu.classList.remove('activo');
            });
            var menu = document.getElementById(id);
            if (menu) menu.classList.toggle('activo');
        }
        document.addEventListener('click', function() {
            document.querySelectorAll('.menu-opciones').forEach(function(menu) {
                menu.classList.remove('activo');
            });
        });
        function descargarMeme(url, nombre) {
            const extension = url.split('.').pop().split('?')[0];
            const nombreArchivo = nombre.replace(/[^a-zA-Z0-9-_]/g, '_') + '.' + extension;
            const a = document.createElement('a');
            a.href = url;
            a.download = nombreArchivo;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script>
</body>
</html>
