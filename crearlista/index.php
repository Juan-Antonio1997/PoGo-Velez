<?php
require_once('../config.php');
require_once('../db_pdo.php');
$db = db_open();
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login');
}
if ($db) {
    $raidBosses = db_query($db, "SELECT * FROM incursiones WHERE Activo = (?)", [1]);
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
        <h1 class="pageTitle">PoGo Vélez-Málaga</h1>
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
            <form action="crearLista.php" method="POST">
                <div>Jefe de incursión: </div>
                <div>
                    <?php foreach ($raidBosses as $raidBoss): ?>
                        <label>
                            <input type="radio" class="radioImg" name="ID_Raid" value="<?= $raidBoss['ID_Raid'] ?>" required>
                            <img src="../media/pokemon/<?= $raidBoss['ID_Pokemon'] ?>.png" title="<?= $raidBoss['Tipo_Raid'] ?>" height="100">
                        </label>
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
                    <label>Tiempo atmosférico (Opcional): </label>
                </div>
                <div>
                    <label>
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Soleado">
                        <img src="../media/raids/Weather_Icon_Clear_Day.webp" title="Soleado" height="50">
                    </label>
                    <label>
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Despejado">
                        <img src="../media/raids/Weather_Icon_Clear_Night.webp" title="Despejado" height="50">
                    </label>
                    <label>
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Parcialmente nublado (día)">
                        <img src="../media/raids/Weather_Icon_Partly_Cloudy_Day.webp" title="Parcialmente nublado (día)" height="50">
                    </label>
                    <label>
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Parcialmente nublado (noche)">
                        <img src="../media/raids/Weather_Icon_Partly_Cloudy_Night.webp" title="Parcialmente nublado (noche)" height="50">
                    </label>
                    <label>
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Nublado">
                        <img src="../media/raids/Weather_Icon_Cloudy.webp" title="Nublado" height="50">
                    </label>
                    <label>
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Lluvia">
                        <img src="../media/raids/Weather_Icon_Rain.webp" title="Lluvia" height="50">
                    </label>
                    <label>
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Viento">
                        <img src="../media/raids/Weather_Icon_Windy.webp" title="Viento" height="50">
                    </label>
                    <label>
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Niebla">
                        <img src="../media/raids/Weather_Icon_Foggy.webp" title="Niebla" height="50">
                    </label>
                    <label>
                        <input type="radio" class="radioImg" name="Tiempo_atmos" value="Nieve">
                        <img src="../media/raids/Weather_Icon_Snow.webp" title="Nieve" height="50">
                    </label>
                    <label>
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

    </footer>
</body>

</html>