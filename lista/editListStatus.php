<?php
/* Obtengo el PDO y la configuración para poder manipular bases de datos */
require_once('../config.php');
require_once('../db_pdo.php');
/* Inicio la sesión (activo las variables $_SESSION) */
session_start();
/* Establezco que la zona horaria por defecto sea la de Europa/Madrid */
date_default_timezone_set('Europe/Madrid');
if (!isset($_SESSION['usuario'])) {
    /* Si no hay una variable de sesión para el usuario, no tiene la sesión iniciada (y no quiero que 
    se pueda acceder a esta página sin iniciar sesión) */
    $_SESSION['warningAlert'] = "¡Tienes que iniciar sesión antes de poder hacer cambios en una lista!";
    /* Redirijo al usuario a la página de inicio de sesión */
    header('Location: ../login');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario['ID_Lista'] = $_POST['ID_Lista'];
    $usuario['Username'] = $_SESSION['usuario'];
    $currDate = date("Y-m-d");
    $currTime = date("H:i:s");
    $usuario['Hora_ultimo_cambio'] = $currDate . " " . $currTime;
    /* Abro la conexión a la base de datos indicada en el fichero de configuración */
    $db = db_open();
    /* Condición: Si hay conexión a la base de datos */
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
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
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
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        } else {
            $_SESSION['errorAlert'] = "Se ha producido un error al intentar cambiar tu estado en esta lista. Por favor, inténtalo de nuevo más tarde.";
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        }
    } else {
        $_SESSION['errorAlert'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta editar tu estado en esta lista más tarde.";
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
