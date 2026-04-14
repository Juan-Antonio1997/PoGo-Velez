<?php
require_once('../config.php');
require_once('../db_pdo.php');
$db = db_open();
session_start();
if ($db) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Username)=LOWER(?) OR LOWER(Email)=LOWER(?)", [$_POST['User'], $_POST['User']]);
        if (!empty($usuario) && count($usuario) == 1) {
            $username = $usuario[0]['Username'];
            $email = $usuario[0]['Email'];
            $password = $usuario[0]['Password'];
            if (($_POST['User'] === $username || $_POST['User'] === $email) && password_verify($_POST["Password"], $password)) {
                $_SESSION['usuario'] = $username;
                $_SESSION['email'] = $email;
                header('Location: ../');
                exit;
            } else {
                print 'Usuario, email o contraseña incorrecto';
                exit;
            }
        } else {
            print 'Usuario, email o contraseña incorrecto';
            exit;
        }
    } else {
        header('Location: index.php');
        exit;
    }
} else {
    print "Se ha producido un error de conexión";
    exit;
}
