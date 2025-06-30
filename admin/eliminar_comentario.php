<?php
// eliminar_comentario.php
require_once '_auth_admin.php';            // ← asegura que SOLO un admin pueda borrar
require_once __DIR__ . '/../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);               // Método no permitido
    exit('Método no permitido');
}

$comentarioId = isset($_POST['comentario_id']) ? intval($_POST['comentario_id']) : 0;
if ($comentarioId <= 0) {
    http_response_code(400);               // Petición incorrecta
    exit('ID inválido');
}

// ‑‑‑ Elimina de la tabla comentarios (usa prepared statement para seguridad)
$stmt = $conexion->prepare("DELETE FROM comentarios WHERE id = ?");
$stmt->bind_param('i', $comentarioId);

if ($stmt->execute()) {
    echo 'OK';                             // ← El JS busca exactamente “OK”
} else {
    http_response_code(500);
    echo 'Error al eliminar';
}
$stmt->close();
