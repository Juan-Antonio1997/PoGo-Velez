<?php
require_once('../config.php');
require_once('../db_pdo.php');
session_start();
date_default_timezone_set('Europe/Madrid');
if (!isset($_SESSION['usuario'])) {
    $_SESSION['advertencia'] = "¡Tienes que iniciar sesión antes de poder borrar una lista!";
    header('Location: ../login');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario['ID_Lista'] = $_POST['ID_Lista'];
    $usuario['Username'] = $_SESSION['usuario'];
    $db = db_open();
    if ($db) {
        $comprobacionCreador = False;
        $lista = db_query($db, "SELECT Creado_por FROM listas WHERE ID_Lista = (?)", [$usuario['ID_Lista']]);
        if ($lista[0]['Creado_por'] == $usuario['Username']) {
            $comprobacionCreador = True;
        }
        if ($comprobacionCreador) {;
            $removeParticipants = db_query($db, "DELETE FROM apuntados_lista
            WHERE ID_Lista = (?)", [$usuario['ID_Lista']]);
            $deleteList = db_delete_by_id($db, 'listas', $usuario['ID_Lista'], "ID_Lista");
            db_close($db);
            header("Location: ../");
        } else {
            print "Error";
            exit;
        }
    } else {
        print "Se ha producido un error de conexión";
        exit;
    }
}