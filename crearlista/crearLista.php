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
    $_SESSION['warningAlert'] = "¡Tienes que iniciar sesión antes de poder crear una lista!";
    /* Redirijo al usuario a la página de inicio de sesión */
    header('Location: ../login');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /* Si la petición es de tipo POST, guardo las variables de POST en la variable "lista" */
    $lista['ID_Raid'] = $_POST['ID_Raid'];
    $lista['Creado_por'] = $_SESSION['usuario'];
    $lista['Ubicacion'] = $_POST['Ubicacion'];
    /* Si se ha introducido un enlace de Google Maps, lo guardo en la variable "lista" */
    if (strlen($_POST['Enlace_Maps']) > 0) {
        $lista['Enlace_Maps'] = $_POST['Enlace_Maps'];
    };
    /* Obtengo la fecha de hoy */
    $currDate = date("Y-m-d");
    /* Obtengo la hora actual */
    $currTime = date("H:i:s");
    /* Hago que la hora de creación sea la fecha de hoy y la hora actual */
    $lista['Hora_creacion'] = $currDate . " " . $currTime;
    /* Hago que la hora de la quedada sea la fecha de hoy, y la hora indicada en el formulario */
    $lista['Hora_quedada'] = $currDate . " " . $_POST['Hora_quedada'] . ":00";
    /* Creo una variable para validar la hora de la quedadda */
    $validateTime = False;
    if (strtotime($lista['Hora_creacion']) < strtotime($lista['Hora_quedada'])) {
        /* Compruebo que la hora de la quedada sea posterior a la hora de creación. Si es así, 
        cambio el valor de la variable de validación a "True" */
        $validateTime = True;
    }
    /* Si se ha indicado la hora de inicio, la guardo en la variable "lista" */
    if (strlen($_POST['Hora_inicio']) > 0) {
        $lista['Hora_inicio'] = $currDate . " " . $_POST['Hora_inicio'] . ":00";
    };
    /* Si se ha indicado la hora de fin, la guardo en la variable "lista" */
    if (strlen($_POST['Hora_fin']) > 0) {
        $lista['Hora_fin'] = $currDate . " " . $_POST['Hora_fin'] . ":00";
    }
    $lista['Tiempo_atmos'] = $_POST['Tiempo_atmos'];
    /* Abro la conexión a la base de datos indicada en el fichero de configuración */
    $db = db_open();
    /* Condición: Si hay conexión a la base de datos */
    if ($db) {
        if ($validateTime) {
            /* Si la hora de quedada es válida, inserto los datos de la quedada en la tabla "listas" */
            $id = db_insert($db, 'listas', $lista);
            /* Luego creo una variable "apuntado" para los datos del apuntado, incluyendo el id de la
            lista que se ha creado */
            $apuntado['ID_Lista'] = $id;
            $apuntado['Username'] = $_SESSION['usuario'];
            $apuntado['Pase'] = $_POST['Pase'];
            $apuntado['Estado'] = "Voy";
            $apuntado['Hora_apuntado'] = $lista['Hora_creacion'];
            $apuntado['Hora_ultimo_cambio'] = $lista['Hora_creacion'];
            /* Inserto los datos del usuario en la tabla "apuntados_lista" para apuntar al usuario en la 
            lista que ha creado */
            $id2 = db_insert($db, 'apuntados_lista', $apuntado);
            /* Cierro la conexión a la base de datos */
            db_close($db);
            /* Creo una variable de sesión para una alerta de tipo éxito indicando que se ha creado la lista */
            $_SESSION['successAlert'] = "¡Se ha creado la lista con éxito! Su ID es: " . $id;
            /* Redirijo al usuario a la lista que ha creado */
            header("Location: ../lista/?id=" . $id);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        } else {
            /* Si no es válida, creo una variable de sesión para una alerta de tipo error que indique por qué no 
            se ha creado la lista */
            $_SESSION['errorAlert'] = "La hora de la quedada no es válida. Tiene que ser posterior a la hora actual.";
            /* Redirijo al usuario a la página de creación de listas */
            header("Location: ../crearlista");
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        }
    } else {
        /* Si no se ha podido realizar la conexión a la base de datos, creo una variable de sesión para una alerta 
        de tipo error, indicando el error sucedido */
        $_SESSION['errorAlert'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta crear la lista más tarde.";
        /* Redirijo al usuario a la página de creación de listas */
        header("Location: ../crearlista");
        /* Y con "exit" hago que se detenga el script, para que no ejecute el 
        resto de funciones */
        exit;
    }
} else {
    /* Si la petición no es de tipo POST, simplemente redirijo al usuario a la página de creación de listas */
    header('Location: ../crearLista');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
}