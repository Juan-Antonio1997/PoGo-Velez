<?php
/* Inicio la sesión (activo las variables $_SESSION) */
session_start();
/* Establezco que la zona horaria por defecto sea la de Europa/Madrid */
date_default_timezone_set('Europe/Madrid');
if (isset($_SESSION['usuario'])) {
    unset($_SESSION['usuario']);
    unset($_SESSION['email']);
    unset($_SESSION['pogo_username']);
    $_SESSION = [];
    $_SESSION['successAlert'] = "Se ha cerrado tu sesión con éxito";
    header('Location: ../');
    exit;
} else {
    $_SESSION['errorAlert'] = "No tienes una sesión iniciada";
    header('Location: ../');
    exit;
}
