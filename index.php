<?php
include 'datos.php';
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
        <?php if (isset($_SESSION['successAlert'])): ?>
            <div class="alertBox" id="successAlert">
                <div class="alertSuccess">
                    <span class="closeAlertBtn">&times;</span>
                    <?= $_SESSION['successAlert'] ?>
                </div>
            </div>
            <?php unset($_SESSION['successAlert']) ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['errorAlert'])): ?>
            <div class="alertBox" id="errorAlert">
                <div class="alertError">
                    <span class="closeAlertBtn">&times;</span>
                    <?= $_SESSION['errorAlert'] ?>
                </div>
            </div>
            <?php unset($_SESSION['errorAlert']) ?>
        <?php endif; ?>
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
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="60" alt="Variocolor activado" title="Variocolor activado">
                                    <?php endif; ?>
                                    <img class="shadowIcon" src="../media/raids/Shadow.webp" height="60" alt="Oscuro" title="Oscuro">
                                </div>
                                <div class="pokemonName">
                                    <span><?= $lista['Nombre'] ?> Oscuro</span>
                                </div>
                            <?php elseif ($lista['Tipo_Raid'] == "Dinamax"): ?>
                                <div class="pokemonIcon">
                                    <img class="pokemonSprite" src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista['Nombre'] ?> Dinamax" title="<?= $lista['Nombre'] ?> Dinamax">
                                    <?php if ($lista['Shiny_activado']): ?>
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="60" alt="Variocolor activado" title="Variocolor activado">
                                    <?php endif; ?>
                                    <img class="maxIcon" src="../media/raids/Dynamax.webp" height="90" alt="Dinamax" title="Dinamax">
                                </div>
                                <div class="pokemonName">
                                    <span><?= $lista['Nombre'] ?> Dinamax</span>
                                </div>
                            <?php elseif ($lista['Tipo_Raid'] == "Gigamax"): ?>
                                <div class="pokemonIcon">
                                    <img class="pokemonSprite" src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista['Nombre'] ?>" title="<?= $lista['Nombre'] ?>">
                                    <?php if ($lista['Shiny_activado']): ?>
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="60" alt="Variocolor activado" title="Variocolor activado">
                                    <?php endif; ?>
                                    <img class="maxIcon" src="../media/raids/Gigantamax.webp" height="90" alt="Gigamax" title="Gigamax">
                                </div>
                                <div class="pokemonName">
                                    <span><?= $lista['Nombre'] ?></span>
                                </div>
                            <?php else: ?>
                                <div class="pokemonIcon">
                                    <img class="pokemonSprite" src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista['Nombre'] ?>" title="<?= $lista['Nombre'] ?>">
                                    <?php if ($lista['Shiny_activado']): ?>
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="60" alt="Variocolor activado" title="Variocolor activado">
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
                                <span><?= $lista['Participantes'] ?>/<?= $lista['Maximo_participantes'] ?> (<?= $lista['Remotos'] ?>/<?= $lista['Maximo_remotos_totales'] ?> remotos)</span>
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
        <div>Juan Antonio Gómez Martín - 2026</div>
        <div>©Niantic ©Pokémon/Nintendo/Creatures/GAME FREAK TM, ® y los nombres de los personajes son marcas comerciales de Nintendo.</div>
    </footer>
    <script src="script.js"></script>
</body>

</html>