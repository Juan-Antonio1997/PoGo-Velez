<?php
/* Obtengo el PDO y la configuración para poder manipular bases de datos */
require_once('../config.php');
require_once('../db_pdo.php');
/* Abro la conexión a la base de datos indicada en el fichero de configuración */
$db = db_open();
/* Inicio la sesión (activo las variables $_SESSION) */
session_start();
if (!isset($_SESSION['usuario'])) {
    $_SESSION['warningAlert'] = "¡Tienes que iniciar sesión antes de poder crear una lista!";
    header('Location: ../login');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
}
/* Condición: Si hay conexión a la base de datos */
if ($db) {
    $raidBosses = db_query($db, "SELECT i.*, p.* 
    FROM incursiones AS i 
    INNER JOIN pokemon AS p ON p.ID_Pokemon = i.ID_Pokemon
    WHERE Activo = (?)", [1]);
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
        <a href="../" class="titleLink">
            <h1 class="pageTitle">PoGo Vélez-Málaga</h1>
        </a>
    </header>
    <nav>
        <?php if (isset($_SESSION['usuario'])): ?>
            <?php /* Si hay una sesión de usuario activa, muestro los botones de cerrar sesión, y un saludo (se cambiará a "Mi perfil") */ ?>
            <ul class="navList">
                <li class="navElement"><a href="../logout">Cerrar sesión</a></li>
                <li class="navElement"><span>¡Hola <?= $_SESSION['usuario'] ?>!</span></li>
            </ul>
        <?php else: ?>
            <?php /* Si no hay una sesión de usuario activa, muestro los botones de registro y de inicio de sesión */ ?>
            <ul class="navList">
                <li class="navElement"><a href="../registro">Regístrate</a></li>
                <li class="navElement"><a href="../login">Iniciar sesión</a></li>
            </ul>
        <?php endif; ?>
    </nav>
    <section>
        <article>
            <?php /* Si hay una variable de sesión de una alerta de tipo error, la muestro y la desasigno. 
            Las alertas tienen un botón de cerrado (marcado con un símbolo de X codificado como "&times;")
            para que, al ser pulsados, desaparezcan. */ ?>
            <?php if (isset($_SESSION['errorAlert'])): ?>
                <div class="alertBox" id="errorAlert">
                    <div class="alertError">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['errorAlert'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['errorAlert']) ?>
            <?php endif; ?>
            <form action="crearLista.php" method="POST">
                <div>Jefe de incursión: <span id="bossName"></span></div>
                <div id="bossSelection">
                    <?php foreach ($raidBosses as $raidBoss): ?>
                        <?php if ($raidBoss['Tipo_Raid'] == "Oscura"): ?>
                            <?php /* Si el tipo de incursión es oscura, muestro el icono de un Pokémon oscuro, 
                            y añado "Oscuro" a su nombre en el atributo "title" */ ?>
                            <label class="listOption">
                                <input type="radio" class="radioImg" name="ID_Raid" value="<?= $raidBoss['ID_Raid'] ?>" title="<?= $raidBoss['Nombre'] ?> Oscuro" required>
                                <img src="../media/pokemon/<?= $raidBoss['ID_Pokemon'] ?>.png" alt="<?= $raidBoss['Nombre'] ?> Oscuro" title="<?= $raidBoss['Nombre'] ?> Oscuro" height="100">
                                <?php if ($raidBoss['Shiny_activado']): ?>
                                    <?php /* Si tiene su variocolor activado, muestro el icono de variocolor */ ?>
                                    <img class="shinyIcon" src="../media/raids/Shiny.png" height="40" alt="Variocolor activado" title="Variocolor activado">
                                <?php endif; ?>
                                <img class="shadowIcon" src="../media/raids/Shadow.webp" height="40" alt="Oscuro" title="Oscuro">
                            </label>
                        <?php elseif ($raidBoss['Tipo_Raid'] == "Dinamax"): ?>
                            <?php /* Si el tipo de incursión es dinamax, muestro el icono de un Pokémon dinamax, 
                            y añado "Dinamax" a su nombre en el atributo "title" */ ?>
                            <label class="listOption">
                                <input type="radio" class="radioImg" name="ID_Raid" value="<?= $raidBoss['ID_Raid'] ?>" title="<?= $raidBoss['Nombre'] ?> Dinamax" required>
                                <img src="../media/pokemon/<?= $raidBoss['ID_Pokemon'] ?>.png" alt="<?= $raidBoss['Nombre'] ?> Dinamax" title="<?= $raidBoss['Nombre'] ?> Dinamax" height="100">
                                <?php if ($raidBoss['Shiny_activado']): ?>
                                    <?php /* Si tiene su variocolor activado, muestro el icono de variocolor */ ?>
                                    <img class="shinyIcon" src="../media/raids/Shiny.png" height="40" alt="Variocolor activado" title="Variocolor activado">
                                <?php endif; ?>
                                <img class="maxIcon" src="../media/raids/Dynamax.webp" height="60" alt="Dinamax" title="Dinamax">
                            </label>
                        <?php elseif ($raidBoss['Tipo_Raid'] == "Gigamax"): ?>
                            <?php /* Si el tipo de incursión es gigamax, muestro el icono de un Pokémon gigamax, 
                                pero no hace falta añadir "Gigamax" a su nombre, ya que viene así en la base de datos */ ?>
                            <label class="listOption">
                                <input type="radio" class="radioImg" name="ID_Raid" value="<?= $raidBoss['ID_Raid'] ?>" title="<?= $raidBoss['Nombre'] ?>" required>
                                <img src="../media/pokemon/<?= $raidBoss['ID_Pokemon'] ?>.png" alt="<?= $raidBoss['Nombre'] ?>" title="<?= $raidBoss['Nombre'] ?>" height="100">
                                <?php if ($raidBoss['Shiny_activado']): ?>
                                    <?php /* Si tiene su variocolor activado, muestro el icono de variocolor */ ?>
                                    <img class="shinyIcon" src="../media/raids/Shiny.png" height="40" alt="Variocolor activado" title="Variocolor activado">
                                <?php endif; ?>
                                <img class="maxIcon" src="../media/raids/Gigantamax.webp" height="60" alt="Gigamax" title="Gigamax">
                            </label>
                        <?php else: ?>
                            <?php /* Si no es ninguno de los tipos anteriores, no añado ningún icono */ ?>
                            <label class="listOption">
                                <input type="radio" class="radioImg" name="ID_Raid" value="<?= $raidBoss['ID_Raid'] ?>" title="<?= $raidBoss['Nombre'] ?>" required>
                                <img src="../media/pokemon/<?= $raidBoss['ID_Pokemon'] ?>.png" alt="<?= $raidBoss['Nombre'] ?>" title="<?= $raidBoss['Nombre'] ?>" height="100">
                                <?php if ($raidBoss['Shiny_activado']): ?>
                                    <?php /* Si tiene su variocolor activado, muestro el icono de variocolor */ ?>
                                    <img class="shinyIcon" src="../media/raids/Shiny.png" height="40" alt="Variocolor activado" title="Variocolor activado">
                                <?php endif; ?>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div>
                    <label>Ubicación: </label>
                    <input type="text" name="Ubicacion" placeholder="Nombre del gimnasio o nodo" maxlength="30" required>
                </div>
                <div>
                    <label>Enlace Ubicación (Opcional): </label>
                    <input type="text" name="Enlace Maps" placeholder="Enlace de Google Maps" maxlength="50">
                </div>
                <div>
                    <label>Hora de quedada: </label>
                    <input type="time" name="Hora_quedada" required>
                </div>
                <div>
                    <label>Hora de inicio (Opcional): </label>
                    <input type="time" name="Hora_inicio">
                </div>
                <div>
                    <label>Hora de fin (Opcional): </label>
                    <input type="time" name="Hora_fin">
                </div>
                <div>
                    <label>Tiempo atmosférico (Opcional): <span id="weatherName"></span></label>
                </div>
                <div id="weatherSelection">
                    <label class="weatherList">
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Soleado">
                        <img src="../media/raids/Weather_Icon_Clear_Day.webp" title="Soleado" height="50">
                    </label>
                    <label class="weatherList">
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Despejado">
                        <img src="../media/raids/Weather_Icon_Clear_Night.webp" title="Despejado" height="50">
                    </label>
                    <label class="weatherList">
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Parcialmente nublado (día)">
                        <img src="../media/raids/Weather_Icon_Partly_Cloudy_Day.webp" title="Parcialmente nublado (día)" height="50">
                    </label>
                    <label class="weatherList">
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Parcialmente nublado (noche)">
                        <img src="../media/raids/Weather_Icon_Partly_Cloudy_Night.webp" title="Parcialmente nublado (noche)" height="50">
                    </label>
                    <label class="weatherList">
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Nublado">
                        <img src="../media/raids/Weather_Icon_Cloudy.webp" title="Nublado" height="50">
                    </label>
                    <label class="weatherList">
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Lluvia">
                        <img src="../media/raids/Weather_Icon_Rain.webp" title="Lluvia" height="50">
                    </label>
                    <label class="weatherList">
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Viento">
                        <img src="../media/raids/Weather_Icon_Windy.webp" title="Viento" height="50">
                    </label>
                    <label class="weatherList">
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Niebla">
                        <img src="../media/raids/Weather_Icon_Foggy.webp" title="Niebla" height="50">
                    </label>
                    <label class="weatherList">
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Nieve">
                        <img src="../media/raids/Weather_Icon_Snow.webp" title="Nieve" height="50">
                    </label>
                    <label class="weatherList">
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Extremo">
                        <img src="../media/raids/Weather_Icon_Extreme.webp" title="Extremo" height="50">
                    </label>
                </div>
                <div>
                    <label>¿Vas a estar en persona o vas a usar un pase remoto? </label>
                </div>
                <div>
                    <div>
                        <input type="radio" name="Pase" value="Presencial" required>
                        <label>En persona</label>
                    </div>
                    <div>
                        <input type="radio" name="Pase" value="Remoto" required>
                        <label>Voy a usar un pase remoto</label>
                    </div>
                </div>
                <div>
                    <input type="submit" value="Crear lista">
                </div>
            </form>
        </article>
    </section>
    <footer>
        <div>Juan Antonio Gómez Martín - 2026</div>
        <div>©Niantic ©Pokémon/Nintendo/Creatures/GAME FREAK TM, ® y los nombres de los personajes son marcas comerciales de Nintendo.</div>
    </footer>
    <script src="script.js"></script>
</body>

</html>