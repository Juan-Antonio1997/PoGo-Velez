<?php
require_once('./config.php');
require_once('./db_pdo.php');
$db = db_open();
session_start();
if ($db) {
    $listas = db_query($db, "SELECT l.ID_Lista, l.Ubicacion, l.Hora_quedada, i.ID_Pokemon, i.Tipo_Raid, i.Maximo_participantes, i.Maximo_remotos, p.Nombre 
    FROM listas AS l
    INNER JOIN incursiones AS i ON l.ID_Raid = i.ID_Raid
    INNER JOIN pokemon AS p ON i.ID_Pokemon = p.ID_Pokemon
    WHERE DATE_ADD(l.Hora_quedada, INTERVAL 1 MINUTE) > NOW()
    ORDER BY l.Hora_quedada;");
    $hoy = date("d/m/Y");
    for ($i = 0; $i < count($listas); $i++) {
        $participantes = db_query($db, "SELECT COUNT(Username) AS Num_Apuntados
        FROM apuntados_lista WHERE ID_Lista = (?)", [$listas[$i]['ID_Lista']]);
        $listas[$i]['Participantes'] = $participantes[0]['Num_Apuntados'];
        $remotos = db_query($db, "SELECT COUNT(Username) AS Num_Remotos
        FROM apuntados_lista WHERE ID_Lista = (?) AND Pase = (?)", [$listas[$i]['ID_Lista'], 'Remoto']);
        $listas[$i]['Remotos'] = $remotos[0]['Num_Remotos'];
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
        <button class="createList">Crear lista</button>
        <article>
            <?php if (!empty($listas)): ?>
                <?php foreach ($listas as $lista): ?>
                    <a href="lista?id=<?= $lista['ID_Lista'] ?>" class="listLink">
                        <div class="pokeList">
                            <div class="pokemonSprite">
                                <img src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" width="150">
                            </div>
                            <div class="pokemonName">
                                <span><?= $lista['Nombre'] ?></span>
                            </div>
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
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </article>
    </section>
    <footer>

    </footer>
</body>

</html>