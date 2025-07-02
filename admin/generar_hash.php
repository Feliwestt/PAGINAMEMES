    
<?php
    // este codigo se encarga de generar un usuario automaticamente, 
    // Recuerden correrlo siempre que manden un clone


require_once __DIR__ . '/../includes/conexion.php';

    // Aqui solo ponen los datos, y el codigo encriptara y guardara la contraseña y usuario automaticamente en la base de datos
$usuario = 'Tomy';
$clave = 'weco';
$hash = password_hash($clave, PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (usuario, contra_hash) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('ss', $usuario, $hash);

if ($stmt->execute()) {
    echo "✔ Admin creado correctamente: <strong>$usuario</strong>";
} else {
    echo "❌ Error al crear admin: " . $stmt->error;
}
