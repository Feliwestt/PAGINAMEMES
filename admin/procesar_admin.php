<?php
session_start();
require __DIR__ . '/../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_admin.php'); exit;
}

$user = trim($_POST['username'] ?? '');
$pass = $_POST['password'] ?? '';

if ($user === '' || $pass === '') {
    $_SESSION['login_error'] = 'Completa todos los campos';
    header('Location: login_admin.php'); exit;
}

$stmt = $conexion->prepare(
    "SELECT id_admin, contra_hash FROM admins WHERE usuario = ? LIMIT 1"
);
$stmt->bind_param('s', $user);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    if (password_verify($pass, $row['contra_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id']   = $row['id_admin'];
        $_SESSION['admin_user'] = $user;
        header('Location: vista_admin.php'); exit;
    }
}

sleep(1);
$_SESSION['login_error'] = 'Credenciales incorrectas';
header('Location: login_admin.php');
exit;
?>
