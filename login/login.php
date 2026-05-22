<?php
/* Obtengo el PDO y la configuración para poder manipular bases de datos */
require_once('../config.php');
require_once('../db_pdo.php');
/* Abro la conexión a la base de datos indicada en el fichero de configuración */
$db = db_open();
/* Inicio la sesión (activo las variables $_SESSION) */
session_start();
/* Establezco que la zona horaria por defecto sea la de Europa/Madrid */
date_default_timezone_set('Europe/Madrid');
if (isset($_SESSION['usuario'])) {
    header('Location: ../');
    exit;
}
/* Condición: Si hay conexión a la base de datos */
if ($db) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Username)=LOWER(?) OR LOWER(Email)=LOWER(?)", [$_POST['User'], $_POST['User']]);
        if (!empty($usuario) && count($usuario) == 1) {
            $username = $usuario[0]['Username'];
            $email = $usuario[0]['Email'];
            $password = $usuario[0]['Password'];
            $pogo_username = $usuario[0]['Pogo_Username'];
            if (($_POST['User'] === $username || $_POST['User'] === $email) && password_verify($_POST["Password"], $password)) {
                $_SESSION['usuario'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['pogo_username'] = $pogo_username;
                $_SESSION['successAlert'] = "¡Bienvenid@, " . $username . "!";
                header('Location: ../');
                exit;
            } else {
                $_SESSION['errorAlert'] = 'Usuario, email o contraseña incorrecto';
                header('Location: ../login');
                exit;
            }
        } else {
            $_SESSION['errorAlert'] = 'Usuario, email o contraseña incorrecto';
            header('Location: ../login');
            exit;
        }
    } else {
        header('Location: ../login');
        exit;
    }
} else {
    $_SESSION['errorAlert'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta iniciar sesión más tarde.";
    header('Location: ../login');
    exit;
}
