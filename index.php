<?php
/* Importo los datos del fichero datos.php */
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
            <?php /* Si hay una sesión de usuario activa, muestro los botones de cerrar sesión, y un saludo (se cambiará a "Mi perfil") */ ?>
            <ul class="navList">
                <li class="navElement"><a href="./logout">Cerrar sesión</a></li>
                <li class="navElement"><span>¡Hola <?= $_SESSION['usuario'] ?>!</span></li>
            </ul>
        <?php else: ?>
            <?php /* Si no hay una sesión de usuario activa, muestro los botones de registro y de inicio de sesión */ ?>
            <ul class="navList">
                <li class="navElement"><a href="./registro">Regístrate</a></li>
                <li class="navElement"><a href="./login">Iniciar sesión</a></li>
            </ul>
        <?php endif; ?>
    </nav>
    <section>
        <?php /* Si hay una variable de sesión de una alerta de tipo éxito, la muestro y la desasigno. 
        Las alertas tienen un botón de cerrado (marcado con un símbolo de X codificado como "&times;")
        para que, al ser pulsados, desaparezcan. */ ?>
        <?php if (isset($_SESSION['successAlert'])): ?>
            <div class="alertBox" id="successAlert">
                <div class="alertSuccess">
                    <span class="closeAlertBtn">&times;</span>
                    <?= $_SESSION['successAlert'] ?>
                </div>
            </div>
            <?php unset($_SESSION['successAlert']) ?>
        <?php endif; ?>
        <?php /* Si hay una variable de sesión de una alerta de tipo error, la muestro y la desasigno. */ ?>
        <?php if (isset($_SESSION['errorAlert'])): ?>
            <div class="alertBox" id="errorAlert">
                <div class="alertError">
                    <span class="closeAlertBtn">&times;</span>
                    <?= $_SESSION['errorAlert'] ?>
                </div>
            </div>
            <?php unset($_SESSION['errorAlert']) ?>
        <?php endif; ?>
        <?php /* Introduzco un enlace como botón para acceder a la página de crear listas */ ?>
        <a class="buttonLink" href="crearlista"><button class="createList">Crear lista</button></a>
        <article>
            <?php /* Compruebo si hay listas activas */ ?>
            <?php if (!empty($listas)): ?>
                <?php /* Si hay listas activas, abro un bucle for each para insertar cada lista activa */ ?>
                <?php foreach ($listas as $lista): ?>
                    <div class="pokeList">
                        <?php /* Inserto un enlace a la página de esa lista, que tiene que usar el protocolo GET para acceder a ella */ ?>
                        <a href="lista?id=<?= $lista['ID_Lista'] ?>" class="listLink">
                            <?php if ($lista['Tipo_Raid'] == "Oscura"): ?>
                                <?php /* Si el tipo de incursión es oscura, muestro el icono de un Pokémon oscuro, y añado "Oscuro" a su nombre */ ?>
                                <div class="pokemonIcon">
                                    <img class="pokemonSprite" src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista['Nombre'] ?> Oscuro" title="<?= $lista['Nombre'] ?> Oscuro">
                                    <?php if ($lista['Shiny_activado']): ?>
                                        <?php /* Si tiene su variocolor activado, muestro el icono de variocolor */ ?>
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="60" alt="Variocolor activado" title="Variocolor activado">
                                    <?php endif; ?>
                                    <img class="shadowIcon" src="../media/raids/Shadow.webp" height="60" alt="Oscuro" title="Oscuro">
                                </div>
                                <div class="pokemonName">
                                    <span><?= $lista['Nombre'] ?> Oscuro</span>
                                </div>
                            <?php elseif ($lista['Tipo_Raid'] == "Dinamax"): ?>
                                <?php /* Si el tipo de incursión es dinamax, muestro el icono de un Pokémon dinamax, y añado "Dinamax" a su nombre */ ?>
                                <div class="pokemonIcon">
                                    <img class="pokemonSprite" src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista['Nombre'] ?> Dinamax" title="<?= $lista['Nombre'] ?> Dinamax">
                                    <?php if ($lista['Shiny_activado']): ?>
                                        <?php /* Si tiene su variocolor activado, muestro el icono de variocolor */ ?>
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="60" alt="Variocolor activado" title="Variocolor activado">
                                    <?php endif; ?>
                                    <img class="maxIcon" src="../media/raids/Dynamax.webp" height="90" alt="Dinamax" title="Dinamax">
                                </div>
                                <div class="pokemonName">
                                    <span><?= $lista['Nombre'] ?> Dinamax</span>
                                </div>
                            <?php elseif ($lista['Tipo_Raid'] == "Gigamax"): ?>
                                <?php /* Si el tipo de incursión es gigamax, muestro el icono de un Pokémon gigamax, 
                                pero no hace falta añadir "Gigamax" a su nombre, ya que viene así en la base de datos */ ?>
                                <div class="pokemonIcon">
                                    <img class="pokemonSprite" src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista['Nombre'] ?>" title="<?= $lista['Nombre'] ?>">
                                    <?php if ($lista['Shiny_activado']): ?>
                                        <?php /* Si tiene su variocolor activado, muestro el icono de variocolor */ ?>
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="60" alt="Variocolor activado" title="Variocolor activado">
                                    <?php endif; ?>
                                    <img class="maxIcon" src="../media/raids/Gigantamax.webp" height="90" alt="Gigamax" title="Gigamax">
                                </div>
                                <div class="pokemonName">
                                    <span><?= $lista['Nombre'] ?></span>
                                </div>
                            <?php else: ?>
                                <?php /* Si no es ninguno de los tipos anteriores, no añado ningún icono */ ?>
                                <div class="pokemonIcon">
                                    <img class="pokemonSprite" src="media/pokemon/<?= $lista['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista['Nombre'] ?>" title="<?= $lista['Nombre'] ?>">
                                    <?php if ($lista['Shiny_activado']): ?>
                                        <?php /* Si tiene su variocolor activado, muestro el icono de variocolor */ ?>
                                        <img class="shinyIcon" src="../media/raids/Shiny.png" height="60" alt="Variocolor activado" title="Variocolor activado">
                                    <?php endif; ?>
                                </div>
                                <div class="pokemonName">
                                    <span><?= $lista['Nombre'] ?></span>
                                </div>
                            <?php endif; ?>
                            <?php /* Pongo el nombre de la ubicación de la quedada */ ?>
                            <div class="placeName">
                                <span><?= $lista['Ubicacion'] ?></span>
                            </div>
                            <?php if ($lista['fecha'] == $hoy): ?>
                                <?php /* Si la fecha de la quedada es la misma que hoy, solo muestro la hora */ ?>
                                <div class="meetTime">
                                    <span><?= $lista['hora'] ?></span>
                                </div>
                            <?php else: ?>
                                <?php /* Si no, muestro también la fecha */ ?>
                                <div class="meetTime">
                                    <span><?= $lista['fecha'] ?> - <?= $lista['hora'] ?></span>
                                </div>
                            <?php endif; ?>
                            <?php /* Muestro la lista de participantes que hay, junto al límite de la sala (incluyendo el número de remotos y su límite) */ ?>
                            <div class="lobbyStatus">
                                <span><?= $lista['Participantes'] ?>/<?= $lista['Maximo_participantes'] ?> (<?= $lista['Remotos'] ?>/<?= $lista['Maximo_remotos_totales'] ?> remotos)</span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php /* Si no hay listas activas, se indica, y se invita al usuario a crear una lista */ ?>
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