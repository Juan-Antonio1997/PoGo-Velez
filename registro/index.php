<?php
/* Inicio la sesión (activo las variables $_SESSION) */
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: ../');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
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
            <?php /* Muestro la barra de navegación que se muestra cuando no hay una sesión de usuario activa, 
            pero sin poner enlace en la parte de registro, ya que es esta página */ ?>
            <ul class="navList">
                <li class="navSelected"><span>Regístrate</span></li>
                <li class="navElement"><a href="../login">Iniciar sesión</a></li>
            </ul>
        </nav>
    </header>
    <section>
        <?php /* Si hay una variable de sesión de un error al conectarse a la base de datos, la muestro 
        como alerta y la desasigno. Las alertas tienen un botón de cerrado (marcado con un símbolo de X 
        codificado como "&times;") para que, al ser pulsados, desaparezcan. */ ?>
        <?php if (isset($_SESSION['db_error'])): ?>
            <div class="alertBox" id="dbErrorAlert">
                <div class="alertError">
                    <span class="closeAlertBtn">&times;</span>
                    <?= $_SESSION['db_error'] ?>
                </div>
            </div>
            <?php unset($_SESSION['db_error']) ?>
        <?php endif; ?>
        <?php /* Creo un formulario para el registro de un usuario, haciendo que se ejecute una función al hacer un submit */ ?>
        <form action="registro.php" method="POST" id="RegistroGoVelez" onsubmit="return register()">
            <?php /* Si hay una variable de sesión de un error con el nombre de usuario, la muestro y la desasigno. */ ?>
            <?php if (isset($_SESSION['usernameError'])): ?>
                <div class="alertBox" id="usernameErrorAlert">
                    <div class="alertError">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['usernameError'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['usernameError']) ?>
            <?php endif; ?>
            <div id="usernameDiv">
                <label>Usuario: </label>
                <input type="text" name="Username" placeholder="Usuario" maxlength="20" required>
            </div>
            <?php /* Si hay una variable de sesión de un error con el correo electrónico, la muestro y la desasigno. */ ?>
            <?php if (isset($_SESSION['emailError'])): ?>
                <div class="alertBox" id="emailErrorAlert">
                    <div class="alertError">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['emailError'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['emailError']) ?>
            <?php endif; ?>
            <div id="emailDiv">
                <label>Email: </label>
                <input type="email" name="Email" placeholder="Email" maxlength="100" required>
            </div>
            <?php /* Si hay una variable de sesión de un error con la contraseña (si no coinciden, por ejemplo), la muestro y la desasigno. */ ?>
            <?php if (isset($_SESSION['passwordError'])): ?>
                <div class="alertBox" id="passwordErrorAlert">
                    <div class="alertError">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['passwordError'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['passwordError']) ?>
            <?php endif; ?>
            <?php /* Hago que los campos de contraseña tengan que cumplir con un patrón determinado, explicado en el atributo "title" */ ?>
            <div id="passwordDiv">
                <label>Contraseña: </label>
                <input type="password" name="Password" placeholder="Contraseña"
                    title="La contraseña tiene que tener al menos 6 caracteres. Esos caracteres pueden ser mayúsculas, minúsculas, números o uno de estos caracteres especiales: @ # $ % ^ & - + = ( )"
                    pattern="^[A-Za-z0-9\@\#\$\%\^\&\-\+\=\(\)]{6,}$" maxlength="127" required>
            </div>
            <div id="password2Div">
                <label>Confirma tu contraseña: </label>
                <input type="password" name="Password2" placeholder="Contraseña"
                    title="La contraseña tiene que tener al menos 6 caracteres. Esos caracteres pueden ser mayúsculas, minúsculas, números o uno de estos caracteres especiales: @ # $ % ^ & - + = ( )"
                    pattern="^[A-Za-z0-9\@\#\$\%\^\&\-\+\=\(\)]{6,}$" maxlength="127" required>
            </div>
            <?php /* Si hay una variable de sesión de un error con el nombre de usuario de Pokémon Go, la muestro y la desasigno. */ ?>
            <?php if (isset($_SESSION['pogoUsernameError'])): ?>
                <div class="alertBox" id="pogoUsernameErrorAlert">
                    <div class="alertError">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['pogoUsernameError'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['pogoUsernameError']) ?>
            <?php endif; ?>
            <?php /* La verdad es que hice los campos nombre de usuario y nombre de usuario en Pokémon GO sean distintos para aquellas 
            personas (como yo) que quieren tener un nombre de usuario diferente al nombre de usuario en Pokémon GO.
            Ejemplo personal: Nombre de usuario - 08Juan80 | Nombre de usuario en Pokémon GO - x08Juan80x (08Juan80 no está disponible) */ ?>
            <div id="pogoUsernameDiv">
                <label>Nombre de usuario en Pokémon GO: </label>
                <input type="text" name="Pogo_Username" placeholder="Nombre de usuario en Pokémon GO" maxlength="15" required>
            </div>
            <?php /* Si hay una variable de sesión de un error con el nivel, la muestro y la desasigno. */ ?>
            <?php if (isset($_SESSION['levelError'])): ?>
                <div class="alertBox" id="levelErrorAlert">
                    <div class="alertError">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['levelError'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['levelError']) ?>
            <?php endif; ?>
            <div id="levelDiv">
                <label>Nivel: </label>
                <input type="number" name="Level" placeholder="1-80" title="El nivel es: Mínimo 1 - Máximo: 80" min=1 max=80 required>
            </div>
            <?php /* Si hay una variable de sesión de un error con el equipo, la muestro y la desasigno. */ ?>
            <?php if (isset($_SESSION['teamError'])): ?>
                <div class="alertBox" id="teamErrorAlert">
                    <div class="alertError">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['teamError'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['teamError']) ?>
            <?php endif; ?>
            <?php /* En el campo de selección de equipo he ocultado los botones de radio con CSS, y he hecho que las imagenes funcionen 
            como esos botones. Cada imagen tendrá un borde negro si no está seleccionada, y un borde rojo si está marcada */ ?>
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
            <?php /* Si hay una variable de sesión de un error con el código de amigo, la muestro y la desasigno. */ ?>
            <?php if (isset($_SESSION['friendCodeError'])): ?>
                <div class="alertBox" id="friendCodeAlert">
                    <div class="alertError">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['friendCodeError'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['friendCodeError']) ?>
            <?php endif; ?>
            <?php /* Hago que el campo de código de amigo tenga que cumplir con un patrón determinado, explicado en el atributo "title", con ejemplos */ ?>
            <div id="friendCodeDiv">
                <label>Código de amigo: </label>
                <input type="text" name="Friend_code" placeholder="Código de amigo"
                    title="Un código de amigo tiene 12 dígitos. &#10;Formatos aceptables: '1234-5678-9012', '1234 5678 9012' y '123456789012'. &#10;Nota: Estos códigos de amigo son ejemplos."
                    pattern="(?:[\d]{12}|[\d]{4}[\-][\d]{4}[\-][\d]{4}|[\d]{4}[\s][\d]{4}[\s][\d]{4})" maxlength="14" required>
            </div>
            <div id="submitDiv">
                <input type="submit" value="Regístrame">
            </div>
        </form>
        <?php /* Introduzco un enlace para los que ya tengan una cuenta, que se vayan a la página de login */ ?>
        <div>
            <a class="loginLink" href="../login">¿Ya tienes una cuenta? Inicia sesión aquí</a>
        </div>
    </section>
    <footer>
        <div>Juan Antonio Gómez Martín - 2026</div>
        <div>©Niantic ©Pokémon/Nintendo/Creatures/GAME FREAK TM, ® y los nombres de los personajes son marcas comerciales de Nintendo.</div>
    </footer>
    <script src="script.js"></script>
</body>

</html>