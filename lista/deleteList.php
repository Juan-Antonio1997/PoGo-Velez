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
    $_SESSION['warningAlert'] = "¡Tienes que iniciar sesión antes de poder borrar una lista!";
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
    /* Abro la conexión a la base de datos indicada en el fichero de configuración */
    $db = db_open();
    /* Condición: Si hay conexión a la base de datos */
    if ($db) {
        /* Creo una variable para comprobar que quién está haciendo esta petición es el creador de la lista */
        $comprobacionCreador = False;
        /* Obtengo el usuario que ha creado la lista */
        $lista = db_query($db, "SELECT Creado_por FROM listas WHERE ID_Lista = (?)", [$usuario['ID_Lista']]);
        if ($lista[0]['Creado_por'] == $usuario['Username']) {
            /* Si el creador de la lista es el mismo que el usuario realizando la petición, marco el valor de 
            la variable de comprobación a "True" */
            $comprobacionCreador = True;
        }
        /* Creo una variable para comprobar que el usuario puede borrar listas ajenas */
        $borrarLista = False;
        /* Obtengo la lista de usuarios que pueden borrar listas ajenas */
        $usuariosBorrarLista = db_query($db, "SELECT u.Username, p.P_BorrarListasAjenas 
        FROM usuarios AS u
        INNER JOIN perfiles AS p on p.ID_Perfil = u.ID_Perfil
        WHERE p.P_BorrarListasAjenas = (?)", [1]);
        /* Abro un bucle for each para recorrer la lista de usuarios que pueden borrar listas ajenas */
        foreach ($usuariosBorrarLista as $usuarioBorrarLista) {
            if ($usuario['Username'] == $usuarioBorrarLista['Username']) {
                /* Si el usuario puede borrar listas ajenas, cambio el valor de la variable de comprobación 
                a "True" */
                $borrarLista = True;
            }
        }
        if ($comprobacionCreador || $borrarLista) {
            /* Si el usuario es el creador de la lista o tiene permisos para borrar listas ajenas, 
            quito a todos los participantes de esa lista a través de la tabla intermedia "apuntados_lista" */
            $removeParticipants = db_query($db, "DELETE FROM apuntados_lista
            WHERE ID_Lista = (?)", [$usuario['ID_Lista']]);
            /* Luego borro la lista de la tabla "listas" */
            $deleteList = db_delete_by_id($db, 'listas', $usuario['ID_Lista'], "ID_Lista");
            /* Cierro la conexión a la base de datos */
            db_close($db);
            /* Creo una variable de sesión para una alerta de tipo éxito indicando que se ha borrado la lista */
            $_SESSION['successAlert'] = "Se ha borrado la lista con ID " . $usuario['ID_Lista'] . " con éxito";
            /* Redirijo al usuario a la página principal */
            header("Location: ../");
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        } else {
            /* Si no se cumple ninguna de las dos condiciones anteriores, el usuario no tiene permisos para borrar 
            la lista, por lo que creo una variable de sesión para una alerta de tipo error indicándolo */
            $_SESSION['errorAlert'] = "No tienes permisos para borrar esta lista.";
            /* Redirijo al usuario a la página de la lista */
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        }
    } else {
        /* Si no se ha podido realizar la conexión a la base de datos, creo una variable de sesión para una alerta 
        de tipo error, indicando el error sucedido */
        $_SESSION['errorAlert'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta borrar esta lista más tarde.";
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
