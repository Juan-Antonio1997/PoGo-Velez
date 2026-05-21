<?php
require_once('../config.php');
require_once('../db_pdo.php');
$db = db_open();
session_start();
if ($db) {
    if (isset($_GET['id'])) {
        $lista = db_query($db, "SELECT p.*, u.Username, u.Pogo_Username, l.Ubicacion, l.Enlace_Maps, 
        l.Hora_creacion, l.Hora_quedada, l.Hora_inicio, l.Hora_fin, l.Tiempo_atmos, i.Tipo_Raid,
        i.Enlace_counters, i.Maximo_participantes, i.Maximo_remotos_totales, i.Maximo_remotos_por_apuntado, i.Shiny_activado
        FROM listas AS l
        INNER JOIN incursiones as i on l.ID_Raid = i.ID_RAID
        INNER JOIN pokemon AS p ON i.ID_Pokemon = p.ID_Pokemon
        INNER JOIN usuarios AS u ON l.Creado_por = u.Username
        WHERE ID_Lista = (?)", [$_GET['id']]);
        if (!empty($lista)) {
            $hoy = date("d/m/Y");
            #Obtengo el número de participantes por lista
            $numParticipantes = db_query($db, "SELECT COUNT(Username) AS Num_Apuntados
            FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$_GET['id'], "No voy"]);
            $lista[0]['numParticipantes'] = $numParticipantes[0]['Num_Apuntados'];
            #Obtengo los invitados y los sumo
            $invitadoPresencial = db_query($db, "SELECT SUM(Invitado_presencial) AS Total_invitados_presenciales
            FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$_GET['id'], "No voy"]);
            $invitadoRemoto = db_query($db, "SELECT SUM(Invitado_remoto) AS Total_invitados_remotos
            FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$_GET['id'], "No voy"]);
            $lista[0]['numParticipantes'] = $lista[0]['numParticipantes'] + $invitadoPresencial[0]['Total_invitados_presenciales'] + $invitadoRemoto[0]['Total_invitados_remotos'];
            #Obtengo el número de apuntados remotos
            $numRemotos = db_query($db, "SELECT COUNT(Username) AS Num_Remotos
            FROM apuntados_lista WHERE ID_Lista = (?) AND Pase = (?) AND Estado != (?)", [$_GET['id'], "Remoto", "No voy"]);
            $lista[0]['numRemotos'] = $numRemotos[0]['Num_Remotos'];
            #Sumo los invitados remotos
            $lista[0]['numRemotos'] = $lista[0]['numRemotos'] + $invitadoRemoto[0]['Total_invitados_remotos'];
            $fechasLista['fechaQuedada'] = strtotime($lista[0]['Hora_quedada']);
            $quedadaLista['fecha'] = date("d/m/Y", $fechasLista['fechaQuedada']);
            $quedadaLista['hora'] = date("H:i", $fechasLista['fechaQuedada']);
            $fechasLista['fechaCreacion'] = strtotime($lista[0]['Hora_creacion']);
            $creacionLista['fecha'] = date("d/m/Y", $fechasLista['fechaCreacion']);
            $creacionLista['hora'] = date("H:i", $fechasLista['fechaCreacion']);
            if (isset($lista[0]['Hora_inicio'])) {
                $fechasLista['fechaInicio'] = strtotime($lista[0]['Hora_inicio']);
                $inicioLista['fecha'] = date("d/m/Y", $fechasLista['fechaInicio']);
                $inicioLista['hora'] = date("H:i", $fechasLista['fechaInicio']);
            };
            if (isset($lista[0]['Hora_fin'])) {
                $fechasLista['fechaFin'] = strtotime($lista[0]['Hora_fin']);
                $finLista['fecha'] = date("d/m/Y", $fechasLista['fechaFin']);
                $finLista['hora'] = date("H:i", $fechasLista['fechaFin']);
            };
            #Creo una variable para mostrar los tipos potenciados por un tiempo como atributo title
            if (isset($lista[0]['Tiempo_atmos'])) {
                $descTiempo = match ($lista[0]['Tiempo_atmos']) {
                    "Soleado", "Despejado" => "Potencia a los tipos Planta, Tierra y Fuego",
                    "Parcialmente nublado (día)", "Parcialmente nublado (noche)" => "Potencia a los tipos Normal y Roca",
                    "Nublado" => "Potencia a los tipos Hada, Lucha y Veneno",
                    "Lluvia" => "Potencia a los tipos Agua, Eléctrico y Bicho",
                    "Viento" => "Potencia a los tipos Dragón, Volador y Psíquico",
                    "Niebla" => "Potencia a los tipos Fantasma y Siniestro",
                    "Nieve" => "Potencia a los tipos Hielo y Acero",
                    "Extremo" => "No potencia ningún tipo"
                };
                $weatherImage = match ($lista[0]['Tiempo_atmos']) {
                    "Soleado" => "Clear_Day",
                    "Despejado" => "Clear_Night",
                    "Parcialmente nublado (día)" => "Partly_Cloudy_Day",
                    "Parcialmente nublado (noche)" => "Partly_Cloudy_Night",
                    "Nublado" => "Cloudy",
                    "Lluvia" => "Rain",
                    "Viento" => "Windy",
                    "Niebla" => "Foggy",
                    "Nieve" => "Snow",
                    "Extremo" => "Extreme"
                };
            };
            $apuntados = db_query($db, "SELECT a.Pase, a.Estado, u.Pogo_Username, u.Level, u.Team, a.Invitado_presencial, a.Invitado_remoto, 
            TIME_FORMAT(a.Hora_apuntado, '%H:%i') AS Hora_apuntado, TIME_FORMAT(a.Hora_ultimo_cambio, '%H:%i') AS Hora_ultimo_cambio FROM apuntados_lista AS a
            INNER JOIN usuarios AS u ON u.Username = a.Username
            WHERE a.ID_Lista = (?) AND a.Estado != (?) ORDER BY Hora_apuntado", [$_GET['id'], "No voy"]);
            $desapuntados = db_query($db, "SELECT a.Pase, a.Estado, u.Pogo_Username, u.Level, u.Team,
            TIME_FORMAT(a.Hora_apuntado, '%H:%i') AS Hora_apuntado, TIME_FORMAT(a.Hora_ultimo_cambio, '%H:%i') AS Hora_ultimo_cambio FROM apuntados_lista AS a
            INNER JOIN usuarios AS u ON u.Username = a.Username
            WHERE a.ID_Lista = (?) AND a.Estado = (?) ORDER BY Hora_apuntado", [$_GET['id'], "No voy"]);
            $usuarioApuntado = False;
            $comprobacionApuntado = db_query($db, "SELECT * FROM apuntados_lista WHERE ID_Lista = (?) AND Username = (?) AND Estado != (?)", [$_GET['id'], $_SESSION["usuario"], "No voy"]);
            if (!empty($comprobacionApuntado)) {
                $usuarioApuntado = True;
            }
            function iconoEstado($estado)
            {
                if ($estado == "Voy") {
                    return "🚶";
                } elseif ($estado == "Estoy") {
                    return "✅";
                } elseif ($estado == "Llego tarde") {
                    return "🐌";
                } else {
                    return "✖️";
                }
            }
            $borrarLista = False;
            $usuariosBorrarLista = db_query($db, "SELECT u.Username, p.P_BorrarListasAjenas 
            FROM usuarios AS u
            INNER JOIN perfiles AS p on p.ID_Perfil = u.ID_Perfil
            WHERE p.P_BorrarListasAjenas = (?)", [1]);
            foreach ($usuariosBorrarLista as $usuarioBorrarLista) {
                if ($_SESSION['usuario'] == $usuarioBorrarLista['Username']) {
                    $borrarLista = True;
                }
            }
        }
    } else {
        header('Location: ../');
        exit;
    }
    #print_r($comprobacionApuntado[0]);
}