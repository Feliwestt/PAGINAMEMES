<?php


require_once '_auth_admin.php';   // ← ya hace session_start() y valida al admin


require_once __DIR__.'/../includes/conexion.php';

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
$meme = $conexion->query("SELECT * FROM memes WHERE id = $id")->fetch_assoc() ?: die('Meme no encontrado.');
$comentarios = $conexion->query("SELECT * FROM comentarios WHERE meme_id = $id ORDER BY fecha ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin<?= htmlspecialchars($meme['titulo']) ?></title>
    <link rel="stylesheet" href="css_admin/meme_comentario_admin.css">
</head> 
<body>

<a class="volver-link" href="vista_admin.php">&larr; Panel Admin</a>

<div class="detalle-meme-admin">
    <div class="encabezado-meme">
        <h2><?= htmlspecialchars($meme['titulo']) ?></h2>
        <span class="meme-id">ID <?= $meme['id'] ?></span>
        <small class="meme-fecha"><?= $meme['fecha'] ?></small>

        <!-- Botón eliminar meme -->
        <button class="btn-eliminar-meme"
                onclick="eliminarMeme(<?= $meme['id'] ?>)">
            🗑️ Eliminar
        </button>
    </div>

    <?php if ($meme['tipo']==='video'): ?>
        <video controls class="media-meme">
            <source src="../imagenes/<?= htmlspecialchars($meme['imagen']) ?>" type="video/mp4">
        </video>
    <?php else: ?>
        <img class="media-meme" src="../imagenes/<?= htmlspecialchars($meme['imagen']) ?>" alt="Meme">
    <?php endif; ?>

    <p class="meme-descripcion"><?= nl2br(htmlspecialchars($meme['descripcion'])) ?></p>
</div>

<!-- Sección comentarios -->
<section class="comentarios-admin">
    <h3>Comentarios (<?= $comentarios->num_rows ?>)</h3>

    <?php if($comentarios->num_rows): ?>
        <?php while($c = $comentarios->fetch_assoc()): ?>
            <article class="comentario">
                <div class="comentario-info">
                    <strong><?= htmlspecialchars($c['autor']) ?></strong>
                    <small><?= $c['fecha'] ?> · id <?= $c['id'] ?></small>
                </div>

                <p><?= nl2br(htmlspecialchars($c['texto'])) ?></p>

                <!-- Botón eliminar comentario -->
                <button class="btn-del-comentario"
                        title="Borrar comentario"
                        onclick="eliminarComentario(<?= $c['id'] ?>)">
                    🗑️
                </button>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No hay comentarios.</p>
    <?php endif; ?>
</section>

<script>
function eliminarMeme(id){
    if(!confirm('¿Eliminar definitivamente este meme?')) return;
    fetch('eliminar_meme.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'meme_id='+encodeURIComponent(id)
    })
    .then(r=>r.text()).then(t=>{
        if(t.trim()==='OK'){ location.href='vista_admin.php?msg=Eliminado'; }
        else alert('Error:\n'+t);
    }).catch(()=>alert('Fallo de red'));
}

function eliminarComentario(id){
    if(!confirm('¿Borrar este comentario?')) return;
    fetch('eliminar_comentario.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'comentario_id='+encodeURIComponent(id)
    })
    .then(r=>r.text()).then(t=>{
        if(t.trim()==='OK') location.reload();
        else alert('Error:\n'+t);
    }).catch(()=>alert('Fallo de red'));
}
</script>

</body>
</html>
