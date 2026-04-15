<?php
require_once('../config.php');
require_once('../db_pdo.php');
session_start();
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
    $levelCheck = False;
    if ($usuario['Level'] >= 1 && $usuario['Level'] <= 80) {
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
    $friendCodeSeparator = array("-", " ");
    $usuario['Friend_code'] = str_replace($friendCodeSeparator, "", $_POST['Friend_code']);
    $friendCodeCheck = False;
    if (strlen($usuario['Friend_code']) == 12) {
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
            db_close($db);
            header('Location: ../');
        } else {
            print "Error";
            exit;
        }
    } else {
        print "Se ha producido un error de conexión";
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