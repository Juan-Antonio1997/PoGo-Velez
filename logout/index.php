<?php
/* Inicio la sesión (activo las variables $_SESSION) */
session_start();
/* Establezco que la zona horaria por defecto sea la de Europa/Madrid */
date_default_timezone_set('Europe/Madrid');
if (isset($_SESSION['usuario'])) {
    /* Si la sesión de usuario está asignada, desasigno esa variable, y la
    de correo electrónico y usuario de Pokémon GO */
    unset($_SESSION['usuario']);
    unset($_SESSION['email']);
    unset($_SESSION['pogo_username']);
    /* Vacío la variable de sesión */
    $_SESSION = [];
    /* Creo una variable de sesión para una alerta de tipo éxito que indique 
    que la sesión se ha cerrado con éxito */
    $_SESSION['successAlert'] = "Se ha cerrado tu sesión con éxito";
    /* Redirijo al usuario a la página principal */
    header('Location: ../');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
} else {
    /* Si la sesión de usuario no está asignada, creo una variable de sesión para 
    una alerta de tipo error que indique que el usuario no tiene una sesión iniciada */
    $_SESSION['errorAlert'] = "No tienes una sesión iniciada";
    /* Redirijo al usuario a la página principal */
    header('Location: ../');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
    exit;
}
