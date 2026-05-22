<?php
/* Obtengo el PDO y la configuración para poder manipular bases de datos */
require_once('./config.php');
require_once('./db_pdo.php');
/* Abro la conexión a la base de datos indicada en el fichero de configuración */
$db = db_open();
/* Inicio la sesión (activo las variables $_SESSION) */
session_start();
/* Condición: Si hay conexión a la base de datos */
if ($db) {
    /* Obtengo los datos que voy a mostrar en la página principal de las listas 
    cuya hora de quedada no sea posterior a la hora actual + 1 minuto */
    $listas = db_query($db, "SELECT l.ID_Lista, l.Ubicacion, l.Hora_quedada, i.ID_Pokemon,
    i.Tipo_Raid, i.Maximo_participantes, i.Maximo_remotos_totales, p.Nombre, i.Shiny_activado
    FROM listas AS l
    INNER JOIN incursiones AS i ON l.ID_Raid = i.ID_Raid
    INNER JOIN pokemon AS p ON i.ID_Pokemon = p.ID_Pokemon
    WHERE DATE_ADD(l.Hora_quedada, INTERVAL 1 MINUTE) > NOW()
    ORDER BY l.Hora_quedada;");
    /* Obtengo la fecha de hoy para realizar comparaciones */
    $hoy = date("d/m/Y");
    /* Abro un bucle for para sacar el número de participantes por cada lista */
    for ($i = 0; $i < count($listas); $i++) {
        /* Obtengo el número de participantes por lista */
        $participantes = db_query($db, "SELECT COUNT(Username) AS Num_Apuntados
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$listas[$i]['ID_Lista'], 'No voy']);
        $listas[$i]['Participantes'] = $participantes[0]['Num_Apuntados'];
        /* Obtengo los invitados presenciales y los remotos, y luego los sumo al número de participantes */
        $invitadoPresencial = db_query($db, "SELECT SUM(Invitado_presencial) AS Total_invitados_presenciales
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$listas[$i]['ID_Lista'], 'No voy']);
        $invitadoRemoto = db_query($db, "SELECT SUM(Invitado_remoto) AS Total_invitados_remotos
        FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$listas[$i]['ID_Lista'], 'No voy']);
        $listas[$i]['Participantes'] = $listas[$i]['Participantes'] + $invitadoPresencial[0]['Total_invitados_presenciales'] + $invitadoRemoto[0]['Total_invitados_remotos'];
        /* Obtengo el número de apuntados cuyo pase sea remoto */
        $remotos = db_query($db, "SELECT COUNT(Username) AS Num_Remotos
        FROM apuntados_lista WHERE ID_Lista = (?) AND Pase = (?) AND Estado != (?)", [$listas[$i]['ID_Lista'], 'Remoto', 'No voy']);
        $listas[$i]['Remotos'] = $remotos[0]['Num_Remotos'];
        /* Sumo los invitados remotos al número de apuntados remotos */
        $listas[$i]['Remotos'] = $listas[$i]['Remotos'] + $invitadoRemoto[0]['Total_invitados_remotos'];
        /* Convierto la hora de la quedada en la marca de tiempo de Unix */
        $fecha = strtotime($listas[$i]['Hora_quedada']);
        /* Y de esa marca de tiempo extraigo la fecha */
        $listas[$i]['fecha'] = date("d/m/Y", $fecha);
        /* Y luego la hora (pero solo mostrando las horas y los minutos) */
        $listas[$i]['hora'] = date("H:i", $fecha);
    }
}
