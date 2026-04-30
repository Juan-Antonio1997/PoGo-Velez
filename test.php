<?php
require_once('./config.php');
require_once('./db_pdo.php');
$db = db_open();
session_start();
if ($db) {
    $listas = db_query($db, "SELECT l.ID_Lista, l.Ubicacion, l.Hora_quedada, i.ID_Pokemon, i.Tipo_Raid, i.Maximo_participantes, i.Maximo_remotos, p.Nombre 
    FROM listas AS l
    INNER JOIN incursiones AS i ON l.ID_Raid = i.ID_Raid
    INNER JOIN pokemon AS p ON i.ID_Pokemon = p.ID_Pokemon;");
    $hoy = date("d/m/Y");
    for ($i = 0; $i < count($listas); $i++) {
        $fecha = strtotime($listas[$i]['Hora_quedada']);
        $listas[$i]['fecha'] = date("d/m/Y", $fecha);
        $listas[$i]['hora'] = date("H:i", $fecha);
    }
    print_r($listas);
    print '<br>'.'Hoy: '.$hoy;
}
