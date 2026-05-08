<?php
$numRemotosTotales = 100;
$numRemotosUsuario = 20;
$remotosApuntados = 95;
$usuarioRemotos = 14;
echo $numRemotosTotales - $remotosApuntados + $usuarioRemotos;
echo "<br>";
if ($numRemotosTotales - $remotosApuntados + $usuarioRemotos < $numRemotosUsuario) {
    echo "Limite activado";
}