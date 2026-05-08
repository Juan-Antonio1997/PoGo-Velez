<?php
require_once('../config.php');
require_once('../db_pdo.php');
session_start();
date_default_timezone_set('Europe/Madrid');
if (!isset($_SESSION['usuario'])) {
    $_SESSION['advertencia'] = "¡Tienes que iniciar sesión antes de poder hacer cambios en una lista!";
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
        $lista = db_query($db, "SELECT i.Maximo_participantes, i.Maximo_remotos_totales, i.Maximo_remotos_por_apuntado
        FROM listas AS l
        INNER JOIN incursiones as i on l.ID_Raid = i.ID_RAID
        WHERE ID_Lista = (?)", [$usuario['ID_Lista']]);
        #Obtengo el número de participantes por lista
        $numParticipantes = db_query($db, "SELECT COUNT(Username) AS Num_Apuntados
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$usuario['ID_Lista'], "No voy"]);
        $lista[0]['numParticipantes'] = $numParticipantes[0]['Num_Apuntados'];
        #Obtengo los invitados y los sumo
        $invitadoPresencial = db_query($db, "SELECT SUM(Invitado_presencial) AS Total_invitados_presenciales
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$usuario['ID_Lista'], "No voy"]);
        $invitadoRemoto = db_query($db, "SELECT SUM(Invitado_remoto) AS Total_invitados_remotos
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$usuario['ID_Lista'], "No voy"]);
        $lista[0]['numParticipantes'] = $lista[0]['numParticipantes'] + $invitadoPresencial[0]['Total_invitados_presenciales'] + $invitadoRemoto[0]['Total_invitados_remotos'];
        #Obtengo el número de apuntados remotos
        $numRemotos = db_query($db, "SELECT COUNT(Username) AS Num_Remotos
        FROM apuntados_lista WHERE ID_Lista = (?) AND Pase = (?) AND Estado != (?)", [$usuario['ID_Lista'], "Remoto", "No voy"]);
        $lista[0]['numRemotos'] = $numRemotos[0]['Num_Remotos'];
        #Sumo los invitados remotos
        $lista[0]['numRemotos'] = $lista[0]['numRemotos'] + $invitadoRemoto[0]['Total_invitados_remotos'];
        if (!empty($comprobacionApuntado) && $_POST['Tipo_Invitado'] == "Presencial") {
            $usuario['Invitado_presencial'] = $_POST['Invitado_presencial'];
            if ($lista[0]['numParticipantes'] - $comprobacionApuntado[0]['Invitado_presencial'] + $_POST['Invitado_presencial'] <= $lista[0]['Maximo_participantes']) {
                $updateInvitadoPresencial = db_query($db, "UPDATE apuntados_lista
                SET Invitado_presencial = (?), Hora_ultimo_cambio = (?)
                WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Invitado_presencial'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
                db_close($db);
                header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            } else {
                print "Error presencial";
            }
        } elseif (!empty($comprobacionApuntado) && $_POST['Tipo_Invitado'] == "Remoto") {
            $usuario['Invitado_remoto'] = $_POST['Invitado_remoto'];
            if ($lista[0]['numRemotos'] - $comprobacionApuntado[0]['Invitado_remoto'] + $_POST['Invitado_remoto'] <= $lista[0]['Maximo_remotos_totales'] && $_POST['Invitado_remoto'] <= $lista[0]['Maximo_remotos_por_apuntado']) {
                $updateInvitadoRemoto = db_query($db, "UPDATE apuntados_lista
                SET Invitado_remoto = (?), Hora_ultimo_cambio = (?)
                WHERE ID_Lista = (?) AND Username = (?)", [$usuario['Invitado_remoto'], $usuario['Hora_ultimo_cambio'], $usuario['ID_Lista'], $usuario['Username']]);
                db_close($db);
                header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            } else {
                print "Error remoto";
            }
        } else {
            print "Error";
            exit;
        }
    } else {
        print "Se ha producido un error de conexión";
        exit;
    }
}
