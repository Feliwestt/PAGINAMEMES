<?php

session_start([
    'cookie_httponly' => true,               // 2️⃣ la JS no puede leerla
    'cookie_secure'   => isset($_SERVER['HTTPS']),  // solo se envía con HTTPS
    'cookie_samesite' => 'Strict',           // no se reenvía entre sitios
]);

/* 3️⃣ bloquea inactividad prolongada (ej. 30 min) */
$maxIdle = 60 * 60;                          // 1800 s
if (isset($_SESSION['last_activity']) &&
    time() - $_SESSION['last_activity'] > $maxIdle) {
        session_unset(); session_destroy();
        header('Location: login_admin.php?timeout');
        exit;
}
$_SESSION['last_activity'] = time();

/* 4️⃣ comprueba autenticación */
if (empty($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit;
}

/* 5️⃣ (opcional) confirma rol si guardas más de uno */
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('No tienes permisos para ver esta sección.');
}
?>
