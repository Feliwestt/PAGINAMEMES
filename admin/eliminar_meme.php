<?php
require '_auth_admin.php';
require __DIR__ . '/../includes/conexion.php';

$id = (int)($_POST['meme_id'] ?? 0);
if ($id) {
    $ok = $conexion->query("DELETE FROM memes WHERE id = $id");
    echo $ok ? 'OK' : 'Error al eliminar: '.$conexion->error;
}
?>
