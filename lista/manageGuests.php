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
            $usuario['Invitado_presencial'] = $_POST['Invitado_presencial'];
            if ($lista[0]['numParticipantes'] - $comprobacionApuntado[0]['Invitado_presencial'] + $_POST['Invitado_presencial'] <= $lista[0]['Maximo_participantes']) {
                $updateInvitadoPresencial = db_query($db, "UPDATE apuntados_lista
                SET Invitado_presencial = (?), Hora_ultimo_cambio = (?)
                WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Invitado_presencial'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
                db_close($db);
                $_SESSION['successAlert'] = "He cambiado el número de personas que te van a acompañar de forma presencial a: " . $usuario['Invitado_presencial'];
                header("Location: ../lista/?id=" . $usuario['ID_Lista']);
                /* Y con "exit" hago que se detenga el script, para que no ejecute el 
                resto de funciones */
                exit;
            } else {
                $_SESSION['errorAlert'] = "Ha ocurrido un error al intentar añadir invitados presenciales: El número de participantes totales habría excedido el máximo permitido si se hubiesen añadido esos invitados";
                header("Location: ../lista/?id=" . $usuario['ID_Lista']);
                /* Y con "exit" hago que se detenga el script, para que no ejecute el 
                resto de funciones */
                exit;
            }
        } elseif (!empty($comprobacionApuntado) && $_POST['Tipo_Invitado'] == "Remoto") {
            $usuario['Invitado_remoto'] = $_POST['Invitado_remoto'];
            if ($lista[0]['numParticipantes'] - $comprobacionApuntado[0]['Invitado_remoto'] + $_POST['Invitado_remoto'] <= $lista[0]['Maximo_participantes'] && $lista[0]['numRemotos'] - $comprobacionApuntado[0]['Invitado_remoto'] + $_POST['Invitado_remoto'] <= $lista[0]['Maximo_remotos_totales'] && $_POST['Invitado_remoto'] <= $lista[0]['Maximo_remotos_por_apuntado']) {
                $updateInvitadoRemoto = db_query($db, "UPDATE apuntados_lista
                SET Invitado_remoto = (?), Hora_ultimo_cambio = (?)
                WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Invitado_remoto'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
                db_close($db);
                $_SESSION['successAlert'] = "He cambiado el número de personas que vas a invitar de forma remota a: " . $usuario['Invitado_remoto'];
                header("Location: ../lista/?id=" . $usuario['ID_Lista']);
                /* Y con "exit" hago que se detenga el script, para que no ejecute el 
                resto de funciones */
                exit;
            } else {
                $_SESSION['errorAlert'] = "Ha ocurrido un error al intentar añadir invitados remotos: El número de participantes remotos totales habría excedido el máximo permitido si se hubiesen añadido esos invitados";
                header("Location: ../lista/?id=" . $usuario['ID_Lista']);
                /* Y con "exit" hago que se detenga el script, para que no ejecute el 
                resto de funciones */
                exit;
            }
        } else {
            $_SESSION['errorAlert'] = "SE ha producido un error al intentar cambiar el número de invitados: Inténtalo de nuevo más tarde";
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        }
    } else {
        $_SESSION['errorAlert'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta cambiar el número de personas que vas a invitar a esta lista más tarde.";
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
