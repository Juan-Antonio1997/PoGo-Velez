<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PoGO Vélez-Málaga - Lista X</title>
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
            <div class="pokeList">
                <div class="pokemonSprite">
                    <img src="../media/pokemon/0381-0.png" width="150">
                </div>
                <div class="pokemonName">
                    <span>Latios</span>
                </div>
                <div class="typeName">
                    <span>Dragón/Psíquico</span>
                </div>
                <div class="meetPlace">
                    <span>Caballo Pegaso</span>
                </div>
                <div class="meetTime">
                    <span>12:15 (cierra a las 12:30)</span>
                </div>
                <div class="listHost">
                    <span>Host: x08Juan80x</span>
                </div>
                <div class="currentWeather">
                    <span>Tiempo: <img src="../media/raids/Weather_Icon_Clear_Day.webp" alt="Soleado" title="Soleado - Potencia a los tipo Planta, Fuego y Tierra" height="50"></span>
                </div>
                <div class="perfectPC">
                    <span>100% = 2178 PC</span>
                </div>
                <div class="baseStats">
                    <span>Estadísticas base: 268 ataque / 212 defensa / 190 PS</span>
                </div>
                <div class="counterURL">
                    <span><a href="https://www.pokebattler.com/raids/latios">Counters</a></span>
                </div>
                <div class="listParticipants">
                    <span>1/20 (0 remotos)</span>
                </div>
                <div class="meetTime">
                    <span><img src="../media/raids/Regular_And_Premium_Pass.webp" height="50"> <img src="../media/raids/Remote_Raid_Pass.webp" height="50"></span>
                </div>
                <div class="meetTime">
                    <span>🚶 Voy - ✅ Estoy - 🐌 Llego Tarde - ❌ No voy</span>
                </div>
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