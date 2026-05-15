<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: ../');
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
        <?php if (isset($_SESSION['advertencia'])): ?>
            <div><?= $_SESSION['advertencia'] ?></div>
            <?php unset($_SESSION['advertencia']) ?>
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
</body>

</html>