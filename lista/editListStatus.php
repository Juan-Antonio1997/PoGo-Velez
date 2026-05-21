<?php
require_once('../config.php');
require_once('../db_pdo.php');
session_start();
date_default_timezone_set('Europe/Madrid');
if (!isset($_SESSION['usuario'])) {
    $_SESSION['warningAlert'] = "¡Tienes que iniciar sesión antes de poder hacer cambios en una lista!";
    header('Location: ../login');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario['ID_Lista'] = $_POST['ID_Lista'];
    $usuario['Username'] = $_SESSION['usuario'];
    $currDate = date("Y-m-d");
    $currTime = date("H:i:s");
    $usuario['Hora_ultimo_cambio'] = $currDate . " " . $currTime;
    $db = db_open();
    if ($db) {
        $comprobacionApuntado = db_query($db, "SELECT * FROM apuntados_lista WHERE ID_Lista = (?) AND Username = (?) AND Estado != (?)", [$usuario['ID_Lista'], $usuario['Username'], "No voy"]);
        if (!empty($comprobacionApuntado) && $_POST['Funcion'] == "modificarPase") {
            $usuario['Pase'] = $_POST['Pase'];
            $updatePass = db_query($db, "UPDATE apuntados_lista
            SET Pase = (?), Hora_ultimo_cambio = (?)
            WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Pase'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
            db_close($db);
            $_SESSION['successAlert'] = "He cambiado tu tipo de participación a: " . $usuario['Pase'];
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            exit;
        } elseif (!empty($comprobacionApuntado) && $_POST['Funcion'] == "modificarEstado") {
            $usuario['Estado'] = $_POST['Estado'];
            $updateStatus = db_query($db, "UPDATE apuntados_lista
            SET Estado = (?), Hora_ultimo_cambio = (?)
            WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Estado'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
            db_close($db);
            $_SESSION['successAlert'] = "He cambiado tu estado a: " . $usuario['Estado'];
            if ($usuario['Estado'] == "Llego tarde") {
                $_SESSION['warningAlert'] = "Ten en cuenta que los apuntados no están obligados a esperarte si llegas tarde";
            }
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            exit;
        } else {
            $_SESSION['errorAlert'] = "Se ha producido un error al intentar cambiar tu estado en esta lista. Por favor, inténtalo de nuevo más tarde.";
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            exit;
        }
    } else {
        $_SESSION['errorAlert'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta editar tu estado en esta lista más tarde.";
        header("Location: ../lista/?id=" . $usuario['ID_Lista']);
        exit;
    }
} else {
    header('Location: ../');
    exit;
}