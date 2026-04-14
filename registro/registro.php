<?php
require_once('../config.php');
require_once('../db_pdo.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hashedPass = password_hash($_POST["Password"], PASSWORD_DEFAULT);
    $usuario['Username'] = $_POST['Username'];
    $usuario['Email'] = $_POST['Email'];
    $usuario['Password'] = $hashedPass;
    $usuario['Pogo_Username'] = $_POST['Pogo_Username'];
    $usuario['Level'] = $_POST['Level'];
    $usuario['Team'] = $_POST['Team'];
    $friendCodeSeparator = array("-", " ");
    $usuario['Friend_code'] = str_replace($friendCodeSeparator, "", $_POST['Friend_code']);
    $db = db_open();
    $usernameCheck = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Username) = LOWER(?)", [$usuario['Username']]);
    $emailCheck = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Email) = LOWER(?)", [$usuario['Email']]);
    $pogoUsernameCheck = db_query($db, "SELECT * FROM usuarios WHERE LOWER(Pogo_Username) = LOWER(?)", [$usuario['Pogo_Username']]);
    if ($db) {
        if (empty($usernameCheck) && empty($emailCheck) && empty($pogoUsernameCheck) && $_POST["Password"] === $_POST["Password2"]) {
            $id = db_insert($db, 'usuarios', $usuario);
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