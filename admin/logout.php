<?php


session_start();


if (empty($_SESSION['admin_id'])) {      

    header('Location: login_admin.php');
    exit;

}



session_unset();     

session_destroy();           


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}


header('Location: login_admin.php');
exit;
?>
