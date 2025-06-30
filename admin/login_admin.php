<?php


session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: vista_admin.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Login Admin</title>
  <link rel="stylesheet" href="css_admin/estilo_login.css" />
</head>




<body>
  <h2>Iniciar sesión (Administrador)</h2>

  <?php if (!empty($_SESSION['login_error'])): ?>

    <p style="color:red"><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>

  <?php endif; ?>

  <form action="procesar_admin.php" method="post" autocomplete="off">

    <label for="username">Usuario:</label><br>
    <input type="text" name="username" id="username" required><br><br>
    
    <label for="password">Contraseña:</label><br>
    <input type="password" name="password" id="password" required><br><br>
    
    <input type="submit" value="Ingresar">

  </form>

</body>
</html>
