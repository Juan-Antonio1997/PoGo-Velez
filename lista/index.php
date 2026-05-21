<?php
require_once('../config.php');
require_once('../db_pdo.php');
$db = db_open();
session_start();
if (!isset($_SESSION['usuario'])) {
    $_SESSION['advertencia'] = "¡Tienes que iniciar sesión antes de poder acceder a una lista!";
    header('Location: ../login');
}
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
    }
    #print_r($comprobacionApuntado[0]);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PoGO Vélez-Málaga - Lista <?= $_GET['id'] ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header>
        <a href="../" class="titleLink">
            <h1 class="pageTitle">PoGo Vélez-Málaga</h1>
        </a>
    </header>
    <nav>
        <?php if (isset($_SESSION['usuario'])): ?>
            <ul class="navList">
                <li class="navElement"><a href="../logout">Cerrar sesión</a></li>
                <li class="navElement"><span>¡Hola <?= $_SESSION['usuario'] ?>!</span></li>
            </ul>
        <?php else: ?>
            <ul class="navList">
                <li class="navElement"><a href="../registro">Regístrate</a></li>
                <li class="navElement"><a href="../login">Iniciar sesión</a></li>
            </ul>
        <?php endif; ?>
    </nav>
    <section>
        <article>
            <?php if (isset($_SESSION['listaCreada'])): ?>
            <div class="alertBox" id="createdListAlert">
                <div class="alertSuccess">
                    <span class="closeAlertBtn">&times;</span>
                    <?= $_SESSION['listaCreada'] ?>
                </div>
            </div>
            <?php unset($_SESSION['listaCreada']) ?>
        <?php endif; ?>
            <?php if (isset($_SESSION['addToListError'])): ?>
                <div><?= $_SESSION['addToListError'] ?></div>
                <?php unset($_SESSION['addToListError']) ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['editListStatusError'])): ?>
                <div><?= $_SESSION['editListStatusError'] ?></div>
                <?php unset($_SESSION['editListStatusError']) ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['manageGuestsError'])): ?>
                <div><?= $_SESSION['manageGuestsError'] ?></div>
                <?php unset($_SESSION['manageGuestsError']) ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['removeFromListError'])): ?>
                <div><?= $_SESSION['removeFromListError'] ?></div>
                <?php unset($_SESSION['removeFromListError']) ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['deleteListError'])): ?>
                <div><?= $_SESSION['deleteListError'] ?></div>
                <?php unset($_SESSION['deleteListError']) ?>
            <?php endif; ?>
            <?php if (!empty($lista)): ?>
                <div class="pokeList">
                    <?php if ($lista[0]['Tipo_Raid'] == "Oscura"): ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?> Oscuro" title="<?= $lista[0]['Nombre'] ?> Oscuro">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                            <img id="shadowIcon" src="../media/raids/Shadow.webp" height="60" alt="Oscuro" title="Oscuro">
                        </div>
                        <div class="pokemonName">
                            <span><?= $lista[0]['Nombre'] ?> Oscuro</span>
                        </div>
                    <?php elseif ($lista[0]['Tipo_Raid'] == "Dinamax"): ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?> Dinamax" title="<?= $lista[0]['Nombre'] ?> Dinamax">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                            <img id="maxIcon" src="../media/raids/Dynamax.webp" height="90" alt="Dinamax" title="Dinamax">
                        </div>
                        <div class="pokemonName">
                            <span><?= $lista[0]['Nombre'] ?> Dinamax</span>
                        </div>
                    <?php elseif ($lista[0]['Tipo_Raid'] == "Gigamax"): ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?>" title="<?= $lista[0]['Nombre'] ?>">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                            <img id="maxIcon" src="../media/raids/Gigantamax.webp" height="90" alt="Gigamax" title="Gigamax">
                        </div>
                        <div class="pokemonName">
                            <span><?= $lista[0]['Nombre'] ?></span>
                        </div>
                    <?php else: ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?>" title="<?= $lista[0]['Nombre'] ?>">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                        </div>
                        <div class="pokemonName">
                            <span><?= $lista[0]['Nombre'] ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="typeName">
                        <?php if (isset($lista[0]['Tipo2'])): ?>
                            <span>Tipo: <?= $lista[0]['Tipo1'] ?>/<?= $lista[0]['Tipo2'] ?></span>
                        <?php else: ?>
                            <span>Tipo: <?= $lista[0]['Tipo1'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="meetPlace">
                        <?php if (isset($lista[0]['Enlace_Maps'])): ?>
                            <span>Ubicación: <?= $lista[0]['Ubicacion'] ?> - <a href="<?= $lista[0]['Enlace_Maps'] ?>" target="_blank">¿Cómo llegar?</a></span>
                        <?php else: ?>
                            <span>Ubicación: <?= $lista[0]['Ubicacion'] ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($inicioLista) && isset($finLista)): ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?> - Hora apertura: <?= $inicioLista['hora'] ?> - Hora cierre: <?= $finLista['hora'] ?>)</span>
                        </div>
                    <?php elseif (!isset($inicioLista) && isset($finLista)): ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?> - Hora cierre: <?= $finLista['hora'] ?>)</span>
                        </div>
                    <?php elseif (isset($inicioLista) && !isset($finLista)): ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?> - Hora apertura: <?= $inicioLista['hora'] ?>)</span>
                        </div>
                    <?php else: ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?>)</span>
                        </div>
                    <?php endif; ?>
                    <div class="listHost">
                        <span>Host: <?= $lista[0]['Pogo_Username'] ?></span>
                    </div>
                    <?php if (isset($lista[0]['Tiempo_atmos'])): ?>
                        <div class="currentWeather">
                            <span>Tiempo: </span><img src="../media/raids/Weather_Icon_<?= $weatherImage ?>.webp" alt="<?= $lista[0]['Tiempo_atmos'] ?>" title="<?= $lista[0]['Tiempo_atmos'] ?> - <?= $descTiempo ?>" height="50">
                        </div>
                    <?php endif; ?>
                    <div class="perfectPC">
                        <?php if ($lista[0]['Tipo_Raid'] == "Dinamax" || $lista[0]['Tipo_Raid'] == "Gigamax"): ?>
                            <span>100% = <?= $lista[0]['PC_100_Nivel_20'] ?> PC</span>
                        <?php else: ?>
                            <span>100% = <?= $lista[0]['PC_100_Nivel_20'] ?> PC (<?= $lista[0]['PC_100_Nivel_25'] ?> PC si está potenciado)</span>
                        <?php endif; ?>
                    </div>
                    <div class="baseStats">
                        <span>Estadísticas base: Ataque = <?= $lista[0]['Ataque_base'] ?> / Defensa = <?= $lista[0]['Defensa_base'] ?> / PS = <?= $lista[0]['PS_base'] ?></span>
                    </div>
                    <?php if (isset($lista[0]['Enlace_counters'])): ?>
                        <div class="counterURL">
                            <span><a href=<?= $lista[0]['Enlace_counters'] ?> target="_blank">Counters</a></span>
                        </div>
                    <?php endif; ?>
                    <div class="listParticipants">
                        <span>Apuntados: <?= $lista[0]['numParticipantes'] ?>/<?= $lista[0]['Maximo_participantes'] ?> (<?= $lista[0]['numRemotos'] ?>/<?= $lista[0]['Maximo_remotos_totales'] ?> remotos)</span>
                    </div>
                    <div class="joinButtons">
                        <?php if ($usuarioApuntado && $lista[0]['numRemotos'] < $lista[0]['Maximo_remotos_totales']): ?>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?= $comprobacionApuntado[0]['Pase'] == "Presencial" ? "<input type='submit' value='Ya estás apuntado como presencial' disabled>" : "<input type='submit' value='Me apunto como presencial'>" ?>
                            </form>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Remoto">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?= $comprobacionApuntado[0]['Pase'] == "Remoto" ? "<input type='submit' value='Ya estás apuntado como remoto' disabled>" : "<input type='submit' value='Me apunto como remoto'>" ?>
                            </form>
                        <?php elseif ($usuarioApuntado && $lista[0]['numRemotos'] == $lista[0]['Maximo_remotos_totales']): ?>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?= $comprobacionApuntado[0]['Pase'] == "Presencial" ? "<input type='submit' value='Ya estás apuntado como presencial' disabled>" : "<input type='submit' value='Me apunto como presencial'>" ?>
                            </form>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Remoto">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?= $comprobacionApuntado[0]['Pase'] == "Remoto" ? "<input type='submit' value='Ya estás apuntado como remoto' disabled>" : "<input type='submit' value='Se ha alcanzado el número máximo de remotos' disabled>" ?>
                            </form>
                        <?php elseif (!$usuarioApuntado && ($lista[0]['numParticipantes'] < $lista[0]['Maximo_participantes'] && $lista[0]['numRemotos'] == $lista[0]['Maximo_remotos_totales'])): ?>
                            <form action="addToList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type='submit' value='Me apunto como presencial'>
                            </form>
                            <form>
                                <input type='submit' value='Se ha alcanzado el número máximo de remotos' disabled>
                            </form>
                        <?php elseif (!$usuarioApuntado && $lista[0]['numParticipantes'] == $lista[0]['Maximo_participantes']): ?>
                            <span>La lista está llena</span>
                        <?php else: ?>
                            <form action="addToList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type='submit' value='Me apunto como presencial'>
                            </form>
                            <form action="addToList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Remoto">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type='submit' value='Me apunto como remoto'>
                            </form>
                        <?php endif; ?>
                    </div>
                    <?php if ($usuarioApuntado): ?>
                        <div class="guestManagement">
                            <details>
                                <summary>Añadir o quitar invitados presenciales: </summary>
                                <form action="manageGuests.php" method="POST">
                                    <label>Invitados presenciales: </label>
                                    <input type="number" name="Invitado_presencial" value="<?= $comprobacionApuntado[0]['Invitado_presencial'] ?>" min=0 max=<?= ($lista[0]['Maximo_participantes'] - $lista[0]['numParticipantes']) + $comprobacionApuntado[0]['Invitado_presencial'] ?> required>
                                    <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                    <input type="hidden" name="Tipo_Invitado" value="Presencial">
                                    <input type="submit" value="Confirmar">
                                    <input type="reset" value="Volver al valor actual">
                                </form>
                            </details>
                            <details>
                                <summary>Añadir o quitar invitados remotos: </summary>
                                <form action="manageGuests.php" method="POST">
                                    <label>Invitados remotos: </label>
                                    <?php if ($lista[0]['Maximo_remotos_totales'] > $lista[0]['Maximo_remotos_por_apuntado']): ?>
                                        <?php if ((($lista[0]['Maximo_participantes'] - $lista[0]['numParticipantes']) + $comprobacionApuntado[0]['Invitado_remoto']) < $lista[0]['Maximo_remotos_por_apuntado']): ?>
                                            <input type="number" name="Invitado_remoto" value="<?= $comprobacionApuntado[0]['Invitado_remoto'] ?>" min=0 max=<?= ($lista[0]['Maximo_participantes'] - $lista[0]['numParticipantes']) + $comprobacionApuntado[0]['Invitado_remoto'] ?> required>
                                        <?php else: ?>
                                            <input type="number" name="Invitado_remoto" value="<?= $comprobacionApuntado[0]['Invitado_remoto'] ?>" min=0 max=<?= $lista[0]['Maximo_remotos_por_apuntado'] ?> required>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($lista[0]['Maximo_participantes'] - ($lista[0]['numParticipantes'] - $lista[0]['numRemotos']) < $lista[0]['Maximo_remotos_totales']): ?>
                                            <input type="number" name="Invitado_remoto" value="<?= $comprobacionApuntado[0]['Invitado_remoto'] ?>" min=0 max=<?= ($lista[0]['Maximo_participantes'] - $lista[0]['numParticipantes']) + $comprobacionApuntado[0]['Invitado_remoto'] ?> required>
                                        <?php else: ?>
                                            <input type="number" name="Invitado_remoto" value="<?= $comprobacionApuntado[0]['Invitado_remoto'] ?>" min=0 max=<?= ($lista[0]['Maximo_remotos_totales'] - $lista[0]['numRemotos']) + $comprobacionApuntado[0]['Invitado_remoto'] ?> required>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                    <input type="hidden" name="Tipo_Invitado" value="Remoto">
                                    <input type="submit" value="Confirmar">
                                    <input type="reset" value="Volver al valor actual">
                                </form>
                            </details>
                        </div>
                        <div class="statusList">
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type="hidden" name="Funcion" value="modificarEstado">
                                <?= $comprobacionApuntado[0]['Estado'] == "Voy" ? "<input type='submit' value='🚶 Voy' title='Tu estado ya es \"Voy\"' disabled>" : "<input type='submit' value='🚶 Voy' title='Cambia tu estado a \"Voy\"'>" ?>
                            </form>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Estado" value="Estoy">
                                <input type="hidden" name="Funcion" value="modificarEstado">
                                <?= $comprobacionApuntado[0]['Estado'] == "Estoy" ? "<input type='submit' value='✅ Estoy' title='Tu estado ya es \"Estoy\"' disabled>" : "<input type='submit' value='✅ Estoy' title='Cambia tu estado a \"Estoy\"'>" ?>
                            </form>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Estado" value="Llego tarde">
                                <input type="hidden" name="Funcion" value="modificarEstado">
                                <?= $comprobacionApuntado[0]['Estado'] == "Llego tarde" ? "<input type='submit' value='🐌 Llego tarde' title='Tu estado ya es \"Llego tarde\"' disabled>" : "<input type='submit' value='🐌 Llego tarde' title='Cambia tu estado a \"Llego tarde\"'>" ?>
                            </form>
                            <form action="removeFromList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type='submit' value='✖️ No voy' title='Cambia tu estado a "No voy" (no te cuenta como apuntado, aunque aún salgas en la lista)'>
                            </form>
                        </div>
                    <?php endif; ?>
                    <?php $ordenLista = 1 ?>
                    <div class="listJoined">
                        <?php foreach ($apuntados as $apuntado): ?>
                            <div class="meetTime">
                                <span class="apuntadosLista"><?= $ordenLista ?>) <?= $apuntado['Pase'] == "Remoto" ? "<img class='passIcon' src='../media/raids/Remote_Raid_Pass.webp' height=25 alt='Remoto' title='Remoto'> " : "" ?><?= iconoEstado($apuntado["Estado"]) ?> <?= $apuntado['Pogo_Username'] ?> - Nivel <?= $apuntado["Level"] ?> - <img class="teamLogo" src="../media/website/Logo_Equipo_<?= $apuntado['Team'] ?>_GO.png" height="25" alt="<?= $apuntado['Team'] ?>" title="<?= $apuntado['Team'] ?>"> - Apuntado a las <?= $apuntado['Hora_apuntado'] ?> - Último cambio a las <?= $apuntado['Hora_ultimo_cambio'] ?></span>
                                <?php if ($apuntado["Invitado_presencial"] > 0 && $apuntado['Invitado_remoto'] > 0): ?>
                                    <div><span class="invitadosLista">+ <?= $apuntado['Invitado_presencial'] ?> acompañantes presenciales + <?= $apuntado['Invitado_remoto'] ?> acompañantes remotos</span></div>
                                <?php elseif ($apuntado["Invitado_presencial"] > 0 && $apuntado['Invitado_remoto'] == 0): ?>
                                    <div><span class="invitadosLista">+ <?= $apuntado['Invitado_presencial'] ?> acompañantes presenciales</span></div>
                                <?php elseif ($apuntado["Invitado_presencial"] == 0 && $apuntado['Invitado_remoto'] > 0): ?>
                                    <div><span class="invitadosLista">+ <?= $apuntado['Invitado_remoto'] ?> acompañantes remotos</span></div>
                                <?php endif; ?>
                            </div>
                            <?php $ordenLista = $ordenLista + 1 + $apuntado["Invitado_presencial"] + $apuntado["Invitado_remoto"] ?>
                        <?php endforeach; ?>
                        <?php foreach ($desapuntados as $desapuntado): ?>
                            <div class="meetTime">
                                <span class="desapuntadoLista"><?= $ordenLista ?>) <?= $desapuntado['Pase'] == "Remoto" ? "<img class='passIcon' src='../media/raids/Remote_Raid_Pass.webp' height=25 alt='Remoto' title='Remoto'> " : "" ?><?= iconoEstado($desapuntado["Estado"]) ?> <?= $desapuntado['Pogo_Username'] ?> - Nivel <?= $desapuntado["Level"] ?> - <img class="teamLogo" src="../media/website/Logo_Equipo_<?= $desapuntado['Team'] ?>_GO.png" height="25" alt="<?= $desapuntado['Team'] ?>" title="<?= $desapuntado['Team'] ?>"> - Apuntado a las <?= $desapuntado['Hora_apuntado'] ?> - Último cambio a las <?= $desapuntado['Hora_ultimo_cambio'] ?></span>
                            </div>
                            <?php $ordenLista = $ordenLista + 1 ?>
                        <?php endforeach; ?>
                    </div>
                    <div>
                        <?php if ($_SESSION['usuario'] == $lista[0]['Username'] || $borrarLista): ?>
                            <form action="deleteList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type='submit' value='Borrar lista' title="Borra esta lista (Nota: No se puede deshacer)">
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <p>No se ha podido encontrar la lista con ID = <?= $_GET['id'] ?></p>
            <?php endif; ?>
        </article>
    </section>
    <footer>
        <div>Juan Antonio Gómez Martín - 2026</div>
        <div>©Niantic ©Pokémon/Nintendo/Creatures/GAME FREAK TM, ® y los nombres de los personajes son marcas comerciales de Nintendo.</div>
    </footer>
</body>
<script>
    <?php if (isset($_GET['id']) && $lista[0]['Shiny_activado']): ?>
        const shinyIcon = document.getElementById("shinyIcon");
        const pokemonSprite = document.getElementById("pokemonSprite");
        shinyIcon.addEventListener("click", shinyToggle);

        function shinyToggle() {
            if (shinyIcon.alt == "No variocolor") {
                pokemonSprite.src = "../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>-shiny.png";
                shinyIcon.src = "../media/raids/Shiny.png";
                shinyIcon.alt = "Variocolor";
                shinyIcon.title = "Pulsa para ocultar el variocolor"
                <?php if ($lista[0]['Tipo_Raid'] == "Oscura"): ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?> Oscuro variocolor";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?> Oscuro variocolor";
                <?php elseif ($lista[0]['Tipo_Raid'] == "Dinamax"): ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?> Dinamax variocolor";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?> Dinamax variocolor";
                <?php else: ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?> variocolor";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?> variocolor";
                <?php endif; ?>
            } else {
                pokemonSprite.src = "../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png";
                shinyIcon.src = "../media/raids/Shiny-Off.png";
                shinyIcon.alt = "No variocolor";
                shinyIcon.title = "Pulsa para mostrar el variocolor"
                <?php if ($lista[0]['Tipo_Raid'] == "Oscura"): ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?> Oscuro";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?> Oscuro";
                <?php elseif ($lista[0]['Tipo_Raid'] == "Dinamax"): ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?> Dinamax";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?> Dinamax";
                <?php else: ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?>";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?>";
                <?php endif; ?>
            }
        }
    <?php endif; ?>

    var closeAlert = document.getElementsByClassName("closeAlertBtn");
    var i;
    for (i = 0; i < closeAlert.length; i++) {
        closeAlert[i].onclick = function () {
            var childDiv = this.parentElement;
            div = childDiv.parentElement;
            div.style.opacity = "0";
            setTimeout(function () { div.style.display = "none"; }, 600);
        }
    }
</script>

</html>