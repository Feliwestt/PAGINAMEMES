<?php
$conexion = new mysqli("localhost", "root", "", "db_memes_indie");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?> 