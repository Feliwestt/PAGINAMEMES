<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../includes/conexion.php';

$clientID = '220719803439-fr1ek66bkleoas8h2k7pbur2v3se7rih.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/PRUEBAPAGINAMEMES/usuarios/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope('email');
$client->addScope('profile');

$service = new Google_Service_Oauth2($client);

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    $client->setAccessToken($_SESSION['access_token']);

    $user = $service->userinfo->get();
    $google_id = $user->id;
    $nombre = $user->name;
    $email = $user->email;

    // Buscar usuario por google_id o email
    $sql = "SELECT id, nombre FROM usuarios WHERE google_id = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $google_id, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Usuario ya existe
        $stmt->bind_result($id, $nombre_db);
        $stmt->fetch();
        $_SESSION['usuario_id'] = $id;
        $_SESSION['usuario_nombre'] = $nombre_db;
    } else {
        // Nuevo usuario
        $sql = "INSERT INTO usuarios (nombre, email, google_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombre, $email, $google_id);
        if ($stmt->execute()) {
            $_SESSION['usuario_id'] = $stmt->insert_id;
            $_SESSION['usuario_nombre'] = $nombre;
        }
    }
    $stmt->close();
    $conn->close();
    header('Location: ../index.php');
    exit();
} else {
    header('Location: login.php');
    exit();
} 