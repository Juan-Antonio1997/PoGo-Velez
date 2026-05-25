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
    $_SESSION['warningAlert'] = "¡Tienes que iniciar sesión antes de poder apuntarte a una lista!";
    /* Redirijo al usuario a la página de inicio de sesión */
    header('Location: ../login');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /* Si la petición es de tipo POST, guardo las variables de POST en la variable "lista" */
    $usuario['ID_Lista'] = $_POST['ID_Lista'];
    $usuario['Username'] = $_SESSION['usuario'];
    $usuario['Pase'] = $_POST['Pase'];
    $usuario['Estado'] = $_POST['Estado'];
    /* Obtengo la fecha de hoy */
    $currDate = date("Y-m-d");
    /* Obtengo la hora actual */
    $currTime = date("H:i:s");
    /* Hago que la hora en la que se ha apuntado sea la fecha de hoy y la hora actual */
    $usuario['Hora_apuntado'] = $currDate . " " . $currTime;
    /* Hago que la hora en la que el usuario ha hecho su último cambio en la lista 
    sea la fecha de hoy y la hora actual */
    $usuario['Hora_ultimo_cambio'] = $currDate . " " . $currTime;
    /* Abro la conexión a la base de datos indicada en el fichero de configuración */
    $db = db_open();
    /* Condición: Si hay conexión a la base de datos */
    if ($db) {
        /* Hago una consulta en la tabla "apuntados_lista" para ver si el usuario está apuntado en la lista */
        $comprobacionApuntado = db_query($db, "SELECT * FROM apuntados_lista WHERE ID_Lista = (?) AND Username = (?)", [$usuario['ID_Lista'], $usuario['Username']]);
        /* Hago una consulta en la tabla "apuntados_lista" para ver si el usuario está apuntado en la lista, pero su estado es "No voy" */
        $comprobacionDesapuntado = db_query($db, "SELECT * FROM apuntados_lista WHERE ID_Lista = (?) AND Username = (?) AND Estado = (?)", [$usuario['ID_Lista'], $usuario['Username'], "No voy"]);
        if (empty($comprobacionApuntado)) {
            /* Si no está apuntado en la lista, introduzco los datos del usuario en la tabla 
            "apuntados_lista" junto con el ID de la lista a la que se va a apuntar */
            $id = db_insert($db, 'apuntados_lista', $usuario);
            /* Cierro la conexión a la base de datos */
            db_close($db);
            /* Creo una variable de sesión para una alerta de tipo éxito indicando que el usuario se ha apuntado con éxito, 
            y con que pase lo ha hecho */
            $_SESSION['successAlert'] = "¡Te has apuntado a esta lista como " . strtolower($usuario['Pase']) . "!";
            /* Redirijo al usuario a la página de la lista */
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        } elseif (!empty($comprobacionDesapuntado)) {
            /* Si está apuntado, pero su estado es "No voy", vuelvo a cambiar su estado a "Voy", apuntándolo de vuelta */
            $addBack = db_query($db, "UPDATE apuntados_lista
            SET Pase = (?), Estado = (?), Hora_ultimo_cambio = (?)
            WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Pase'], $usuario['Estado'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
            /* Cierro la conexión a la base de datos */
            db_close($db);
            /* Creo una variable de sesión para una alerta de tipo éxito indicando que el usuario se ha vuelto a apuntar 
            con éxito, y con que pase lo ha hecho */
            $_SESSION['successAlert'] = "¡Te has vuelto a apuntar a esta lista como " . strtolower($usuario['Pase']) . "!";
            /* Redirijo al usuario a la página de la lista */
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        } else {
            /* Si no se cumple ninguna de las condiciones anteriores, el usuario está apuntado, por lo que creo una variable 
            de sesión de tipo error indicándolo, para luego mostrarlo */
            $_SESSION['errorAlert'] = "Ya estás apuntado en esta lista.";
            /* Redirijo al usuario a la página de la lista */
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        }
    } else {
        /* Si no se ha podido realizar la conexión a la base de datos, creo una variable de sesión para una alerta 
        de tipo error, indicando el error sucedido */
        $_SESSION['errorAlert'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta apuntarte a esta lista más tarde.";
        /* Redirijo al usuario a la página de la lista */
        header("Location: ../lista/?id=" . $usuario['ID_Lista']);
        /* Y con "exit" hago que se detenga el script, para que no ejecute el 
        resto de funciones */
        exit;
    }
} else {
    /* Si la petición no es de tipo POST, como no se puede sacar el ID de la lista afectada, 
    redirijo al usuario a la página principal */
    header('Location: ../');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
}
