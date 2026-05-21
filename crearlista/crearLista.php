<?php
require_once('../config.php');
require_once('../db_pdo.php');
session_start();
date_default_timezone_set('Europe/Madrid');
if (!isset($_SESSION['usuario'])) {
    $_SESSION['advertencia'] = "¡Tienes que iniciar sesión antes de poder crear una lista!";
    header('Location: ../login');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lista['ID_Raid'] = $_POST['ID_Raid'];
    $lista['Creado_por'] = $_SESSION['usuario'];
    $lista['Ubicacion'] = $_POST['Ubicacion'];
    if (strlen($_POST['Enlace_Maps']) > 0) {
        $lista['Enlace_Maps'] = $_POST['Enlace_Maps'];
    };
    $currDate = date("Y-m-d");
    $currTime = date("H:i:s");
    $lista['Hora_creacion'] = $currDate . " " . $currTime;
    $lista['Hora_quedada'] = $currDate . " " . $_POST['Hora_quedada'] . ":00";
    $validateTime = False;
    if (strtotime($lista['Hora_creacion']) < strtotime($lista['Hora_quedada'])) {
        $validateTime = True;
    }
    if (strlen($_POST['Hora_inicio']) > 0) {
        $lista['Hora_inicio'] = $currDate . " " . $_POST['Hora_inicio'] . ":00";
    };
    if (strlen($_POST['Hora_fin']) > 0) {
        $lista['Hora_fin'] = $currDate . " " . $_POST['Hora_fin'] . ":00";
    }
    $lista['Tiempo_atmos'] = $_POST['Tiempo_atmos'];
    $db = db_open();
    if ($db) {
        if ($validateTime) {
            $id = db_insert($db, 'listas', $lista);
            $apuntado['ID_Lista'] = $id;
            $apuntado['Username'] = $_SESSION['usuario'];
            $apuntado['Pase'] = $_POST['Pase'];
            $apuntado['Estado'] = "Voy";
            $apuntado['Hora_apuntado'] = $lista['Hora_creacion'];
            $apuntado['Hora_ultimo_cambio'] = $lista['Hora_creacion'];
            $id2 = db_insert($db, 'apuntados_lista', $apuntado);
            db_close($db);
            $_SESSION['listaCreada'] = "¡Se ha creado la lista con éxito! Su ID es: " . $id;
            header("Location: ../lista/?id=" . $id);
            exit;
        } else {
            $_SESSION['crearListaError'] = "Se ha producido un error al crear la lista. Por favor, inténtalo de nuevo más tarde.";
            header("Location: ../crearlista");
            exit;
        }
    } else {
        $_SESSION['crearListaError'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta crear la lista más tarde.";
        header("Location: ../crearlista");
        exit;
    }
} else {
    header('Location: ../crearLista');
    exit;
}