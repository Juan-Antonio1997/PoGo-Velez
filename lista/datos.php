<?php
/* Obtengo el PDO y la configuración para poder manipular bases de datos */
require_once('../config.php');
require_once('../db_pdo.php');
/* Abro la conexión a la base de datos indicada en el fichero de configuración */
$db = db_open();
/* Inicio la sesión (activo las variables $_SESSION) */
session_start();
/* Condición: Si hay conexión a la base de datos */
if ($db) {
    if (isset($_GET['id'])) {
        /* Si en el protocolo GET hay una variable "id", es decir, en la url aparece "/?id=", 
        obtengo los datos de esa lista */
        $lista = db_query($db, "SELECT p.*, u.Username, u.Pogo_Username, l.Ubicacion, l.Enlace_Maps, 
        l.Hora_creacion, l.Hora_quedada, l.Hora_inicio, l.Hora_fin, l.Tiempo_atmos, i.Tipo_Raid,
        i.Enlace_counters, i.Maximo_participantes, i.Maximo_remotos_totales, i.Maximo_remotos_por_apuntado, i.Shiny_activado
        FROM listas AS l
        INNER JOIN incursiones as i on l.ID_Raid = i.ID_RAID
        INNER JOIN pokemon AS p ON i.ID_Pokemon = p.ID_Pokemon
        INNER JOIN usuarios AS u ON l.Creado_por = u.Username
        WHERE ID_Lista = (?)", [$_GET['id']]);
        if (!empty($lista)) {
            /* Si la lista no está vacía, obtengo la fecha de hoy */
            $hoy = date("d/m/Y");
            /* Obtengo el número de participantes de esa lista */
            $numParticipantes = db_query($db, "SELECT COUNT(Username) AS Num_Apuntados
            FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$_GET['id'], "No voy"]);
            $lista[0]['numParticipantes'] = $numParticipantes[0]['Num_Apuntados'];
            /* Obtengo los invitados presenciales y los remotos, y luego los sumo al número de participantes */
            $invitadoPresencial = db_query($db, "SELECT SUM(Invitado_presencial) AS Total_invitados_presenciales
            FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$_GET['id'], "No voy"]);
            $invitadoRemoto = db_query($db, "SELECT SUM(Invitado_remoto) AS Total_invitados_remotos
            FROM apuntados_lista WHERE ID_Lista = (?) AND Estado != (?)", [$_GET['id'], "No voy"]);
            $lista[0]['numParticipantes'] = $lista[0]['numParticipantes'] + $invitadoPresencial[0]['Total_invitados_presenciales'] + $invitadoRemoto[0]['Total_invitados_remotos'];
            /* Obtengo el número de apuntados cuyo pase sea remoto */
            $numRemotos = db_query($db, "SELECT COUNT(Username) AS Num_Remotos
            FROM apuntados_lista WHERE ID_Lista = (?) AND Pase = (?) AND Estado != (?)", [$_GET['id'], "Remoto", "No voy"]);
            $lista[0]['numRemotos'] = $numRemotos[0]['Num_Remotos'];
            /* Sumo los invitados remotos al número de apuntados remotos */
            $lista[0]['numRemotos'] = $lista[0]['numRemotos'] + $invitadoRemoto[0]['Total_invitados_remotos'];
            /* Obtengo la marca de tiempo tipo Unix de la hora de quedada de la lista (si no, se mostraría en 
            el formato "Y/m/d H:i:s") */
            $fechasLista['fechaQuedada'] = strtotime($lista[0]['Hora_quedada']);
            /* A partir de esa marca de tiempo, obtengo la fecha de quedada de la lista en el formato deseado */
            $quedadaLista['fecha'] = date("d/m/Y", $fechasLista['fechaQuedada']);
            /* A partir de esa marca de tiempo, obtengo la hora de quedada de la lista en el formado deseado */
            $quedadaLista['hora'] = date("H:i", $fechasLista['fechaQuedada']);
            /* Obtengo la marca de tiempo tipo Unix de la hora de creación de la lista (si no, se mostraría en 
            el formato "Y/m/d H:i:s") */
            $fechasLista['fechaCreacion'] = strtotime($lista[0]['Hora_creacion']);
            /* A partir de esa marca de tiempo, obtengo la fecha de creación de la lista en el formato deseado */
            $creacionLista['fecha'] = date("d/m/Y", $fechasLista['fechaCreacion']);
            /* A partir de esa marca de tiempo, obtengo la hora de creación de la lista en el formado deseado */
            $creacionLista['hora'] = date("H:i", $fechasLista['fechaCreacion']);
            if (isset($lista[0]['Hora_inicio'])) {
                /* Si se ha indicado la hora de inicio, obtengo la marca de tiempo tipo Unix de esa hora (si no, 
                se mostraría en el formato "Y/m/d H:i:s") */
                $fechasLista['fechaInicio'] = strtotime($lista[0]['Hora_inicio']);
                /* A partir de esa marca de tiempo, obtengo la fecha de inicio de la lista en el formato deseado */
                $inicioLista['fecha'] = date("d/m/Y", $fechasLista['fechaInicio']);
                /* A partir de esa marca de tiempo, obtengo la hora de inicio de la lista en el formado deseado */
                $inicioLista['hora'] = date("H:i", $fechasLista['fechaInicio']);
            };
            if (isset($lista[0]['Hora_fin'])) {
                /* Si se ha indicado la hora de fin, obtengo la marca de tiempo tipo Unix de esa hora (si no, 
                se mostraría en el formato "Y/m/d H:i:s") */
                $fechasLista['fechaFin'] = strtotime($lista[0]['Hora_fin']);
                /* A partir de esa marca de tiempo, obtengo la fecha de fin de la lista en el formato deseado */
                $finLista['fecha'] = date("d/m/Y", $fechasLista['fechaFin']);
                /* A partir de esa marca de tiempo, obtengo la hora de fin de la lista en el formado deseado */
                $finLista['hora'] = date("H:i", $fechasLista['fechaFin']);
            };
            if (isset($lista[0]['Tiempo_atmos'])) {
                /* Si se ha indicado el tiempo atmosférico, creo una variable para mostrar los tipos potenciados 
                por un tiempo como atributo title, que será un match dependiendo del valor del tiempo atmosférico */
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
                /* Creo una variable para que, dependiendo del valor del tiempo atmosférico, poder sacar el icono del 
                tiempo atmosférico correspondiente */
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
            /* Obtengo la lista de apuntados de la lista */
            $apuntados = db_query($db, "SELECT a.Pase, a.Estado, u.Pogo_Username, u.Level, u.Team, a.Invitado_presencial, a.Invitado_remoto, 
            TIME_FORMAT(a.Hora_apuntado, '%H:%i') AS Hora_apuntado, TIME_FORMAT(a.Hora_ultimo_cambio, '%H:%i') AS Hora_ultimo_cambio FROM apuntados_lista AS a
            INNER JOIN usuarios AS u ON u.Username = a.Username
            WHERE a.ID_Lista = (?) AND a.Estado != (?) ORDER BY Hora_apuntado", [$_GET['id'], "No voy"]);
            /* Obtengo la lista de desapuntados de la lista */
            $desapuntados = db_query($db, "SELECT a.Pase, a.Estado, u.Pogo_Username, u.Level, u.Team,
            TIME_FORMAT(a.Hora_apuntado, '%H:%i') AS Hora_apuntado, TIME_FORMAT(a.Hora_ultimo_cambio, '%H:%i') AS Hora_ultimo_cambio FROM apuntados_lista AS a
            INNER JOIN usuarios AS u ON u.Username = a.Username
            WHERE a.ID_Lista = (?) AND a.Estado = (?) ORDER BY Hora_apuntado", [$_GET['id'], "No voy"]);
            /* Creo una variable de comprobación para ver si el ususuario está apuntado a la lista */
            $usuarioApuntado = False;
            /* Hago una consulta en la tabla "apuntados_lista" para ver si el usuario está apuntado en la lista */
            $comprobacionApuntado = db_query($db, "SELECT * FROM apuntados_lista WHERE ID_Lista = (?) AND Username = (?) AND Estado != (?)", [$_GET['id'], $_SESSION["usuario"], "No voy"]);
            if (!empty($comprobacionApuntado)) {
                /* Si está apuntado, cambio el valor de la variable de comprobación a "True" */
                $usuarioApuntado = True;
            }
            /* Creo una función para que, según el valor del estado, devuelva el emoji que le corresponde */
            function iconoEstado($estado) {
                if ($estado == "Voy") {
                    /* Si el estado es "Voy", tendrá este emoji */
                    return "🚶";
                } elseif ($estado == "Estoy") {
                    /* Si el estado es "Estoy", tendrá este emoji */
                    return "✅";
                } elseif ($estado == "Llego tarde") {
                    /* Si el estado es "Llego tarde", tendrá este emoji */
                    return "🐌";
                } else {
                    /* Si el estado es "No voy", tendrá este emoji */
                    return "✖️";
                }
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
                if ($_SESSION['usuario'] == $usuarioBorrarLista['Username']) {
                    /* Si el usuario puede borrar listas ajenas, cambio el valor de la variable de comprobación 
                    a "True" */
                    $borrarLista = True;
                }
            }
        }
    } else {
        /* Si no hay ningún dato del protocolo GET con el ID de una lista, redirijo al usuario 
        a la página principal */
        header('Location: ../');
        /* Y con "exit" hago que se detenga el script, para que no ejecute el 
        resto de funciones */
        exit;
    }
}
