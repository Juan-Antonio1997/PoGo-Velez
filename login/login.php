<?php
/* Obtengo el PDO y la configuración para poder manipular bases de datos */
require_once('../config.php');
require_once('../db_pdo.php');
/* Abro la conexión a la base de datos indicada en el fichero de configuración */
$db = db_open();
/* Inicio la sesión (activo las variables $_SESSION) */
session_start();
/* Establezco que la zona horaria por defecto sea la de Europa/Madrid */
date_default_timezone_set('Europe/Madrid');
if (isset($_SESSION['usuario'])) {
    /* Si el usuario ya ha iniciado sesión (la variable de sesión "usuario" está 
    definida), hago que vuelva a la página principal */
    header('Location: ../');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
}
/* Condición: Si hay conexión a la base de datos */
if ($db) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        /* Si la petición es de tipo POST, miro a ver si hay un usuario con el nombre 
        de usuario o correo electrónico introducidos en el formulario de login */
        $usuario = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Username)=LOWER(?) OR LOWER(Email)=LOWER(?)", [$_POST['User'], $_POST['User']]);
        if (!empty($usuario) && count($usuario) == 1) {
            /* Si no está vacío y solo hay una coincidencia, extraigo las variables de 
            nombre de usuario, email, contraseña (encriptada) y usuario de Pokémon GO */
            $username = $usuario[0]['Username'];
            $email = $usuario[0]['Email'];
            $password = $usuario[0]['Password'];
            $pogo_username = $usuario[0]['Pogo_Username'];
            if (($_POST['User'] === $username || $_POST['User'] === $email) && password_verify($_POST["Password"], $password)) {
                /* Si el usuario o el email introducido coincide con el extraído, y la 
                contraseña introducida es igual a la guardada (comprobado con una 
                verificación de contraseña), establezco las variables de sesión del usuario, 
                email y usuario de Pokémon GO */
                $_SESSION['usuario'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['pogo_username'] = $pogo_username;
                /* Creo una variable de sesión para una alerta de tipo éxito, que será 
                un mensaje de bienvenida */
                $_SESSION['successAlert'] = "¡Bienvenid@, " . $username . "!";
                /* Redirijo al usuario a la página principal */
                header('Location: ../');
                /* Y con "exit" hago que se detenga el script, para que no ejecute el 
                resto de funciones */
                exit;
            } else {
                /* Si la contraseña introducida es distinta a la guardada, creo una variable 
                de sesión para una alerta de tipo error indicando que uno de los datos 
                introducidos es incorrecto, pero sin decir cuál */
                $_SESSION['errorAlert'] = 'Usuario, email o contraseña incorrecto';
                /* Redirijo al usuario a la página de login */
                header('Location: ../login');
                /* Y con "exit" hago que se detenga el script, para que no ejecute el 
                resto de funciones */
                exit;
            }
        } else {
            /* Si el usuario o el email no coinciden con el extraído, creo una variable 
            de sesión para una alerta de tipo error indicando que uno de los datos 
            introducidos es incorrecto, pero sin decir cuál */
            $_SESSION['errorAlert'] = 'Usuario, email o contraseña incorrecto';
            /* Redirijo al usuario a la página de login */
            header('Location: ../login');
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        }
    } else {
        /* Si la petición no es de tipo POST, mando al usuario directamente a la página 
        de login */
        header('Location: ../login');
        /* Y con "exit" hago que se detenga el script, para que no ejecute el 
        resto de funciones */
        exit;
    }
} else {
    /* Si no hay conexión a la base de datos, indico con una variable de sesión para una 
    alerta de tipo error que ha habido un problema */
    $_SESSION['errorAlert'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta iniciar sesión más tarde.";
    /* Redirijo al usuario a la página de login */
    header('Location: ../login');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
}
