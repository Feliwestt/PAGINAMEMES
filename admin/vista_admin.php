    <?php

        // Esta es la vista del administrador

    require_once '_auth_admin.php';                       
    require_once __DIR__ . '/../includes/conexion.php';  

    $adminUser = $_SESSION['admin_user'] ?? 'admin';


    $filtro = $conexion->real_escape_string($_GET['filtro_etiqueta'] ?? '');
    $sql   = $filtro
            ? "SELECT * FROM memes WHERE etiqueta = '$filtro' ORDER BY fecha DESC"
            : "SELECT * FROM memes ORDER BY fecha DESC";
    $memes = $conexion->query($sql);
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
    <meta charset="utf-8">
    <title>Panel de Administración | Indie Memes</title>

    <link rel="stylesheet" href="css_admin/estilos_admin.css">

    </head>

    <body>

    <header class="header-indie">
    <div class="admin-bar">

        <!-- Hay que arreglar esta wea, no me dio la cabeza wn, si quitas los espacios invisbles el bienvenidos se esconde en la izquierda de la pagina -->

        <div class="admin-left">
            <span class="admin-welcome">‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎  Bienvenido, <strong><?= htmlspecialchars($adminUser) ?></strong></span>
        </div>
        <div class="admin-center"> 
            <h1 id="logo-indie">INDIE MEMES | <small>Panel Admin</small></h1>
        </div>
        <div class="admin-right">
            <a href="lista_reportes.php" class="btn-admin">📋 Reportes</a>
            <a href="informe_flujo.php" class="btn-admin">📈 Informe</a>
            <a href="logout.php" class="btn-admin btn-salir">⏻ Salir</a>
        </div>
    </div>
    </header>
    

    <!-- Filtro por etiqueta -->
    <section style="padding:0 20px 10px">
        <form method="GET" action="vista_admin.php" style="text-align:center; margin-bottom: 24px;">
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
    </section>

    <!-- Galería de memes -->

    <h1 style="text-align:center;">Galería de Memes (Admin)</h1>

    <div class="galeria">
    <?php while ($m = $memes->fetch_assoc()): ?>
        <div class="meme">
            <!-- Botón de menú ⋮ -->
            <button class="opciones-btn"
                    title="Opciones"
                    onclick="toggleMenuOpciones(event,'menu-<?= $m['id'] ?>')">
                &#8942;
            </button>

            <!-- Menú de opciones -->
            <div class="menu-opciones" id="menu-<?= $m['id'] ?>">
                <button
                onclick="descargarMeme('../imagenes/<?= htmlspecialchars($m['imagen']) ?>',
                                        '<?= addslashes(htmlspecialchars($m['titulo'])) ?>')">
                    Descargar
                </button>
                <button
                onclick="eliminarMeme(<?= $m['id'] ?>)">
                    Eliminar
                </button>
            </div>

            <!-- Título y etiqueta -->
            <h2><?= htmlspecialchars($m['titulo']) ?></h2>
            <span class="etiqueta-meme"><?= htmlspecialchars($m['etiqueta']) ?></span>

            <!-- Media (imagen o video) con zoom -->
            <?php if ($m['tipo'] === 'video'): ?>
                <video controls
                    class="meme-img-ampliable"
                    style="width:100%;height:440px;background:#14161a;border-radius:10px;
                            margin-bottom:18px;box-shadow:0 2px 12px #0006;object-fit:contain;">
                    <source src="../imagenes/<?= htmlspecialchars($m['imagen']) ?>" type="video/mp4">
                    Tu navegador no soporta el video.
                </video>
            <?php else: ?>
                <img class="meme-img-ampliable"
                    src="../imagenes/<?= htmlspecialchars($m['imagen']) ?>"
                    alt="Meme">
            <?php endif; ?>

            <!-- Descripción y fecha -->
            <p><?= nl2br(htmlspecialchars($m['descripcion'])) ?></p>
            <small><?= $m['fecha'] ?></small>

            <!-- Link a comentarios (la misma página que usas en público o tu versión admin) -->
            <a class="comentario-link"
            href="meme_admin.php?id=<?= $m['id'] ?>"
            title="Ver / Comentar">
                <span style="display:inline-flex;align-items:center;gap:6px;">
                    <svg width="24" height="24" fill="none" stroke="#a7bfff"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        viewBox="0 0 24 24">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span style="color:#a7bfff;font-weight:500;">Comentarios</span>
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



    <script src="js_admin/funcion_vista.js"></script>


    <script>
    function eliminarMeme(id){
    if(!confirm('¿Seguro que deseas borrar este meme?')) return;

    fetch('eliminar_meme.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'meme_id='+encodeURIComponent(id)
    })
    .then(r=>r.text())
    .then(txt=>{
        if(txt.trim()==='OK') location.reload();
        else alert('Error:\n'+txt);
    })
    .catch(()=>alert('Error al conectar con el servidor.'));
    }
    </script>

    </body>
    </html>
