<?php
require_once '../vendor/autoload.php';

// Configura tus credenciales de Google
$clientID = '220719803439-fr1ek66bkleoas8h2k7pbur2v3se7rih.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/PRUEBAPAGINAMEMES/usuarios/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope('email');
$client->addScope('profile');

$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit(); 