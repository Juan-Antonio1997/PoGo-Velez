<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión - PoGO Vélez-Málaga</title>
</head>
<body>
    <form action="login.php" method="POST">
        <div>
            <label>Usuario o Email</label>
            <input type="text" name="User" placeholder="Usuario o Email" required>
        </div>
        <div>
            <label>Contraseña</label>
            <input type="password" name="Password" placeholder="Contraseña" required>
        </div>
        <div>
            <input type="submit" value="Acceder">
        </div>
    </form>
</body>
</html>