<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - PoGO Vélez-Málaga</title>
</head>

<body>
    <form action="registro.php" method="POST" id="RegistroGoVelez" onsubmit="return register()">
        <div>
            <label>Usuario</label>
            <input type="text" name="Username" placeholder="Usuario" maxlength="20" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="Email" placeholder="Email" maxlength="100" required>
        </div>
        <div>
            <label>Contraseña</label>
            <input type="password" name="Password" placeholder="Contraseña"
                title="La contraseña tiene que tener al menos 6 carácteres. Esos carácteres pueden ser mayúsculas, minúsculas, números o uno de estos carácteres especiales: @ # $ % ^ & - + = ( )"
                pattern="^[A-Za-z0-9\@\#\$\%\^\&\-\+\=\(\)]{6,}$" maxlength="127" required>
        </div>
        <div>
            <label>Confirma tu contraseña</label>
            <input type="password" name="Password2" placeholder="Contraseña"
                title="La contraseña tiene que tener al menos 6 carácteres. Esos carácteres pueden ser mayúsculas, minúsculas, números o uno de estos carácteres especiales: @ # $ % ^ & - + = ( )"
                pattern="^[A-Za-z0-9\@\#\$\%\^\&\-\+\=\(\)]{6,}$" maxlength="127" required>
        </div>
        <div>
            <label>Usuario de Pokémon GO</label>
            <input type="text" name="Pogo_Username" placeholder="Usuario de Pokémon GO" maxlength="15" required>
        </div>
        <div>
            <label>Nivel</label>
            <input type="number" name="Level" placeholder="1-80" title="El nivel es: Mínimo 1 - Máximo: 80" min=1 max=80 required>
        </div>
        <div>
            <label>Equipo:</label><br>
            <input type="hidden" id="Sin_equipo" name="Team" value="Sin equipo">
            <div class="team">
                <label for="Instinto"><img src="../media/website/Logo_Equipo_Instinto_GO.png" alt="Instinto"
                        title="Instinto" width="50" height="50"></label>
                <input type="radio" class="radioButton" id="Instinto" name="Team" value="Instinto"
                    required>
            </div>
            <div class="team">
                <label for="Sabiduría"><img src="../media/website/Logo_Equipo_Sabiduría_GO.png" alt="Sabiduría"
                        title="Sabiduría" width="50" height="50"></label>
                <input type="radio" class="radioButton" id="Sabiduría" name="Team" value="Sabiduría"
                    required>
            </div>
            <div class="team">
                <label for="Valor"><img src="../media/website/Logo_Equipo_Valor_GO.png" alt="Valor" title="Valor"
                        width="50" height="50"></label>
                <input type="radio" class="radioButton" id="Valor" name="Team" value="Valor" required>
            </div>
        </div>
        <div>
            <label>Código de amigo</label>
            <input type="text" name="Friend_code" placeholder="Código de amigo"
                title="Un código de amigo tiene 12 dígitos. &#10;Formatos aceptables: '1234-5678-9012', '1234 5678 9012' y '123456789012'. &#10;Nota: Estos códigos de amigo son ejemplos."
                pattern="(?:[\d]{12}|[\d]{4}[\-][\d]{4}[\-][\d]{4}|[\d]{4}[\s][\d]{4}[\s][\d]{4})" maxlength="14" required>
        </div>
        <div>
            <input type="submit" value="Regístrame">
        </div>
    </form>
    <script src="script.js"></script>
</body>

</html>