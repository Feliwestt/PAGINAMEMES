<?php
include 'includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meme_id = isset($_POST['meme_id']) ? intval($_POST['meme_id']) : 0;
    $motivo = trim($_POST['motivo'] ?? '');
    $detalles = trim($_POST['detalles'] ?? '');

    if ($meme_id > 0 && $motivo !== '') {
        $motivo = $conexion->real_escape_string($motivo);
        $detalles = $conexion->real_escape_string($detalles);
        $sql = "INSERT INTO reportes (meme_id, motivo, detalles) VALUES ($meme_id, '$motivo', '$detalles')";
        if ($conexion->query($sql)) {
            header("Location: index.php?mensaje=Reporte+enviado+con+éxito");
            exit;
        } else {
            header("Location: index.php?mensaje=Error+al+enviar+el+reporte");
            exit;
        }
    } else {
        header("Location: index.php?mensaje=Completa+el+motivo+del+reporte");
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?> 