<?php
require_once('../config.php');
require_once('../db_pdo.php');
session_start();
date_default_timezone_set('Europe/Madrid');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hashedPass = password_hash($_POST["Password"], PASSWORD_DEFAULT);
    $usuario['Username'] = $_POST['Username'];
    $usernameCheck = False;
    if (strlen($usuario['Username']) <= 20) {
        $usernameCheck = True;
    }
    $usuario['Email'] = $_POST['Email'];
    $emailCheck = False;
    if (strlen($usuario['Email']) <= 100) {
        $emailCheck = True;
    }
    $usuario['Password'] = $hashedPass;
    $usuario['Pogo_Username'] = $_POST['Pogo_Username'];
    $pogoUsernameCheck = False;
    if (strlen($usuario['Pogo_Username']) <= 15) {
        $pogoUsernameCheck = True;
    }
    $usuario['Level'] = $_POST['Level'];
    $minLevel = 1;
    $maxLevel = 80;
    $levelCheck = False;
    if ($usuario['Level'] >= $minLevel && $usuario['Level'] <= $maxLevel) {
        $levelCheck = True;
    }
    $usuario['Team'] = $_POST['Team'];
    $teamCheck = False;
    if ($usuario['Team'] == "Sin equipo") {
        $usuario['Team'] = NULL;
    }
    if ($usuario['Team'] == "Valor" || $usuario['Team'] == "Instinto" || $usuario['Team'] == "Sabiduría" || $usuario['Team'] == NULL) {
        $teamCheck = True;
    }
    $usuario['Friend_code'] = $_POST['Friend_code'];
    if (strpos($usuario['Friend_code'], "-") == 4 && strpos($usuario['Friend_code'], "-", 4 + 1) == 9 && strpos($usuario['Friend_code'], "-", 9 + 1) == False) {
        $usuario['Friend_code'] = str_replace("-", "", $usuario['Friend_code']);
    } elseif (strpos($usuario['Friend_code'], " ") == 4 && strpos($usuario['Friend_code'], " ", 4 + 1) == 9 && strpos($usuario['Friend_code'], " ", 9 + 1) == False) {
        $usuario['Friend_code'] = str_replace(" ", "", $usuario['Friend_code']);
    }
    $friendCodeCheck = False;
    if (is_numeric($usuario['Friend_code']) && strlen($usuario['Friend_code']) == 12) {
        $friendCodeCheck = True;
    }
    $fieldsCheck = False;
    if ($usernameCheck && $emailCheck && $pogoUsernameCheck && $levelCheck && $teamCheck && $friendCodeCheck) {
        $fieldsCheck = True;
    }
    $db = db_open();
    $usernameSearch = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Username) = LOWER(?)", [$usuario['Username']]);
    $emailSearch = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Email) = LOWER(?)", [$usuario['Email']]);
    $pogoUsernameSearch = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Pogo_Username) = LOWER(?)", [$usuario['Pogo_Username']]);
    $emptyCheck = False;
    if (empty($usernameSearch) && empty($emailSearch) && empty($pogoUsernameSearch)) {
        $emptyCheck = True;
    }
    $passwordCheck = False;
    if ($_POST["Password"] === $_POST["Password2"]) {
        $passwordCheck = True;
    }
    $fullCheck = False;
    if ($fieldsCheck && $emptyCheck && $passwordCheck) {
        $fullCheck = True;
    }
    if ($db) {
        if ($fullCheck) {
            $id = db_insert($db, 'usuarios', $usuario);
            $_SESSION['usuario'] = $usuario['Username'];
            $_SESSION['email'] = $usuario['Email'];
            $_SESSION['pogo_username'] = $_POST['Pogo_Username'];
            $_SESSION['login'] = "Tu registro se ha realizado con éxito. ¡Bienvenid@, " . $usuario['Username'] . "!";
            db_close($db);
            header('Location: ../');
        } else {
            if (!(strlen($usuario['Username']) <= 20)) {
                $_SESSION['usernameError'] = "Tu nombre de usuario es demasiado largo";
            }
            if (!(empty($usernameSearch))) {
                $_SESSION['usernameError'] = "Ese nombre de usuario no está disponible";
            }
            if (!(strlen($usuario['Email']) <= 100)) {
                $_SESSION['emailError'] = "Tu correo electrónico es demasiado largo";
            }
            if (!(empty($emailSearch))) {
                $_SESSION['emailError'] = "Ese correo electrónico no está disponible";
            }
            if (!($_POST["Password"] === $_POST["Password2"])) {
                $_SESSION['passwordError'] = "Las contraseñas no coinciden";
            }
            if (!(strlen($usuario['Pogo_Username']) <= 15)) {
                $_SESSION['pogoUsernameError'] = "Tu nombre de usuario de Pokémon GO es demasiado largo";
            }
            if (!(empty($pogoUsernameSearch))) {
                $_SESSION['pogoUsernameError'] = "Ese nombre de usuario de Pokémon GO no está disponible";
            }
            if (!($usuario['Level'] >= $minLevel && $usuario['Level'] <= $maxLevel)) {
                $_SESSION['levelError'] = "Tu nivel de Pokémon GO debe estar entre " . $minLevel . " y " . $maxLevel . " (ambos incluidos)";
            }
            if (!($usuario['Team'] == "Valor" || $usuario['Team'] == "Instinto" || $usuario['Team'] == "Sabiduría" || $usuario['Team'] == NULL)) {
                $_SESSION['teamError'] = "El equipo que has seleccionado no es válido";
            }
            if (!(is_numeric($usuario['Friend_code']) && strlen($usuario['Friend_code']) == 12)) {
                $_SESSION['friendCodeError'] = "Tu código de amigo no es válido";
            }
            header('Location: ../registro');
            exit;
        }
    } else {
        $_SESSION['db_error'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta registrarte de nuevo más tarde.";
        header('Location: ../registro');
        exit;
    }
}
#print_r($_POST);
#print "<br>";
#$hashedPass = password_hash($_POST["Password"], PASSWORD_DEFAULT);
#print $hashedPass;
#print "<br>";
#$verify = password_verify($_POST["Password"], $hashedPass);
#$verify2 = password_verify($_POST["Password2"], $hashedPass);
#$verify3 = password_verify($_POST["Password"], '$2y$10$xlkr3F6o//pEOad8nhOoVe0/Av5mYh.0TnOMrz/W0AvP8alUTV31W');
#print "Password 1 = $verify";
#print "<br>";
#PRINT "Password 2 = $verify2";
#print "<br>";
#PRINT "Password 3 = $verify3";
#print "<br>";
#if ($_POST["Password"] == $_POST["Password2"]) {
#    print "Las contraseñas coinciden";
#}