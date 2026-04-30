<?php
require_once('../config.php');
require_once('../db_pdo.php');
$db = db_open();
session_start();
if ($db) {
    if (isset($_GET['id'])) {
        $lista = db_query($db, "SELECT p.*, u.Username, u.Pogo_Username, l.Ubicacion, l.Enlace_Maps, 
        l.Hora_creacion, l.Hora_quedada, l.Hora_inicio, l.Hora_fin, l.Tiempo_atmos, i.Tipo_Raid,
        i.Enlace_counters, i.Maximo_participantes, i.Maximo_remotos, i.Shiny_activado
        FROM listas AS l
        INNER JOIN incursiones as i on l.ID_Raid = i.ID_RAID
        INNER JOIN pokemon AS p ON i.ID_Pokemon = p.ID_Pokemon
        INNER JOIN usuarios AS u ON l.Creado_por = u.Username
        WHERE ID_Lista = (?)", [$_GET['id']]);
        $hoy = date("d/m/Y");
        #Obtengo el número de participantes por lista
        $numParticipantes = db_query($db, "SELECT COUNT(Username) AS Num_Apuntados
        FROM apuntados_lista WHERE ID_Lista = (?)", [$_GET['id']]);
        $lista[0]['numParticipantes'] = $numParticipantes[0]['Num_Apuntados'];
        #Obtengo los invitados y los sumo
        $invitadoPresencial = db_query($db, "SELECT SUM(Invitado_presencial) AS Total_invitados_presenciales
        FROM apuntados_lista WHERE ID_Lista = (?)", [$_GET['id']]);
        $invitadoRemoto = db_query($db, "SELECT SUM(Invitado_remoto) AS Total_invitados_remotos
        FROM apuntados_lista WHERE ID_Lista = (?)", [$_GET['id']]);
        $lista[0]['numParticipantes'] = $lista[0]['numParticipantes'] + $invitadoPresencial[0]['Total_invitados_presenciales'] + $invitadoRemoto[0]['Total_invitados_remotos'];
        #Obtengo el número de apuntados remotos
        $numRemotos = db_query($db, "SELECT COUNT(Username) AS Num_Remotos
        FROM apuntados_lista WHERE ID_Lista = (?) AND Pase = (?)", [$_GET['id'], 'Remoto']);
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
        }
        if (isset($lista[0]['Hora_fin'])) {
            $fechasLista['fechaFin'] = strtotime($lista[0]['Hora_fin']);
            $finLista['fecha'] = date("d/m/Y", $fechasLista['fechaFin']);
            $finLista['hora'] = date("H:i", $fechasLista['fechaFin']);
        }
    }
    #print_r($lista[0]);
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
            <div class="pokeList">
                <div class="pokemonSprite">
                    <img src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" width="150">
                </div>
                <?php if ($lista[0]['Tipo_Raid'] == "Oscura"): ?>
                    <div class="pokemonName">
                        <span><?= $lista[0]['Nombre'] ?> Oscuro</span>
                    </div>
                <?php elseif ($lista[0]['Tipo_Raid'] == "Dinamax"): ?>
                    <div class="pokemonName">
                        <span><?= $lista[0]['Nombre'] ?> Dinamax</span>
                    </div>
                <?php else: ?>
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
                <!-- Por cambiar lo de la hora -->
                <div class="meetTime">
                    <span>12:15 (cierra a las 12:30)</span>
                </div>
                <!-- Por cambiar lo del host -->
                <div class="listHost">
                    <span>Host: x08Juan80x</span>
                </div>
                <!-- Por cambiar lo del tiempo -->
                <div class="currentWeather">
                    <span>Tiempo: <img src="../media/raids/Weather_Icon_Clear_Day.webp" alt="Soleado" title="Soleado - Potencia a los tipo Planta, Fuego y Tierra" height="50"></span>
                </div>
                <div class="perfectPC">
                    <span>100% = <?= $lista[0]['PC_100_Nivel_20'] ?> PC (<?= $lista[0]['PC_100_Nivel_25'] ?> PC si está potenciado)</span>
                </div>
                <div class="baseStats">
                    <span>Estadísticas base: Ataque = <?= $lista[0]['Ataque_base'] ?> / Defensa = <?= $lista[0]['Defensa_base'] ?> / PS = <?= $lista[0]['PS_base'] ?></span>
                </div>
                <?php if (isset($lista[0]['Enlace_counters'])): ?>
                <div class="counterURL">
                    <span><a href=<?= $lista[0]['Enlace_counters'] ?> target="_blank">Counters</a></span>
                </div>
                <?php endif; ?>
                <!-- Por cambiar lo de la lista de participantes -->
                <div class="listParticipants">
                    <span>1/20 (0 remotos)</span>
                </div>
                <!-- Por dar funcionalidad a los botones de apuntarse -->
                <div class="meetTime">
                    <span><img src="../media/raids/Regular_And_Premium_Pass.webp" height="50"> <img src="../media/raids/Remote_Raid_Pass.webp" height="50"></span>
                </div>
                <!-- Por dar funcionalidad a los botones de estado -->
                <div class="meetTime">
                    <span>🚶 Voy - ✅ Estoy - 🐌 Llego Tarde - ❌ No voy</span>
                </div>
                <!-- Por mostrar la lista de apuntados -->
                <div class="meetTime">
                    <span>1) <img src="../media/raids/Regular_And_Premium_Pass.webp" height="25"> ✅ x08Juan80x - Nivel 77 - <img src="../media/website/Logo_Equipo_Valor_GO.png" height="25"></span>
                </div>
            </div>
        </article>
    </section>
    <footer>

    </footer>
</body>

</html>