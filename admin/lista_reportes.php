<?php
require_once '_auth_admin.php';
require_once __DIR__ . '/../includes/conexion.php';

$adminUser = $_SESSION['admin_user'] ?? 'admin';

// Consulta con un JOIN para traer datos del meme reportado

$sql = "SELECT r.id AS reporte_id, r.motivo, r.detalles, r.fecha AS fecha_reporte, 
        
        m.id AS meme_id, m.titulo, m.imagen 
        
        FROM reportes r 
        
        LEFT JOIN memes m ON r.meme_id = m.id 
        ORDER BY r.fecha DESC";

$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<title>Lista de Reportes | Indie Memes</title>
<link rel="stylesheet" href="css_admin/reportes_estilo.css">
</head>
<body>

    <a class="volver-link" href="vista_admin.php">&larr; Panel Admin</a>


    <header class="header-indie">
        <div class="admin-bar">

            <!-- Hay que arreglar esta wea, no me dio la cabeza wn, si quitas los espacios invisbles el bienvenidos se esconde en la izquierda de la pagina -->

            <div class="admin-left">
                <span class="admin-welcome">‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎  Bienvenido, <strong><?= htmlspecialchars($adminUser) ?></strong></span>
            </div>
            <div class="admin-center"> 
                <h1 id="logo-indie">INDIE MEMES | <small>Panel Reportes</small></h1>
            </div>
            <div class="admin-right">
                <a href="lista_reportes.php" class="btn-admin">📋 Reportes</a>
                <a href="informe_flujo.php" class="btn-admin">📈 Informe</a>
                <a href="logout.php" class="btn-admin btn-salir">⏻ Salir</a>
            </div>
        </div>
    </header>


<div class="lista-reportes">
    <h1 style="text-align:center; color:#a7bfff; margin-bottom: 24px;">Reportes de los Usuarios</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($r = $result->fetch_assoc()): ?>
            <div class="reporte-item" tabindex="0" role="button"
                 onclick="abrirModalReporte(<?= $r['reporte_id'] ?>)"
                 onkeydown="if(event.key==='Enter'){abrirModalReporte(<?= $r['reporte_id'] ?>)}"
                 data-motivo="<?= htmlspecialchars($r['motivo']) ?>"
                 data-detalles="<?= htmlspecialchars($r['detalles']) ?>"
                 data-fecha="<?= htmlspecialchars($r['fecha_reporte']) ?>"
                 data-memeid="<?= $r['meme_id'] ?>"
                 data-memetitulo="<?= htmlspecialchars($r['titulo']) ?>"
                 data-memeimagen="<?= htmlspecialchars($r['imagen']) ?>"
            >
                <div class="reporte-info">
                    <div class="reporte-motivo"><?= htmlspecialchars($r['motivo']) ?></div>
                    <div class="reporte-detalles"><?= nl2br(htmlspecialchars($r['detalles'])) ?></div>
                    <div class="reporte-fecha"><?= htmlspecialchars($r['fecha_reporte']) ?></div>
                </div>
                <div class="reporte-meme-titulo" title="Meme reportado: <?= htmlspecialchars($r['titulo']) ?>">
                    <?= htmlspecialchars($r['titulo']) ?: '<em>Sin meme asociado</em>' ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="color:#7f8fa6; text-align:center;">No hay reportes registrados.</p>
    <?php endif; ?>
</div>

<!-- Modal para ver detalles del reporte -->
<div class="modal-reporte-fondo" id="modalReporteFondo" role="dialog" aria-modal="true" aria-labelledby="modalReporteTitulo" tabindex="-1">
    <div class="modal-reporte">
        <button class="cerrar-modal-reporte" id="cerrarModalReporte" aria-label="Cerrar modal">&times;</button>
        <h2 id="modalReporteTitulo"></h2>
        <p id="modalReporteDetalles"></p>
        <p><strong>Fecha del reporte:</strong> <span id="modalReporteFecha"></span></p>

        <div id="modalMemeInfo" style="margin-top:20px;">
            <h3>Meme reportado:</h3>
            <img id="modalMemeImagen" src="" alt="Imagen del meme reportado" style="max-width: 100%; border-radius: 10px; box-shadow: 0 2px 12px #0008; margin-bottom: 12px;">
            <p id="modalMemeTitulo" style="color:#a7bfff; font-weight: 600; font-size: 1.1em;"></p>
            <a id="btnIrAlMeme" href="#" class="btn-admin">Ir al meme reportado</a>
        </div>
    </div>
</div>

<script>
function abrirModalReporte(id) {
    const item = document.querySelector(`.reporte-item[onclick*="abrirModalReporte(${id})"]`);
    if (!item) return;

    document.getElementById('modalReporteTitulo').textContent = item.dataset.motivo;
    document.getElementById('modalReporteDetalles').textContent = item.dataset.detalles;
    document.getElementById('modalReporteFecha').textContent = item.dataset.fecha;

    const memeTitulo = item.dataset.memetitulo || 'Sin meme asociado';
    document.getElementById('modalMemeTitulo').textContent = memeTitulo;

    const memeImagen = item.dataset.memeimagen || '';
    const imgElem = document.getElementById('modalMemeImagen');
    if (memeImagen) {
        imgElem.src = '../imagenes/' + memeImagen;
        imgElem.style.display = 'block';
    } else {
        imgElem.style.display = 'none';
    }

    const memeId = item.dataset.memeid;
    const btnMeme = document.getElementById('btnIrAlMeme');
    if (memeId) {
        btnMeme.href = 'meme_admin.php?id=' + memeId;
        btnMeme.style.display = 'inline-block';
    } else {
        btnMeme.style.display = 'none';
    }

    document.getElementById('modalReporteFondo').classList.add('activo');
    document.getElementById('modalReporteFondo').focus();
}

function cerrarModalReporte() {
    document.getElementById('modalReporteFondo').classList.remove('activo');
}


document.getElementById('cerrarModalReporte').addEventListener('click', cerrarModalReporte);
document.getElementById('modalReporteFondo').addEventListener('click', e => {
    if (e.target.id === 'modalReporteFondo') cerrarModalReporte();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cerrarModalReporte();
});
</script>

</body>
</html>
