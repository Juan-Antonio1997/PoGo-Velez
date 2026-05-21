<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: ../');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión - PoGO Vélez-Málaga</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header>
        <a href="../" class="titleLink">
            <h1 class="pageTitle">PoGo Vélez-Málaga</h1>
        </a>
        <nav>
            <ul class="navList">
                <li class="navElement"><a href="../registro">Regístrate</a></li>
                <li class="navSelected"><span>Iniciar sesión</span></li>
            </ul>
        </nav>
    </header>
    <section>
        <?php if (isset($_SESSION['warningAlert'])): ?>
            <div class="alertBox" id="warningAlert">
                <div class="alertWarning">
                    <span class="closeAlertBtn">&times;</span>
                    <?= $_SESSION['warningAlert'] ?>
                </div>
            </div>
            <?php unset($_SESSION['warningAlert']) ?>
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
        <form action="login.php" method="POST">
            <div>
                <label>Usuario o Email: </label>
                <input type="text" name="User" placeholder="Usuario o Email" maxlength="100" required>
            </div>
            <div>
                <label>Contraseña: </label>
                <input type="password" name="Password" placeholder="Contraseña" maxlength="127" required>
            </div>
            <div>
                <input type="submit" value="Acceder">
            </div>
            <div>
                <a class="registerLink" href="../registro">¿No tienes una cuenta? Regístrate aquí</a>
            </div>
        </form>
    </section>
    <footer>
        <div>Juan Antonio Gómez Martín - 2026</div>
        <div>©Niantic ©Pokémon/Nintendo/Creatures/GAME FREAK TM, ® y los nombres de los personajes son marcas comerciales de Nintendo.</div>
    </footer>
    <script src="../script.js"></script>
</body>

</html>