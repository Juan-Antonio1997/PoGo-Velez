<?php
require_once('./config.php');
require_once('./db_pdo.php');
$db = db_open();
session_start();
if ($db) {
    $listas = db_query($db, "SELECT l.ID_Lista, l.Ubicacion, l.Hora_quedada, i.ID_Pokemon,
    i.Tipo_Raid, i.Maximo_participantes, i.Maximo_remotos_totales, p.Nombre, i.Shiny_activado
    FROM listas AS l
    INNER JOIN incursiones AS i ON l.ID_Raid = i.ID_Raid
    INNER JOIN pokemon AS p ON i.ID_Pokemon = p.ID_Pokemon
    WHERE DATE_ADD(l.Hora_quedada, INTERVAL 1 MINUTE) > NOW()
    ORDER BY l.Hora_quedada;");
    $hoy = date("d/m/Y");
    for ($i = 0; $i < count($listas); $i++) {
        #Obtengo el número de participantes por lista
        $participantes = db_query($db, "SELECT COUNT(Username) AS Num_Apuntados
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$listas[$i]['ID_Lista'], 'No voy']);
        $listas[$i]['Participantes'] = $participantes[0]['Num_Apuntados'];
        #Obtengo los invitados y los sumo
        $invitadoPresencial = db_query($db, "SELECT SUM(Invitado_presencial) AS Total_invitados_presenciales
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$listas[$i]['ID_Lista'], 'No voy']);
        $invitadoRemoto = db_query($db, "SELECT SUM(Invitado_remoto) AS Total_invitados_remotos
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$listas[$i]['ID_Lista'], 'No voy']);
        $listas[$i]['Participantes'] = $listas[$i]['Participantes'] + $invitadoPresencial[0]['Total_invitados_presenciales'] + $invitadoRemoto[0]['Total_invitados_remotos'];
        #Obtengo el número de apuntados remotos
        $remotos = db_query($db, "SELECT COUNT(Username) AS Num_Remotos
        FROM apuntados_lista WHERE ID_Lista = (?) AND Pase = (?) AND Estado != (?)", [$listas[$i]['ID_Lista'], 'Remoto', 'No voy']);
        $listas[$i]['Remotos'] = $remotos[0]['Num_Remotos'];
        #Sumo los invitados remotos
        $listas[$i]['Remotos'] = $listas[$i]['Remotos'] + $invitadoRemoto[0]['Total_invitados_remotos'];
        $fecha = strtotime($listas[$i]['Hora_quedada']);
        $listas[$i]['fecha'] = date("d/m/Y", $fecha);
        $listas[$i]['hora'] = date("H:i", $fecha);
    }
}