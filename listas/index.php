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
        <article>
            <div class="pokeList">
                <div class="pokemonSprite">
                    <img src="../media/pokemon/0381-0.png" width="150">
                </div>
                <div class="pokemonName">
                    <span>Latios</span>
                </div>
                <div class="placeName">
                    <span>Caballo Pegaso</span>
                </div>
                <div class="meetTime">
                    <span>12:15</span>
                </div>
                <div class="lobbyStatus">
                    <span>0/20</span>
                </div>
            </div>
        </article>
    </section>
    <footer>

    </footer>
</body>

</html>