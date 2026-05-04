<?php
require_once('../config.php');
require_once('../db_pdo.php');
session_start();
date_default_timezone_set('Europe/Madrid');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario['ID_Lista'] = $_POST['ID_Lista'];
    $usuario['Username'] = $_SESSION['usuario'];
    $usuario['Pase'] = $_POST['Pase'];
    $usuario['Estado'] = $_POST['Estado'];
    $usuario['Invitado_presencial'] = $_POST['Invitado_presencial'];
    $usuario['Invitado_remoto'] = $_POST['Invitado_remoto'];
    $usuario['Hora_apuntado'] = $_POST['Hora_apuntado'];
    $currDate = date("Y-m-d");
    $currTime = date("H:i:s");
    $usuario['Hora_ultimo_cambio'] = $currDate . " " . $currTime;
    $db = db_open();
    if ($db) {
        $comprobacionApuntado = db_query($db, "SELECT * FROM apuntados_lista WHERE ID_Lista = (?) AND Username = (?)", [$usuario['ID_Lista'], $usuario['Username']]);
        if (!empty($comprobacionApuntado)) {
            $id = db_update($db, 'apuntados_lista', $usuario);
            db_close($db);
            print $id;
            #header("Location: ../lista/?id=" . $usuario['ID_Lista']);
        } else {
            print "Error";
            exit;
        }
    } else {
        print "Se ha producido un error de conexión";
        exit;
    }
}