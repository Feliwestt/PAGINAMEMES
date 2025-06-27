<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $conexion->real_escape_string($_POST['titulo']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $etiqueta = $conexion->real_escape_string($_POST['etiqueta'] ?? 'SFW');
    
    // Procesar el archivo
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombre_tmp = $_FILES['imagen']['tmp_name'];
        $nombre_original = basename($_FILES['imagen']['name']);
        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
        $imagenes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $videos = ['mp4', 'webm', 'ogg'];
        if (in_array($extension, $imagenes)) {
            $tipo = 'imagen';
        } elseif (in_array($extension, $videos)) {
            $tipo = 'video';
        } else {
            header('Location: index.php?mensaje=Tipo de archivo no permitido');
            exit;
        }
        $nuevo_nombre = uniqid('meme_', true) . '.' . $extension;
        $ruta_destino = 'imagenes/' . $nuevo_nombre;
        if (move_uploaded_file($nombre_tmp, $ruta_destino)) {
            // Guardar en la base de datos
            $sql = "INSERT INTO memes (titulo, imagen, descripcion, tipo, etiqueta) VALUES ('$titulo', '$nuevo_nombre', '$descripcion', '$tipo', '$etiqueta')";
            if ($conexion->query($sql)) {
                header('Location: index.php?mensaje=Meme subido con éxito');
                exit;
            } else {
                header('Location: index.php?mensaje=Error al guardar en la base de datos');
                exit;
            }
        } else {
            header('Location: index.php?mensaje=Error al subir el archivo');
            exit;
        }
    } else {
        header('Location: index.php?mensaje=No se seleccionó ningún archivo');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?> 