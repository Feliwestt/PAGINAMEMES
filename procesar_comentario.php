<?php
session_start();
include 'includes/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: usuarios/login.php?mensaje=Debes+iniciar+sesión+para+comentar');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meme_id = isset($_POST['meme_id']) ? intval($_POST['meme_id']) : 0;
    $autor = $_SESSION['usuario_nombre'];
    $texto = trim($_POST['texto'] ?? '');

    if ($meme_id > 0 && $autor !== '' && $texto !== '') {
        $autor = $conexion->real_escape_string($autor);
        $texto = $conexion->real_escape_string($texto);
        $sql = "INSERT INTO comentarios (meme_id, autor, texto) VALUES ($meme_id, '$autor', '$texto')";
        if ($conexion->query($sql)) {
            header("Location: meme.php?id=$meme_id&mensaje=Comentario+agregado");
            exit;
        } else {
            header("Location: meme.php?id=$meme_id&mensaje=Error+al+guardar+el+comentario");
            exit;
        }
    } else {
        header("Location: meme.php?id=$meme_id&mensaje=Completa+todos+los+campos");
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?> 