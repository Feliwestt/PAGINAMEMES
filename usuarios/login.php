<?php
session_start();
require_once '../includes/conexion.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, nombre, password FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $nombre, $password_hash);
        $stmt->fetch();
        if ($password_hash && password_verify($password, $password_hash)) {
            // Autenticación exitosa
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nombre'] = $nombre;
            header('Location: ../index.php');
            exit();
        } else {
            $mensaje = "Contraseña incorrecta.";
        }
    } else {
        $mensaje = "El correo no está registrado.";
    }
    $stmt->close();
    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <a href="../index.php" class="btn-volver" style="margin:40px 0 18px 0;display:inline-block;">&larr; Volver</a>
    <form class="form-login" action="login.php" method="POST">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($mensaje)) echo '<p>' . $mensaje . '</p>'; ?>
        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" required><br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br>
        <button type="submit" name="login">Iniciar Sesión</button>
        <hr>
        <form action="google_login.php" method="GET" style="margin-top:20px;">
            <button type="submit" class="google-btn">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google"> Iniciar sesión con Google
            </button>
        </form>
    </form>
    <p style="text-align:center; margin-top:18px;">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
</body>
</html> 