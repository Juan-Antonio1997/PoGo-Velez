<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PoGO Vélez-Málaga</title>
</head>
<body>
    <?php if (isset($_SESSION['usuario'])): ?>
        <p>¡Hola <?=$_SESSION['usuario']?>!</p>
        <p><a href="./logout">Cerrar sesión</a></p>
    <?php else: ?>
        <p>¡Hola invitad@!</p>
        <p><a href="./login">Iniciar sesión</a></p>
        <p><a href="./registro">Regístrate</a></p>
    <?php endif; ?>
</body>
</html>