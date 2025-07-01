<?php
session_start();
require_once '../includes/conexion.php'; // Asegúrate de que este archivo conecta a tu BD

if (isset($_POST['registrar'])) {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validar que el email no exista
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $mensaje = "El correo ya está registrado.";
    } else {
        // Hashear la contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar usuario
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $nombre, $email, $password_hash);

        if ($stmt->execute()) {
            $_SESSION['usuario_id'] = $stmt->insert_id;
            $_SESSION['usuario_nombre'] = $nombre;
            $stmt->close();
            $conexion->close();
            header('Location: ../index.php');
            exit();
        } else {
            $mensaje = "Error al registrar. Intenta de nuevo.";
        }
    }
    $stmt->close();
    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../css/registro.css">
</head>
<body>
    <a href="../index.php" class="btn-volver" style="margin:40px 0 18px 0;display:inline-block;">&larr; Volver</a>
    <form class="form-registro" action="registro.php" method="POST">
        <h2>Registro de Usuario</h2>
        <?php if (isset($mensaje)) echo '<p>' . $mensaje . '</p>'; ?>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" required><br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br>
        <button type="submit" name="registrar">Registrarse</button>
        <hr>
        <form action="google_login.php" method="GET" style="margin-top:20px;">
            <button type="submit" class="google-btn">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google"> Registrarse con Google
            </button>
        </form>
    </form>
    <p style="text-align:center; margin-top:18px;">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</body>
</html> 