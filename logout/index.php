<?php
session_start();
date_default_timezone_set('Europe/Madrid');
if (isset($_SESSION['usuario'])) {
    unset($_SESSION['usuario']);
    unset($_SESSION['email']);
    unset($_SESSION['pogo_username']);
    $_SESSION = [];
    #if (ini_get("session.use_cookies")) {
    #    $params = session_get_cookie_params();
    #    setcookie(
    #        session_name(),
    #        '',
    #        time() - 60 * 60 * 24 * 365,
    #        $params["path"],
    #        $params["domain"],
    #        $params["secure"],
    #        $params["httponly"]
    #    );
    #}
    #
    #session_destroy();
    $_SESSION['logout'] = "Se ha cerrado tu sesión con éxito";
    header('Location: ../');
    exit;
} else {
    $_SESSION['logoutError'] = "No tienes una sesión iniciada";
    header('Location: ../');
}