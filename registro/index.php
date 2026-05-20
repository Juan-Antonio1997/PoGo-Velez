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
    <title>Registro - PoGO Vélez-Málaga</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header>
        <a href="../" class="titleLink">
            <h1 class="pageTitle">PoGo Vélez-Málaga</h1>
        </a>
        <nav>
            <ul class="navList">
                <li class="navSelected"><span>Regístrate</span></li>
                <li class="navElement"><a href="../login">Iniciar sesión</a></li>
            </ul>
        </nav>
    </header>
    <section>
        <form action="registro.php" method="POST" id="RegistroGoVelez" onsubmit="return register()">
            <?php if (isset($_SESSION['usernameError'])): ?>
                <div><?= $_SESSION['usernameError'] ?></div>
                <?php unset($_SESSION['usernameError']) ?>
            <?php endif; ?>
            <div>
                <label>Usuario: </label>
                <input type="text" name="Username" placeholder="Usuario" maxlength="20" required>
            </div>
            <?php if (isset($_SESSION['emailError'])): ?>
                <div><?= $_SESSION['emailError'] ?></div>
                <?php unset($_SESSION['emailError']) ?>
            <?php endif; ?>
            <div>
                <label>Email: </label>
                <input type="email" name="Email" placeholder="Email" maxlength="100" required>
            </div>
            <?php if (isset($_SESSION['passwordError'])): ?>
                <div><?= $_SESSION['passwordError'] ?></div>
                <?php unset($_SESSION['passwordError']) ?>
            <?php endif; ?>
            <div>
                <label>Contraseña: </label>
                <input type="password" name="Password" placeholder="Contraseña"
                    title="La contraseña tiene que tener al menos 6 carácteres. Esos carácteres pueden ser mayúsculas, minúsculas, números o uno de estos carácteres especiales: @ # $ % ^ & - + = ( )"
                    pattern="^[A-Za-z0-9\@\#\$\%\^\&\-\+\=\(\)]{6,}$" maxlength="127" required>
            </div>
            <div>
                <label>Confirma tu contraseña: </label>
                <input type="password" name="Password2" placeholder="Contraseña"
                    title="La contraseña tiene que tener al menos 6 carácteres. Esos carácteres pueden ser mayúsculas, minúsculas, números o uno de estos carácteres especiales: @ # $ % ^ & - + = ( )"
                    pattern="^[A-Za-z0-9\@\#\$\%\^\&\-\+\=\(\)]{6,}$" maxlength="127" required>
            </div>
            <?php if (isset($_SESSION['pogoUsernameError'])): ?>
                <div><?= $_SESSION['pogoUsernameError'] ?></div>
                <?php unset($_SESSION['pogoUsernameError']) ?>
            <?php endif; ?>
            <div>
                <label>Usuario de Pokémon GO: </label>
                <input type="text" name="Pogo_Username" placeholder="Usuario de Pokémon GO" maxlength="15" required>
            </div>
            <?php if (isset($_SESSION['levelError'])): ?>
                <div><?= $_SESSION['levelError'] ?></div>
                <?php unset($_SESSION['levelError']) ?>
            <?php endif; ?>
            <div>
                <label>Nivel: </label>
                <input type="number" name="Level" placeholder="1-80" title="El nivel es: Mínimo 1 - Máximo: 80" min=1 max=80 required>
            </div>
            <?php if (isset($_SESSION['teamError'])): ?>
                <div><?= $_SESSION['teamError'] ?></div>
                <?php unset($_SESSION['teamError']) ?>
            <?php endif; ?>
            <div id="teamSelection">
                <label>Equipo: <span id="teamName"></span></label><br>
                <input type="hidden" id="Sin_equipo" name="Team" value="Sin equipo">
                <label for="Instinto" class="teamList">
                    <input type="radio" class="radioImg" id="Instinto" name="Team" value="Instinto" required>
                    <img src="../media/website/Logo_Equipo_Instinto_GO.png" alt="Instinto" title="Instinto" width="50" height="50">
                </label>
                <label for="Sabiduría" class="teamList">
                    <input type="radio" class="radioImg" id="Sabiduría" name="Team" value="Sabiduría" required>
                    <img src="../media/website/Logo_Equipo_Sabiduría_GO.png" alt="Sabiduría" title="Sabiduría" width="50" height="50">
                </label>
                <label for="Valor" class="teamList">
                    <input type="radio" class="radioImg" id="Valor" name="Team" value="Valor" required>
                    <img src="../media/website/Logo_Equipo_Valor_GO.png" alt="Valor" title="Valor" width="50" height="50">
                </label>
            </div>
            <?php if (isset($_SESSION['friendCodeError'])): ?>
                <div><?= $_SESSION['friendCodeError'] ?></div>
                <?php unset($_SESSION['friendCodeError']) ?>
            <?php endif; ?>
            <div>
                <label>Código de amigo: </label>
                <input type="text" name="Friend_code" placeholder="Código de amigo"
                    title="Un código de amigo tiene 12 dígitos. &#10;Formatos aceptables: '1234-5678-9012', '1234 5678 9012' y '123456789012'. &#10;Nota: Estos códigos de amigo son ejemplos."
                    pattern="(?:[\d]{12}|[\d]{4}[\-][\d]{4}[\-][\d]{4}|[\d]{4}[\s][\d]{4}[\s][\d]{4})" maxlength="14" required>
            </div>
            <div>
                <input type="submit" value="Regístrame">
            </div>
            <?php if (isset($_SESSION['db_error'])): ?>
                <div><?= $_SESSION['db_error'] ?></div>
                <?php unset($_SESSION['db_error']) ?>
            <?php endif; ?>
        </form>
        <a class="loginLink" href="../login">¿Ya tienes una cuenta? Inicia sesión aquí</a>
    </section>
    <script src="script.js"></script>
</body>

</html>