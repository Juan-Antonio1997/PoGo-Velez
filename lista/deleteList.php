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
        $borrarLista = False;
        $usuariosBorrarLista = db_query($db, "SELECT u.Username, p.P_BorrarListasAjenas 
        FROM usuarios AS u
        INNER JOIN perfiles AS p on p.ID_Perfil = u.ID_Perfil
        WHERE p.P_BorrarListasAjenas = (?)", [1]);
        foreach ($usuariosBorrarLista as $usuarioBorrarLista) {
            if ($usuario['Username'] == $usuarioBorrarLista['Username']) {
                $borrarLista = True;
            }
        }
        if ($comprobacionCreador || $borrarLista) {;
            $removeParticipants = db_query($db, "DELETE FROM apuntados_lista
            WHERE ID_Lista = (?)", [$usuario['ID_Lista']]);
            $deleteList = db_delete_by_id($db, 'listas', $usuario['ID_Lista'], "ID_Lista");
            db_close($db);
            $_SESSION['deletedList'] = "Se ha borrado la lista con ID " . $usuario['ID_Lista'] . " con éxito";
            header("Location: ../");
        } else {
            $_SESSION['deleteListError'] = "Se ha producido un error al intentar borrar esta lista. Por favor, inténtalo más tarde.";
            header("Location: ../lista/?id=" . $usuario['ID_Lista']);
            exit;
        }
    } else {
        $_SESSION['deleteListError'] = "Se ha producido un error de conexión a la base de datos. Por favor, intenta borrar esta lista más tarde.";
        header("Location: ../lista/?id=" . $usuario['ID_Lista']);
        exit;
    }
} else {
    header('Location: ../');
    exit;
}