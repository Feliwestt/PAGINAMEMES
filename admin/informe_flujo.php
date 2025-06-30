<?php
require_once '_auth_admin.php';
require_once __DIR__ . '/../includes/conexion.php';

$adminUser = $_SESSION['admin_user'] ?? 'admin';

/* ───── Rango de fechas (últimos 30 días por defecto) ───── */
$inicio = $_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days'));
$fin    = $_GET['fin']    ?? date('Y-m-d');

/* ───── Consulta al log ───── */
$stmt = $conexion->prepare(
   "SELECT tipo, COUNT(*) AS total
      FROM log_actividad
     WHERE DATE(fecha) BETWEEN ? AND ?
  GROUP BY tipo"
);
$stmt->bind_param('ss', $inicio, $fin);
$stmt->execute();
$res = $stmt->get_result();

$datos = [];
while ($row = $res->fetch_assoc()) $datos[$row['tipo']] = $row['total'];

/* Asegurar que existan todas las claves */
$tipos = ['meme_creado','comentario_creado','reporte_creado',
          'meme_borrado','comentario_borrado'];

foreach ($tipos as $t) if (!isset($datos[$t])) $datos[$t] = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<title>Informe de Flujo | Indie Memes</title>
<link rel="stylesheet" href="css_admin/estilos_admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.informe-box{max-width:800px;margin:90px auto 40px;padding:0 20px;}
.informe-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:18px;margin:24px 0;}
.infocard{background:#23272f;border-radius:12px;padding:20px;text-align:center;box-shadow:0 2px 12px #0006;}
.infocard strong{font-size:1.8em;color:#7fffd4;display:block;margin-bottom:6px;}
.infocard span{color:#a7bfff;font-size:0.9em;}
</style>
</head>

<link rel="stylesheet" href="css_admin/reportes_estilo.css">

<body>

    <a class="volver-link" href="vista_admin.php">&larr; Panel Admin</a>


    <header class="header-indie">
        <div class="admin-bar">

            <!-- Hay que arreglar esta wea, no me dio la cabeza wn, si quitas los espacios invisbles el bienvenidos se esconde en la izquierda de la pagina -->

            <div class="admin-left">
                <span class="admin-welcome">‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎  Bienvenido, <strong><?= htmlspecialchars($adminUser) ?></strong></span>
            </div>
            <div class="admin-center"> 
                <h1 id="logo-indie">INDIE MEMES | <small>Reporte de flujo</small></h1>
            </div>
            <div class="admin-right">
                <a href="lista_reportes.php" class="btn-admin">📋 Reportes</a>
                <a href="informe_flujo.php" class="btn-admin">📈 Informe</a>
                <a href="logout.php" class="btn-admin btn-salir">⏻ Salir</a>
            </div>
        </div>
    </header>

<div class="informe-box">

<form method="GET" style="text-align:center;margin-bottom:20px;">
  <label>Desde: <input type="date" name="inicio" value="<?= htmlspecialchars($inicio) ?>"></label>
  <label>Hasta: <input type="date" name="fin" value="<?= htmlspecialchars($fin) ?>"></label>
  <button class="btn-admin" type="submit">Actualizar</button>
</form>

<div class="informe-grid">
  <div class="infocard"><strong><?= $datos['meme_creado'] ?></strong><span>Memes creados</span></div>
  <div class="infocard"><strong><?= $datos['comentario_creado'] ?></strong><span>Comentarios creados</span></div>
  <div class="infocard"><strong><?= $datos['reporte_creado'] ?></strong><span>Reportes</span></div>
  <div class="infocard"><strong><?= $datos['meme_borrado'] ?></strong><span>Memes borrados</span></div>
  <div class="infocard"><strong><?= $datos['comentario_borrado'] ?></strong><span>Comentarios borrados</span></div>
</div>

<canvas id="graficoFlujo" style="max-width:800px;margin:0 auto;"></canvas>

</div>

<script>
new Chart(document.getElementById('graficoFlujo'),{
  type:'bar',
  data:{
    labels:[
      'Memes creados',
      'Comentarios creados',
      'Reportes',
      'Memes borrados',
      'Comentarios borrados'
    ],
    datasets:[{
      label:'Conteo',
      data:[
        <?= $datos['meme_creado'] ?>,
        <?= $datos['comentario_creado'] ?>,
        <?= $datos['reporte_creado'] ?>,
        <?= $datos['meme_borrado'] ?>,
        <?= $dados['comentario_borrado'] ?>
      ]
    }]
  },
  options:{
    plugins:{legend:{display:false}},
    scales:{y:{beginAtZero:true}}
  }
});
</script>



</body>
</html>
