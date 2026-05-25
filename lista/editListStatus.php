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
    /* Si la petición es de tipo POST, guardo las variables de POST en la variable "lista" */
    $usuario['ID_Lista'] = $_POST['ID_Lista'];
    $usuario['Username'] = $_SESSION['usuario'];
    /* Obtengo la fecha de hoy */
    $currDate = date("Y-m-d");
    /* Obtengo la hora actual */
    $currTime = date("H:i:s");
    /* Hago que la hora en la que el usuario ha hecho su último cambio en la lista 
    sea la fecha de hoy y la hora actual */
    $usuario['Hora_ultimo_cambio'] = $currDate . " " . $currTime;
    /* Abro la conexión a la base de datos indicada en el fichero de configuración */
    $db = db_open();
    /* Condición: Si hay conexión a la base de datos */
    if ($db) {
        /* Hago una consulta para comprobar que el usuario está apuntado a la lista */
        $comprobacionApuntado = db_query($db, "SELECT * FROM apuntados_lista WHERE ID_Lista = (?) AND Username = (?) AND Estado != (?)", [$usuario['ID_Lista'], $usuario['Username'], "No voy"]);
        if (!empty($comprobacionApuntado) && $_POST['Funcion'] == "modificarPase") {
            /* Si el usuario está apuntado, y los datos de POST vienen de la sección para cambiar el tipo de pase, 
            añado el valor del pase a la variable "usuario" */
            $usuario['Pase'] = $_POST['Pase'];
            /* Actualizo la tabla "apuntados_lista" para cambiar el tipo de pase, y cambiar la hora en la que se 
            realizó su último cambio */
            $updatePass = db_query($db, "UPDATE apuntados_lista
            SET Pase = (?), Hora_ultimo_cambio = (?)
            WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Pase'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
            /* Cierro la conexión a la base de datos */
            db_close($db);
            /* Creo una variable de sesión para una alerta de tipo éxito indicando al usuario el cambio realizado */
            $_SESSION['successAlert'] = "He cambiado tu tipo de participación a: " . $usuario['Pase'];
            /* Redirijo al usuario a la página de la lista */
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        } elseif (!empty($comprobacionApuntado) && $_POST['Funcion'] == "modificarEstado") {
            /* Si el usuario está apuntado, y los datos de POST vienen de la sección para modificar el estado del 
            usuario, añado el valor del estado a la variable "usuario" */
            $usuario['Estado'] = $_POST['Estado'];
            /* Actualizo la tabla "apuntados_lista" para cambiar ep estado del usuario, y cambiar la hora en la que 
            se realizó su último cambio */
            $updateStatus = db_query($db, "UPDATE apuntados_lista
            SET Estado = (?), Hora_ultimo_cambio = (?)
            WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Estado'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
            /* Cierro la conexión a la base de datos */
            db_close($db);
            /* Creo una variable de sesión para una alerta de tipo éxito indicando al usuario el cambio realizado */
            $_SESSION['successAlert'] = "He cambiado tu estado a: " . $usuario['Estado'];
            if ($usuario['Estado'] == "Llego tarde") {
                /* Si el estado del usuario es "Llego tarde", creo una variable de sesión para una alerta de tipo 
                advertencia indicando al usuario que los apuntados no tienen que esperarle (por si alguien tiene prisa, 
                por ejemplo) */
                $_SESSION['warningAlert'] = "Ten en cuenta que los apuntados no están obligados a esperarte si llegas tarde";
            }
            /* Redirijo al usuario a la página de la lista */
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        } else {
            /* Si no está apuntado el usuario o la función no es ninguna de las mostradas en las condiciones, 
            creo una variable de sesión para una alerta de tipo error indicando que ha habido un error al cambiar 
            el estado de ese usuario en esa lista */
            $_SESSION['errorAlert'] = "Se ha producido un error al intentar cambiar tu estado en esta lista. Por favor, inténtalo de nuevo más tarde.";
            /* Redirijo al usuario a la página de la lista */
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        }
    } else {
        /* Si no se ha podido realizar la conexión a la base de datos, creo una variable de sesión para una alerta 
        de tipo error, indicando el error sucedido */
        $_SESSION['errorAlert'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta editar tu estado en esta lista más tarde.";
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
