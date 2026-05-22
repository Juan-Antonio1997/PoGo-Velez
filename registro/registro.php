<?php
/* Obtengo el PDO y la configuración para poder manipular bases de datos */
require_once('../config.php');
require_once('../db_pdo.php');
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /* Si la petición es de tipo POST, encripto la contraseña del usuario con
    la función password_hash */
    $hashedPass = password_hash($_POST["Password"], PASSWORD_DEFAULT);
    /* Asigno a la variable usuario['Username'] el valor del nombre de usuario */
    $usuario['Username'] = $_POST['Username'];
    /* Creo una variable para comprobar si el nombre de usuario cumple una condición */
    $usernameCheck = False;
    if (strlen($usuario['Username']) <= 20) {
        /* La condición es que la longitud del nombre de usuario sea menor o igual a 20.
        Si es así, cambio el valor de la variable de comprobación a "True" */
        $usernameCheck = True;
    }
    /* Asigno a la variable usuario['Email'] el valor del correo electrónico */
    $usuario['Email'] = $_POST['Email'];
    /* Creo una variable para comprobar si el correo electrónico cumple una condición */
    $emailCheck = False;
    if (strlen($usuario['Email']) <= 100) {
        /* La condición es que la longitud del correo sea menor o igual a 100. Si es así, 
        cambio el valor de comprobación a "True" */
        $emailCheck = True;
    }
    /* Asigno a la variable usuario['Password'] el valor de la contraseña encriptada */
    $usuario['Password'] = $hashedPass;
    /* Asigno a la variable usuario['Pogo_Username'] el valor del nombre de usuario 
    de Pokémon GO */
    $usuario['Pogo_Username'] = $_POST['Pogo_Username'];
    /* Creo una variable para comprobar si el nombre de usuario de Pokémon GO cumple 
    una condición */
    $pogoUsernameCheck = False;
    if (strlen($usuario['Pogo_Username']) <= 15) {
        /* La condición es que la longitud del usuario de Pokémon GO sea menor o igual 
        a 15. Si es así, cambio el valor de comprobación a "True" */
        $pogoUsernameCheck = True;
    }
    /* Asigno a la variable usuario['Level'] el valor del nivel introducido en el 
    formulario */
    $usuario['Level'] = $_POST['Level'];
    /* Establezco una variable con el nivel mínimo que puede tener un usuario en Pokémon GO */
    $minLevel = 1;
    /* Establezco una variable con el nivel máximo que puede tener un usuario en Pokémon GO */
    $maxLevel = 80;
    /* Creo una variable para comprobar si el nivel cumple una condición */
    $levelCheck = False;
    if ($usuario['Level'] >= $minLevel && $usuario['Level'] <= $maxLevel) {
        /* La condición es que el nivel tiene que estar entre el nivel mínimo y el máximo 
        (ambos incluidos). Si es así, cambio el valor de comprobación a "True" */
        $levelCheck = True;
    }
    /* Asigno a la variable usuario['Team'] el valor del equipo seleccionado 
    (si no se ha seleccionado ninguno, será "Sin equipo") */
    $usuario['Team'] = $_POST['Team'];
    /* Creo una variable para comprobar si el equipo del usuario cumple una condición */
    $teamCheck = False;
    if ($usuario['Team'] == "Sin equipo") {
        /* Antes de nada, si el equipo tiene de valor "Sin equipo", marco su valor como nulo */
        $usuario['Team'] = NULL;
    }
    if ($usuario['Team'] == "Valor" || $usuario['Team'] == "Instinto" || $usuario['Team'] == "Sabiduría" || $usuario['Team'] == NULL) {
        /* La condición es que el nombre del equipo sea o "Valor", o "Instinto", o "Sabiduría", o nulo.
        Si es así, cambio el valor de comprobación a "True" */
        $teamCheck = True;
    }
    /* Asigno a la variable usuario['Friend_code'] el valor del código de amigo 
    del usuario */
    $usuario['Friend_code'] = $_POST['Friend_code'];
    if (strpos($usuario['Friend_code'], "-") == 4 && strpos($usuario['Friend_code'], "-", 4 + 1) == 9 && strpos($usuario['Friend_code'], "-", 9 + 1) == False) {
        /* Compruebo que haya un guión "-" solo en las posiciones 4 y 9. Si es así, los quito */
        $usuario['Friend_code'] = str_replace("-", "", $usuario['Friend_code']);
    } elseif (strpos($usuario['Friend_code'], " ") == 4 && strpos($usuario['Friend_code'], " ", 4 + 1) == 9 && strpos($usuario['Friend_code'], " ", 9 + 1) == False) {
        /* Si no, compruebo que haya un espacio " " solo en las posiciones 4 y 9. Si es así, los quito */
        $usuario['Friend_code'] = str_replace(" ", "", $usuario['Friend_code']);
    }
    /* Creo una variable para comprobar si el código de amigo cumple unas condiciones */
    $friendCodeCheck = False;
    if (is_numeric($usuario['Friend_code']) && strlen($usuario['Friend_code']) == 12) {
        /* Las condiciones son que el código de amigo sea numérico (con los guiones o espacios quitados) 
        y que su longitud sea exáctamente 12. Si es así, cambio el valor de comprobación a "True" */
        $friendCodeCheck = True;
    }
    /* Creo una variable para comprobar que las variables de comprobación anteriores tienen como valor "True" */
    $fieldsCheck = False;
    if ($usernameCheck && $emailCheck && $pogoUsernameCheck && $levelCheck && $teamCheck && $friendCodeCheck) {
        /* Si es así, cambio su valor a "True" */
        $fieldsCheck = True;
    }
    /* Abro la conexión a la base de datos indicada en el fichero de configuración */
    $db = db_open();
    /* Hago una consulta a la tabla usuarios para ver si el nombre de usuario está registrado o no */
    $usernameSearch = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Username) = LOWER(?)", [$usuario['Username']]);
    /* Hago una consulta a la tabla usuarios para ver si el correo electrónico está registrado o no */
    $emailSearch = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Email) = LOWER(?)", [$usuario['Email']]);
    /* Hago una consulta a la tabla usuarios para ver si el nombre de usuario de Pokémon GO está registrado o no */
    $pogoUsernameSearch = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Pogo_Username) = LOWER(?)", [$usuario['Pogo_Username']]);
    /* Creo una variable para comprobar que las consultas anteriores no devuelven nada (no están registrados) */
    $emptyCheck = False;
    if (empty($usernameSearch) && empty($emailSearch) && empty($pogoUsernameSearch)) {
        /* Si es así, cambio el valor de la variable de comprobación a "True" */
        $emptyCheck = True;
    }
    /* Creo una variable de comprobación para ver si la contraseña cumple con unas condiciones */
    $passwordCheck = False;
    if ($_POST["Password"] === $_POST["Password2"] && ((strlen($_POST["Password"]) >= 6 && strlen($_POST["Password"]) <= 127) && (strlen($_POST["Password2"]) >= 6 && strlen($_POST["Password2"]) <= 127))) {
        /* Las condiciones son que las contraseñas sean iguales y que su longitud (sin hashear) esté comprendida entre 
        6 y 127 (ambos incluidos). Si es así, cambio el valor de esa variable a "True" */
        $passwordCheck = True;
    }
    /* Creo una variable para comprobar que todas las comprobaciones realizadas tienen un valor "True" */
    $fullCheck = False;
    if ($fieldsCheck && $emptyCheck && $passwordCheck) {
        /* Compruebo si eso es cierto y, si es así, cambio su valor a "True". Hago esto para no tener que introducir 
        todas las variables de comprobación */
        $fullCheck = True;
    }
    /* Condición: Si hay conexión a la base de datos */
    if ($db) {
        if ($fullCheck) {
            /* Si todas las condiciones se cumplen, inserto el usuario a la tabla "usuarios" dentro de la base de datos */
            $id = db_insert($db, 'usuarios', $usuario);
            /* Creo una variable de sesión con el usuario, correo electrónico y usuario de Pokémon GO para que ya tenga 
            la sesión iniciada tras el registro */
            $_SESSION['usuario'] = $usuario['Username'];
            $_SESSION['email'] = $usuario['Email'];
            $_SESSION['pogo_username'] = $_POST['Pogo_Username'];
            /* Cierro la conexión a la base de datos */
            db_close($db);
            /* Creo una variable de sesión para una alerta de tipo éxito indicando que el registro se ha realizado con éxito, 
            y dando la bienvenida al usuario */
            $_SESSION['successAlert'] = "Tu registro se ha realizado con éxito. ¡Bienvenid@, " . $usuario['Username'] . "!";
            /* Redirijo al usuario a la página principal */
            header('Location: ../');
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        } else {
            /* Si alguna de las condiciones no se cumplen, creo variables de sesión para alertas de tipo error para cada 
            condición que no se ha cumplido */
            if (!(strlen($usuario['Username']) <= 20)) {
                /* Si la longitud del nombre de usuario no es menor o igual que 20, aparecerá esta alerta */
                $_SESSION['usernameError'] = "Tu nombre de usuario es demasiado largo";
            }
            if (!(empty($usernameSearch))) {
                /* Si ya está registrado el usuario, aparecerá esta alerta en vez de la anterior, ya que la longitud del 
                usuario ya es menor o igual que 20 si ya está registrado */
                $_SESSION['usernameError'] = "Ese nombre de usuario no está disponible";
            }
            if (!(strlen($usuario['Email']) <= 100)) {
                /* Si la longitud del correo electrónico no es menor o igual que 100, aparecerá esta alerta */
                $_SESSION['emailError'] = "Tu correo electrónico es demasiado largo";
            }
            if (!(empty($emailSearch))) {
                /* Si ya está registrado el email, aparecerá esta alerta en vez de la anterior, ya que la longitud del 
                correo electrónico tiene que ser menor o igual que 100 si ya está registrado */
                $_SESSION['emailError'] = "Ese correo electrónico no está disponible";
            }
            if (!($_POST["Password"] === $_POST["Password2"])) {
                /* Si las contraseñas no coinciden, se mostrará esta alerta */
                $_SESSION['passwordError'] = "Las contraseñas no coinciden";
            } elseif ($_POST["Password"] === $_POST["Password2"] && (strlen($_POST["Password"]) < 6 && strlen($_POST["Password2"]) < 6)) {
                /* Si coinciden, pero su longitud es menor que 6, se mostrará esta alerta en vez de la anterior */
                $_SESSION['passwordError'] = "La contraseña es demasiado corta";
            } elseif ($_POST["Password"] === $_POST["Password2"] && (strlen($_POST["Password"]) > 127 && strlen($_POST["Password"]) > 127)) {
                /* Si coinciden, pero su longitud es mayor que 127, se mostrará esta alerta en vez de las 2 anteriores */
                $_SESSION['passwordError'] = "La contraseña es demasiado larga";
            }
            if (!(strlen($usuario['Pogo_Username']) <= 15)) {
                /* Si la longitud del nombre de usuario de Pokémon Go no es menor o igual que 15, aparecerá esta alerta */
                $_SESSION['pogoUsernameError'] = "Tu nombre de usuario de Pokémon GO es demasiado largo";
            }
            if (!(empty($pogoUsernameSearch))) {
                /* Si ya está registrado el usuario de Pokémon GO, aparecerá esta alerta en vez de la anterior, ya que 
                la longitud del nombre de usuario de Pokémon GO tiene que ser menor o igual a 15 si ya está registrado */
                $_SESSION['pogoUsernameError'] = "Ese nombre de usuario de Pokémon GO no está disponible";
            }
            if (!($usuario['Level'] >= $minLevel && $usuario['Level'] <= $maxLevel)) {
                /* Si el nivel del usuario no está entre el mínimo y el máximo (ambos incluidos), se mostrará esta alerta */
                $_SESSION['levelError'] = "Tu nivel de Pokémon GO debe estar entre " . $minLevel . " y " . $maxLevel . " (ambos incluidos)";
            }
            if (!($usuario['Team'] == "Valor" || $usuario['Team'] == "Instinto" || $usuario['Team'] == "Sabiduría" || $usuario['Team'] == NULL)) {
                /* Si el nombre del equipo no es ni "Valor", ni "Instinto", ni "Sabiduría", ni tiene valor nulo, aparecerá 
                esta alerta */
                $_SESSION['teamError'] = "El equipo que has seleccionado no es válido";
            }
            if (!(is_numeric($usuario['Friend_code']) && strlen($usuario['Friend_code']) == 12)) {
                /* Si el código de amigo no es ni numérico ni su longitud es exáctamente 12, se mostrará esta alerta */
                $_SESSION['friendCodeError'] = "Tu código de amigo no es válido";
            }
            /* Como el registro no se realizó con éxito, redirijo al usuario a la página de registro */
            header('Location: ../registro');
            /* Y con "exit" hago que se detenga el script, para que no ejecute el 
            resto de funciones */
            exit;
        }
    } else {
        /* Creo una variable de sesión para una alerta de tipo error indicando que se ha producido un error de conexión 
        a la base de datos */
        $_SESSION['db_error'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta registrarte de nuevo más tarde.";
        /* Como no se pudo hacer el registro, redirijo al usuario a la página de registro */
        header('Location: ../registro');
        /* Y con "exit" hago que se detenga el script, para que no ejecute el 
        resto de funciones */
        exit;
    }
} else {
    /* Si la petición no es de tipo POST, mando al usuario directamente a la página de registro */
    header('Location: ../registro');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el resto de funciones */
    exit;
}
