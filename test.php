<?php
$maxRaid = 100;
$maxRaidPorUsuario = 20;
$usuarioRemotos = 14;
$apuntados = 95;
echo "Max raid = ".($maxRaid);
echo "<br>";
echo "Mis remotos = ".($usuarioRemotos);
echo "<br>";
echo "Apuntados totales = ".($apuntados);
echo "<br>";
echo "100 - Apuntados = ".($maxRaid - $apuntados);
echo "<br>";
echo "Remotos restantes (para 20) = ".(20 - $usuarioRemotos);
echo "<br>";
echo "Restantes sin mis remotos: ".($maxRaid - $apuntados + $usuarioRemotos);
echo "<br>";
if ($maxRaid - $apuntados + $usuarioRemotos < 20) {
    echo "Limite activado";
}