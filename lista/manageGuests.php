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
        /* Compruebo que el usuario esté apuntado en la lista */
        $comprobacionApuntado = db_query($db, "SELECT * FROM apuntados_lista WHERE ID_Lista = (?) AND Username = (?) AND Estado != (?)", [$usuario['ID_Lista'], $usuario['Username'], "No voy"]);
        /* Obtengo el máximo de participantes, remotos totales y remotos que cada usuario puede inviar de la lista */
        $lista = db_query($db, "SELECT i.Maximo_participantes, i.Maximo_remotos_totales, i.Maximo_remotos_por_apuntado
        FROM listas AS l
        INNER JOIN incursiones as i on l.ID_Raid = i.ID_RAID
        WHERE ID_Lista = (?)", [$usuario['ID_Lista']]);
        /* Obtengo el número de participantes de esa lista */
        $numParticipantes = db_query($db, "SELECT COUNT(Username) AS Num_Apuntados
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$usuario['ID_Lista'], "No voy"]);
        $lista[0]['numParticipantes'] = $numParticipantes[0]['Num_Apuntados'];
        /* Obtengo los invitados presenciales y los remotos, y luego los sumo al número de participantes */
        $invitadoPresencial = db_query($db, "SELECT SUM(Invitado_presencial) AS Total_invitados_presenciales
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$usuario['ID_Lista'], "No voy"]);
        $invitadoRemoto = db_query($db, "SELECT SUM(Invitado_remoto) AS Total_invitados_remotos
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$usuario['ID_Lista'], "No voy"]);
        $lista[0]['numParticipantes'] = $lista[0]['numParticipantes'] + $invitadoPresencial[0]['Total_invitados_presenciales'] + $invitadoRemoto[0]['Total_invitados_remotos'];
        /* Obtengo el número de apuntados cuyo pase sea remoto */
        $numRemotos = db_query($db, "SELECT COUNT(Username) AS Num_Remotos
        FROM apuntados_lista WHERE ID_Lista = (?) AND Pase = (?) AND Estado != (?)", [$usuario['ID_Lista'], "Remoto", "No voy"]);
        $lista[0]['numRemotos'] = $numRemotos[0]['Num_Remotos'];
        /* Sumo los invitados remotos al número de apuntados remotos */
        $lista[0]['numRemotos'] = $lista[0]['numRemotos'] + $invitadoRemoto[0]['Total_invitados_remotos'];
        if (!empty($comprobacionApuntado) && $_POST['Tipo_Invitado'] == "Presencial") {
            /* Si el usuario está apuntado, y va a añadir invitados presenciales, los guardo en la variable "usuario" */
            $usuario['Invitado_presencial'] = $_POST['Invitado_presencial'];
            if ($lista[0]['numParticipantes'] - $comprobacionApuntado[0]['Invitado_presencial'] + $_POST['Invitado_presencial'] <= $lista[0]['Maximo_participantes']) {
                /* Si el número de participantes tras añadir esos invitados no supera el máximo de participantes, 
                actualizo la tabla "apuntados_lista" con el valor de los invitados presenciales que va a invitar 
                el usuario */
                $updateInvitadoPresencial = db_query($db, "UPDATE apuntados_lista
                SET Invitado_presencial = (?), Hora_ultimo_cambio = (?)
                WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Invitado_presencial'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
                /* Cierro la conexión a la base de datos */
                db_close($db);
                /* Creo una variable de sesión para una alerta de tipo éxito indicando que se ha realizado el cambio */
                $_SESSION['successAlert'] = "He cambiado el número de personas que te van a acompañar de forma presencial a: " . $usuario['Invitado_presencial'];
                /* Redirijo al usuario a la página de la lista */
                header("Location: ../lista/?id=" . $usuario['ID_Lista']);
                /* Y con "exit" hago que se detenga el script, para que no ejecute el 
                resto de funciones */
                exit;
            } else {
                /* Si no se cumple esa condición, creo una variable de sesión para una alerta de tipo error 
                indicando lo sucedido */
                $_SESSION['errorAlert'] = "Ha ocurrido un error al intentar añadir invitados presenciales: El número de participantes totales habría excedido el máximo permitido si se hubiesen añadido esos invitados";
                /* Redirijo al usuario a la página de la lista */
                header("Location: ../lista/?id=" . $usuario['ID_Lista']);
                /* Y con "exit" hago que se detenga el script, para que no ejecute el 
                resto de funciones */
                exit;
            }
        } elseif (!empty($comprobacionApuntado) && $_POST['Tipo_Invitado'] == "Remoto") {
            /* Si el usuario está apuntado, y va a añadir invitados remotos, los guardo en la variable "usuario" */
            $usuario['Invitado_remoto'] = $_POST['Invitado_remoto'];
            if ($lista[0]['numParticipantes'] - $comprobacionApuntado[0]['Invitado_remoto'] + $_POST['Invitado_remoto'] <= $lista[0]['Maximo_participantes'] && $lista[0]['numRemotos'] - $comprobacionApuntado[0]['Invitado_remoto'] + $_POST['Invitado_remoto'] <= $lista[0]['Maximo_remotos_totales'] && $_POST['Invitado_remoto'] <= $lista[0]['Maximo_remotos_por_apuntado']) {
                /* Si el número de participantes totales tras añadir esos invitados no supera el máximo de participantes, 
                ni supera el máximo de remotos totales ni tampoco el máximo de invitados remotos por usuario, actualizo 
                la tabla "apuntados_lista" con el valor de los invitados remotos que va a invitar el usuario */
                $updateInvitadoRemoto = db_query($db, "UPDATE apuntados_lista
                SET Invitado_remoto = (?), Hora_ultimo_cambio = (?)
                WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Invitado_remoto'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
                /* Cierro la conexión a la base de datos */
                db_close($db);
                /* Creo una variable de sesión para una alerta de tipo éxito indicando que se ha realizado el cambio */
                $_SESSION['successAlert'] = "He cambiado el número de personas que vas a invitar de forma remota a: " . $usuario['Invitado_remoto'];
                /* Redirijo al usuario a la página de la lista */
                header("Location: ../lista/?id=" . $usuario['ID_Lista']);
                /* Y con "exit" hago que se detenga el script, para que no ejecute el 
                resto de funciones */
                exit;
            } else {
                /* Si no se cumple cualquiera de las tres condiciones anteriores, creo una variable de sesión para una 
                alerta de tipo error indicando lo sucedido */
                $_SESSION['errorAlert'] = "Ha ocurrido un error al intentar añadir invitados remotos: El número de participantes remotos totales habría excedido el máximo permitido si se hubiesen añadido esos invitados";
                /* Redirijo al usuario a la página de la lista */
                header("Location: ../lista/?id=" . $usuario['ID_Lista']);
                /* Y con "exit" hago que se detenga el script, para que no ejecute el 
                resto de funciones */
                exit;
            }
        } else {
            /* Si el usuario no está apuntado o el tipo de invitado no es ni "Presencial" ni "Remoto", creo una variable 
            de sesión para una alerta de tipo error indicando que se ha producido un error */
            $_SESSION['errorAlert'] = "Se ha producido un error al intentar cambiar el número de invitados: Inténtalo de nuevo más tarde";
            /* Redirijo al usuario a la página de la lista */
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        }
    } else {
        /* Si no se ha podido realizar la conexión a la base de datos, creo una variable de sesión para una alerta 
        de tipo error, indicando el error sucedido */
        $_SESSION['errorAlert'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta cambiar el número de personas que vas a invitar a esta lista más tarde.";
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
