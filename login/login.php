<?php
require_once('../config.php');
require_once('../db_pdo.php');
$db = db_open();
session_start();
date_default_timezone_set('Europe/Madrid');
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
                $_SESSION['login'] = "¡Bienvenid@, " . $username . "!";
                header('Location: ../');
                exit;
            } else {
                $_SESSION['loginIncorrecto'] = 'Usuario, email o contraseña incorrecto';
                header('Location: ../login');
                exit;
            }
        } else {
            $_SESSION['loginIncorrecto'] = 'Usuario, email o contraseña incorrecto';
            header('Location: ../login');
            exit;
        }
    } else {
        header('Location: ../login');
        exit;
    }
} else {
    $_SESSION['db_error'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta iniciar sesión más tarde.";
    header('Location: ../login');
    exit;
}
