<?php
require_once('../config.php');
require_once('../db_pdo.php');
session_start();
date_default_timezone_set('Europe/Madrid');
if (!isset($_SESSION['usuario'])) {
    $_SESSION['advertencia'] = "¡Tienes que iniciar sesión antes de poder desapuntarte de una lista!";
    header('Location: ../login');
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
        if (!empty($comprobacionApuntado)) {
            $usuario['Estado'] = "No voy";
            $usuario['Invitado_presencial'] = 0;
            $usuario['Invitado_remoto'] = 0;
            $update = db_query($db, "UPDATE apuntados_lista
            SET Estado = (?), Invitado_presencial = (?), Invitado_remoto = (?), Hora_ultimo_cambio = (?)
            WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Estado'], $usuario['Invitado_presencial'], $usuario['Invitado_remoto'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
            db_close($db);
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
        } else {
            $_SESSION['removeFromListError'] = "Se ha producido un error al intentar desapuntarte de esta lista. Por favor, inténtalo de nuevo más tarde.";
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            exit;
        }
    } else {
        $_SESSION['removeFromListError'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta desapuntarte de esta lista más tarde.";
        header("Location: ../lista/?id=" . $usuario['ID_Lista']);
        exit;
    }
} else {
    header('Location: ../');
    exit;
}