<?php
/* Obtengo el PDO y la configuración para poder manipular bases de datos */
require_once('../config.php');
require_once('../db_pdo.php');
/* Inicio la sesión (activo las variables $_SESSION) */
session_start();
/* Establezco que la zona horaria por defecto sea la de Europa/Madrid */
date_default_timezone_set('Europe/Madrid');
if (!isset($_SESSION['usuario'])) {
    $_SESSION['warningAlert'] = "¡Tienes que iniciar sesión antes de poder apuntarte a una lista!";
    header('Location: ../login');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario['ID_Lista'] = $_POST['ID_Lista'];
    $usuario['Username'] = $_SESSION['usuario'];
    $usuario['Pase'] = $_POST['Pase'];
    $usuario['Estado'] = $_POST['Estado'];
    $currDate = date("Y-m-d");
    $currTime = date("H:i:s");
    $usuario['Hora_apuntado'] = $currDate . " " . $currTime;
    $usuario['Hora_ultimo_cambio'] = $currDate . " " . $currTime;
    /* Abro la conexión a la base de datos indicada en el fichero de configuración */
    $db = db_open();
    /* Condición: Si hay conexión a la base de datos */
    if ($db) {
        $comprobacionApuntado = db_query($db, "SELECT * FROM apuntados_lista WHERE ID_Lista = (?) AND Username = (?)", [$usuario['ID_Lista'], $usuario['Username']]);
        $comprobacionDesapuntado = db_query($db, "SELECT * FROM apuntados_lista WHERE ID_Lista = (?) AND Username = (?) AND Estado = (?)", [$usuario['ID_Lista'], $usuario['Username'], "No voy"]);
        if (empty($comprobacionApuntado)) {
            $id = db_insert($db, 'apuntados_lista', $usuario);
            db_close($db);
            $_SESSION['successAlert'] = "¡Te has apuntado a esta lista como " . strtolower($usuario['Pase']) . "!";
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        } elseif (!empty($comprobacionDesapuntado)) {
            $addBack = db_query($db, "UPDATE apuntados_lista
            SET Pase = (?), Estado = (?), Hora_ultimo_cambio = (?)
            WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Pase'], $usuario['Estado'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
            db_close($db);
            $_SESSION['successAlert'] = "¡Te has vuelto a apuntar a esta lista como " . strtolower($usuario['Pase']) . "!";
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        } else {
            $_SESSION['errorAlert'] = "Se ha producido un error al intentar apuntarte a esta lista. Por favor, inténtalo de nuevo más tarde.";
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        }
    } else {
        $_SESSION['errorAlert'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta apuntarte a esta lista más tarde.";
        header("Location: ../lista/?id=" . $usuario['ID_Lista']);
        /* Y con "exit" hago que se detenga el script, para que no ejecute el 
        resto de funciones */
        exit;
    }
} else {
    header('Location: ../');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
}
