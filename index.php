<?php
require_once('./config.php');
require_once('./db_pdo.php');
$db = db_open();
session_start();
if ($db) {
    $listas = db_query($db, "SELECT l.ID_Lista, l.Ubicacion, l.Hora_quedada, i.ID_Pokemon,
    i.Tipo_Raid, i.Maximo_participantes, i.Maximo_remotos, p.Nombre, i.Shiny_activado
    FROM listas AS l
    INNER JOIN incursiones AS i ON l.ID_Raid = i.ID_Raid
    INNER JOIN pokemon AS p ON i.ID_Pokemon = p.ID_Pokemon
    WHERE DATE_ADD(l.Hora_quedada, INTERVAL 1 MINUTE) > NOW()
    ORDER BY l.Hora_quedada;");
    $hoy = date("d/m/Y");
    for ($i = 0; $i < count($listas); $i++) {
        #Obtengo el número de participantes por lista
        $participantes = db_query($db, "SELECT COUNT(Username) AS Num_Apuntados
        FROM apuntados_lista WHERE ID_Lista = (?)", [$listas[$i]['ID_Lista']]);
        $listas[$i]['Participantes'] = $participantes[0]['Num_Apuntados'];
        #Obtengo los invitados y los sumo
        $invitadoPresencial = db_query($db, "SELECT SUM(Invitado_presencial) AS Total_invitados_presenciales
        FROM apuntados_lista WHERE ID_Lista = (?)", [$listas[$i]['ID_Lista']]);
        $invitadoRemoto = db_query($db, "SELECT SUM(Invitado_remoto) AS Total_invitados_remotos
        FROM apuntados_lista WHERE ID_Lista = (?)", [$listas[$i]['ID_Lista']]);
        $listas[$i]['Participantes'] = $listas[$i]['Participantes'] + $invitadoPresencial[0]['Total_invitados_presenciales'] + $invitadoRemoto[0]['Total_invitados_remotos'];
        #Obtengo el número de apuntados remotos
        $remotos = db_query($db, "SELECT COUNT(Username) AS Num_Remotos
        FROM apuntados_lista WHERE ID_Lista = (?) AND Pase = (?)", [$listas[$i]['ID_Lista'], 'Remoto']);
        $listas[$i]['Remotos'] = $remotos[0]['Num_Remotos'];
        #Sumo los invitados remotos
        $listas[$i]['Remotos'] = $listas[$i]['Remotos'] + $invitadoRemoto[0]['Total_invitados_remotos'];
        $fecha = strtotime($listas[$i]['Hora_quedada']);
        $listas[$i]['fecha'] = date("d/m/Y", $fecha);
        $listas[$i]['hora'] = date("H:i", $fecha);
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PoGO Vélez-Málaga</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header>
        <a href="./" class="titleLink">
            <h1 class="pageTitle">PoGo Vélez-Málaga</h1>
        </a>
    </header>
    <nav>
        <?php if (isset($_SESSION['usuario'])): ?>
            <ul class="navList">
                <li class="navElement"><a href="./logout">Cerrar sesión</a></li>
                <li class="navElement"><span>¡Hola <?= $_SESSION['usuario'] ?>!</span></li>
            </ul>
        <?php else: ?>
            <ul class="navList">
                <li class="navElement"><a href="./registro">Regístrate</a></li>
                <li class="navElement"><a href="./login">Iniciar sesión</a></li>
            </ul>
        <?php endif; ?>
    </nav>
    <section>
        <a class="buttonLink" href="crearlista"><button class="createList">Crear lista</button></a>
        <article>
            <?php if (!empty($listas)): ?>
                <?php foreach ($listas as $lista): ?>
                    <div class="pokeList">
                        <a href="lista?id=<?= $lista['ID_Lista'] ?>" class="listLink">
                            <?php if ($lista['Tipo_Raid'] == "Oscura"): ?>
                                <div class="pokemonIcon">
                                    <img class="pokemonSprite" src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista['Nombre'] ?> Oscuro" title="<?= $lista['Nombre'] ?> Oscuro">
                                    <?php if ($lista['Shiny_activado']): ?>
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="30" alt="Variocolor activado" title="Variocolor activado">
                                    <?php endif; ?>
                                    <img class="raidTypeIcon" src="../media/raids/Shadow.webp" height="40" alt="Oscuro" title="Oscuro">
                                </div>
                                <div class="pokemonName">
                                    <span><?= $lista['Nombre'] ?> Oscuro</span>
                                </div>
                            <?php elseif ($lista['Tipo_Raid'] == "Dinamax"): ?>
                                <div class="pokemonIcon">
                                    <img class="pokemonSprite" src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista['Nombre'] ?> Dinamax" title="<?= $lista['Nombre'] ?> Dinamax">
                                    <?php if ($lista['Shiny_activado']): ?>
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="30" alt="Variocolor activado" title="Variocolor activado">
                                    <?php endif; ?>
                                    <img class="raidTypeIcon" src="../media/raids/Dynamax.webp" height="40" alt="Dinamax" title="Dinamax">
                                </div>
                                <div class="pokemonName">
                                    <span><?= $lista['Nombre'] ?> Dinamax</span>
                                </div>
                            <?php elseif ($lista['Tipo_Raid'] == "Gigamax"): ?>
                                <div class="pokemonIcon">
                                    <img class="pokemonSprite" src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista['Nombre'] ?>" title="<?= $lista['Nombre'] ?>">
                                    <?php if ($lista['Shiny_activado']): ?>
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="30" alt="Variocolor activado" title="Variocolor activado">
                                    <?php endif; ?>
                                    <img class="raidTypeIcon" src="../media/raids/Gigantamax.webp" height="40" alt="Gigamax" title="Gigamax">
                                </div>
                                <div class="pokemonName">
                                    <span><?= $lista['Nombre'] ?></span>
                                </div>
                            <?php else: ?>
                                <div class="pokemonIcon">
                                    <img class="pokemonSprite" src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista['Nombre'] ?>" title="<?= $lista['Nombre'] ?>">
                                    <?php if ($lista['Shiny_activado']): ?>
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="30" alt="Variocolor activado" title="Variocolor activado">
                                    <?php endif; ?>
                                </div>
                                <div class="pokemonName">
                                    <span><?= $lista['Nombre'] ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="placeName">
                                <span><?= $lista['Ubicacion'] ?></span>
                            </div>
                            <?php if ($lista['fecha'] == $hoy): ?>
                                <div class="meetTime">
                                    <span><?= $lista['hora'] ?></span>
                                </div>
                            <?php else: ?>
                                <div class="meetTime">
                                    <span><?= $lista['fecha'] ?> - <?= $lista['hora'] ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="lobbyStatus">
                                <span><?= $lista['Participantes'] ?>/<?= $lista['Maximo_participantes'] ?> (<?= $lista['Remotos'] ?>/<?= $lista['Maximo_remotos'] ?> remotos)</span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="noLists">No hay listas activas. Pulsa en el botón de arriba para crear una.</p>
            <?php endif; ?>
        </article>
    </section>
    <footer>

    </footer>
</body>

</html>