<?php
include 'datos.php';
if (!isset($_SESSION['usuario'])) {
    $_SESSION['warningAlert'] = "¡Tienes que iniciar sesión antes de poder acceder a una lista!";
    header('Location: ../login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PoGO Vélez-Málaga - Lista <?= $_GET['id'] ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header>
        <a href="../" class="titleLink">
            <h1 class="pageTitle">PoGo Vélez-Málaga</h1>
        </a>
    </header>
    <nav>
        <?php if (isset($_SESSION['usuario'])): ?>
            <ul class="navList">
                <li class="navElement"><a href="../logout">Cerrar sesión</a></li>
                <li class="navElement"><span>¡Hola <?= $_SESSION['usuario'] ?>!</span></li>
            </ul>
        <?php else: ?>
            <ul class="navList">
                <li class="navElement"><a href="../registro">Regístrate</a></li>
                <li class="navElement"><a href="../login">Iniciar sesión</a></li>
            </ul>
        <?php endif; ?>
    </nav>
    <section>
        <article>
            <?php if (isset($_SESSION['successAlert'])): ?>
                <div class="alertBox" id="successAlert">
                    <div class="alertSuccess">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['successAlert'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['successAlert']) ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['warningAlert'])): ?>
                <div class="alertBox" id="warningAlert">
                    <div class="alertWarning">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['warningAlert'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['warningAlert']) ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['errorAlert'])): ?>
                <div class="alertBox" id="errorAlert">
                    <div class="alertError">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['errorAlert'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['errorAlert']) ?>
            <?php endif; ?>
            <?php if (!empty($lista)): ?>
                <div class="pokeList">
                    <?php if ($lista[0]['Tipo_Raid'] == "Oscura"): ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?> Oscuro" title="<?= $lista[0]['Nombre'] ?> Oscuro">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                            <img id="shadowIcon" src="../media/raids/Shadow.webp" height="60" alt="Oscuro" title="Oscuro">
                        </div>
                        <div class="pokemonName">
                            <span><?= $lista[0]['Nombre'] ?> Oscuro</span>
                        </div>
                    <?php elseif ($lista[0]['Tipo_Raid'] == "Dinamax"): ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?> Dinamax" title="<?= $lista[0]['Nombre'] ?> Dinamax">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                            <img id="maxIcon" src="../media/raids/Dynamax.webp" height="90" alt="Dinamax" title="Dinamax">
                        </div>
                        <div class="pokemonName">
                            <span><?= $lista[0]['Nombre'] ?> Dinamax</span>
                        </div>
                    <?php elseif ($lista[0]['Tipo_Raid'] == "Gigamax"): ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?>" title="<?= $lista[0]['Nombre'] ?>">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                            <img id="maxIcon" src="../media/raids/Gigantamax.webp" height="90" alt="Gigamax" title="Gigamax">
                        </div>
                        <div class="pokemonName">
                            <span><?= $lista[0]['Nombre'] ?></span>
                        </div>
                    <?php else: ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?>" title="<?= $lista[0]['Nombre'] ?>">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                        </div>
                        <div class="pokemonName">
                            <span><?= $lista[0]['Nombre'] ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="typeName">
                        <?php if (isset($lista[0]['Tipo2'])): ?>
                            <span>Tipo: <?= $lista[0]['Tipo1'] ?>/<?= $lista[0]['Tipo2'] ?></span>
                        <?php else: ?>
                            <span>Tipo: <?= $lista[0]['Tipo1'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="meetPlace">
                        <?php if (isset($lista[0]['Enlace_Maps'])): ?>
                            <span>Ubicación: <?= $lista[0]['Ubicacion'] ?> - <a href="<?= $lista[0]['Enlace_Maps'] ?>" target="_blank">¿Cómo llegar?</a></span>
                        <?php else: ?>
                            <span>Ubicación: <?= $lista[0]['Ubicacion'] ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($inicioLista) && isset($finLista)): ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?> - Hora apertura: <?= $inicioLista['hora'] ?> - Hora cierre: <?= $finLista['hora'] ?>)</span>
                        </div>
                    <?php elseif (!isset($inicioLista) && isset($finLista)): ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?> - Hora cierre: <?= $finLista['hora'] ?>)</span>
                        </div>
                    <?php elseif (isset($inicioLista) && !isset($finLista)): ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?> - Hora apertura: <?= $inicioLista['hora'] ?>)</span>
                        </div>
                    <?php else: ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?>)</span>
                        </div>
                    <?php endif; ?>
                    <div class="listHost">
                        <span>Host: <?= $lista[0]['Pogo_Username'] ?></span>
                    </div>
                    <?php if (isset($lista[0]['Tiempo_atmos'])): ?>
                        <div class="currentWeather">
                            <span>Tiempo: </span><img src="../media/raids/Weather_Icon_<?= $weatherImage ?>.webp" alt="<?= $lista[0]['Tiempo_atmos'] ?>" title="<?= $lista[0]['Tiempo_atmos'] ?> - <?= $descTiempo ?>" height="50">
                        </div>
                    <?php endif; ?>
                    <div class="perfectPC">
                        <?php if ($lista[0]['Tipo_Raid'] == "Dinamax" || $lista[0]['Tipo_Raid'] == "Gigamax"): ?>
                            <span>100% = <?= $lista[0]['PC_100_Nivel_20'] ?> PC</span>
                        <?php else: ?>
                            <span>100% = <?= $lista[0]['PC_100_Nivel_20'] ?> PC (<?= $lista[0]['PC_100_Nivel_25'] ?> PC si está potenciado)</span>
                        <?php endif; ?>
                    </div>
                    <div class="baseStats">
                        <span>Estadísticas base: Ataque = <?= $lista[0]['Ataque_base'] ?> / Defensa = <?= $lista[0]['Defensa_base'] ?> / PS = <?= $lista[0]['PS_base'] ?></span>
                    </div>
                    <?php if (isset($lista[0]['Enlace_counters'])): ?>
                        <div class="counterURL">
                            <span><a href=<?= $lista[0]['Enlace_counters'] ?> target="_blank">Counters</a></span>
                        </div>
                    <?php endif; ?>
                    <div class="listParticipants">
                        <span>Apuntados: <?= $lista[0]['numParticipantes'] ?>/<?= $lista[0]['Maximo_participantes'] ?> (<?= $lista[0]['numRemotos'] ?>/<?= $lista[0]['Maximo_remotos_totales'] ?> remotos)</span>
                    </div>
                    <div class="joinButtons">
                        <?php if ($usuarioApuntado && $lista[0]['numRemotos'] < $lista[0]['Maximo_remotos_totales']): ?>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?= $comprobacionApuntado[0]['Pase'] == "Presencial" ? "<input type='submit' value='Ya estás apuntado como presencial' disabled>" : "<input type='submit' value='Me apunto como presencial'>" ?>
                            </form>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Remoto">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?= $comprobacionApuntado[0]['Pase'] == "Remoto" ? "<input type='submit' value='Ya estás apuntado como remoto' disabled>" : "<input type='submit' value='Me apunto como remoto'>" ?>
                            </form>
                        <?php elseif ($usuarioApuntado && $lista[0]['numRemotos'] == $lista[0]['Maximo_remotos_totales']): ?>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?= $comprobacionApuntado[0]['Pase'] == "Presencial" ? "<input type='submit' value='Ya estás apuntado como presencial' disabled>" : "<input type='submit' value='Me apunto como presencial'>" ?>
                            </form>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Remoto">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?= $comprobacionApuntado[0]['Pase'] == "Remoto" ? "<input type='submit' value='Ya estás apuntado como remoto' disabled>" : "<input type='submit' value='Se ha alcanzado el número máximo de remotos' disabled>" ?>
                            </form>
                        <?php elseif (!$usuarioApuntado && ($lista[0]['numParticipantes'] < $lista[0]['Maximo_participantes'] && $lista[0]['numRemotos'] == $lista[0]['Maximo_remotos_totales'])): ?>
                            <form action="addToList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type='submit' value='Me apunto como presencial'>
                            </form>
                            <form>
                                <input type='submit' value='Se ha alcanzado el número máximo de remotos' disabled>
                            </form>
                        <?php elseif (!$usuarioApuntado && $lista[0]['numParticipantes'] == $lista[0]['Maximo_participantes']): ?>
                            <span>La lista está llena</span>
                        <?php else: ?>
                            <form action="addToList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type='submit' value='Me apunto como presencial'>
                            </form>
                            <form action="addToList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Remoto">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type='submit' value='Me apunto como remoto'>
                            </form>
                        <?php endif; ?>
                    </div>
                    <?php if ($usuarioApuntado): ?>
                        <div class="guestManagement">
                            <details>
                                <summary>Añadir o quitar invitados presenciales: </summary>
                                <form action="manageGuests.php" method="POST">
                                    <label>Invitados presenciales: </label>
                                    <input type="number" name="Invitado_presencial" value="<?= $comprobacionApuntado[0]['Invitado_presencial'] ?>" min=0 max=<?= ($lista[0]['Maximo_participantes'] - $lista[0]['numParticipantes']) + $comprobacionApuntado[0]['Invitado_presencial'] ?> required>
                                    <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                    <input type="hidden" name="Tipo_Invitado" value="Presencial">
                                    <input type="submit" value="Confirmar">
                                    <input type="reset" value="Volver al valor actual">
                                </form>
                            </details>
                            <details>
                                <summary>Añadir o quitar invitados remotos: </summary>
                                <form action="manageGuests.php" method="POST">
                                    <label>Invitados remotos: </label>
                                    <?php if ($lista[0]['Maximo_remotos_totales'] > $lista[0]['Maximo_remotos_por_apuntado']): ?>
                                        <?php if ((($lista[0]['Maximo_participantes'] - $lista[0]['numParticipantes']) + $comprobacionApuntado[0]['Invitado_remoto']) < $lista[0]['Maximo_remotos_por_apuntado']): ?>
                                            <input type="number" name="Invitado_remoto" value="<?= $comprobacionApuntado[0]['Invitado_remoto'] ?>" min=0 max=<?= ($lista[0]['Maximo_participantes'] - $lista[0]['numParticipantes']) + $comprobacionApuntado[0]['Invitado_remoto'] ?> required>
                                        <?php else: ?>
                                            <input type="number" name="Invitado_remoto" value="<?= $comprobacionApuntado[0]['Invitado_remoto'] ?>" min=0 max=<?= $lista[0]['Maximo_remotos_por_apuntado'] ?> required>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($lista[0]['Maximo_participantes'] - ($lista[0]['numParticipantes'] - $lista[0]['numRemotos']) < $lista[0]['Maximo_remotos_totales']): ?>
                                            <input type="number" name="Invitado_remoto" value="<?= $comprobacionApuntado[0]['Invitado_remoto'] ?>" min=0 max=<?= ($lista[0]['Maximo_participantes'] - $lista[0]['numParticipantes']) + $comprobacionApuntado[0]['Invitado_remoto'] ?> required>
                                        <?php else: ?>
                                            <input type="number" name="Invitado_remoto" value="<?= $comprobacionApuntado[0]['Invitado_remoto'] ?>" min=0 max=<?= ($lista[0]['Maximo_remotos_totales'] - $lista[0]['numRemotos']) + $comprobacionApuntado[0]['Invitado_remoto'] ?> required>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                    <input type="hidden" name="Tipo_Invitado" value="Remoto">
                                    <input type="submit" value="Confirmar">
                                    <input type="reset" value="Volver al valor actual">
                                </form>
                            </details>
                        </div>
                        <div class="statusList">
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type="hidden" name="Funcion" value="modificarEstado">
                                <?= $comprobacionApuntado[0]['Estado'] == "Voy" ? "<input type='submit' value='🚶 Voy' title='Tu estado ya es \"Voy\"' disabled>" : "<input type='submit' value='🚶 Voy' title='Cambia tu estado a \"Voy\"'>" ?>
                            </form>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Estado" value="Estoy">
                                <input type="hidden" name="Funcion" value="modificarEstado">
                                <?= $comprobacionApuntado[0]['Estado'] == "Estoy" ? "<input type='submit' value='✅ Estoy' title='Tu estado ya es \"Estoy\"' disabled>" : "<input type='submit' value='✅ Estoy' title='Cambia tu estado a \"Estoy\"'>" ?>
                            </form>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Estado" value="Llego tarde">
                                <input type="hidden" name="Funcion" value="modificarEstado">
                                <?= $comprobacionApuntado[0]['Estado'] == "Llego tarde" ? "<input type='submit' value='🐌 Llego tarde' title='Tu estado ya es \"Llego tarde\"' disabled>" : "<input type='submit' value='🐌 Llego tarde' title='Cambia tu estado a \"Llego tarde\"'>" ?>
                            </form>
                            <form action="removeFromList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type='submit' value='✖️ No voy' title='Cambia tu estado a "No voy" (no te cuenta como apuntado, aunque aún salgas en la lista)'>
                            </form>
                        </div>
                    <?php endif; ?>
                    <?php $ordenLista = 1 ?>
                    <div class="listJoined">
                        <?php foreach ($apuntados as $apuntado): ?>
                            <div class="meetTime">
                                <span class="apuntadosLista"><?= $ordenLista ?>) <?= $apuntado['Pase'] == "Remoto" ? "<img class='passIcon' src='../media/raids/Remote_Raid_Pass.webp' height=25 alt='Remoto' title='Remoto'> " : "" ?><?= iconoEstado($apuntado["Estado"]) ?> <?= $apuntado['Pogo_Username'] ?> - Nivel <?= $apuntado["Level"] ?> - <img class="teamLogo" src="../media/website/Logo_Equipo_<?= $apuntado['Team'] ?>_GO.png" height="25" alt="<?= $apuntado['Team'] ?>" title="<?= $apuntado['Team'] ?>"> - Apuntado a las <?= $apuntado['Hora_apuntado'] ?> - Último cambio a las <?= $apuntado['Hora_ultimo_cambio'] ?></span>
                                <?php if ($apuntado["Invitado_presencial"] > 0 && $apuntado['Invitado_remoto'] > 0): ?>
                                    <div><span class="invitadosLista">+ <?= $apuntado['Invitado_presencial'] ?> acompañantes presenciales + <?= $apuntado['Invitado_remoto'] ?> acompañantes remotos</span></div>
                                <?php elseif ($apuntado["Invitado_presencial"] > 0 && $apuntado['Invitado_remoto'] == 0): ?>
                                    <div><span class="invitadosLista">+ <?= $apuntado['Invitado_presencial'] ?> acompañantes presenciales</span></div>
                                <?php elseif ($apuntado["Invitado_presencial"] == 0 && $apuntado['Invitado_remoto'] > 0): ?>
                                    <div><span class="invitadosLista">+ <?= $apuntado['Invitado_remoto'] ?> acompañantes remotos</span></div>
                                <?php endif; ?>
                            </div>
                            <?php $ordenLista = $ordenLista + 1 + $apuntado["Invitado_presencial"] + $apuntado["Invitado_remoto"] ?>
                        <?php endforeach; ?>
                        <?php foreach ($desapuntados as $desapuntado): ?>
                            <div class="meetTime">
                                <span class="desapuntadoLista"><?= $ordenLista ?>) <?= $desapuntado['Pase'] == "Remoto" ? "<img class='passIcon' src='../media/raids/Remote_Raid_Pass.webp' height=25 alt='Remoto' title='Remoto'> " : "" ?><?= iconoEstado($desapuntado["Estado"]) ?> <?= $desapuntado['Pogo_Username'] ?> - Nivel <?= $desapuntado["Level"] ?> - <img class="teamLogo" src="../media/website/Logo_Equipo_<?= $desapuntado['Team'] ?>_GO.png" height="25" alt="<?= $desapuntado['Team'] ?>" title="<?= $desapuntado['Team'] ?>"> - Apuntado a las <?= $desapuntado['Hora_apuntado'] ?> - Último cambio a las <?= $desapuntado['Hora_ultimo_cambio'] ?></span>
                            </div>
                            <?php $ordenLista = $ordenLista + 1 ?>
                        <?php endforeach; ?>
                    </div>
                    <div>
                        <?php if ($_SESSION['usuario'] == $lista[0]['Username'] || $borrarLista): ?>
                            <form action="deleteList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type='submit' value='Borrar lista' title="Borra esta lista (Nota: No se puede deshacer)">
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <p>No se ha podido encontrar la lista con ID = <?= $_GET['id'] ?></p>
            <?php endif; ?>
        </article>
    </section>
    <footer>
        <div>Juan Antonio Gómez Martín - 2026</div>
        <div>©Niantic ©Pokémon/Nintendo/Creatures/GAME FREAK TM, ® y los nombres de los personajes son marcas comerciales de Nintendo.</div>
    </footer>
</body>
<script>
    <?php if (isset($_GET['id']) && $lista[0]['Shiny_activado']): ?>
        const shinyIcon = document.getElementById("shinyIcon");
        const pokemonSprite = document.getElementById("pokemonSprite");
        shinyIcon.addEventListener("click", shinyToggle);

        function shinyToggle() {
            if (shinyIcon.alt == "No variocolor") {
                pokemonSprite.src = "../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>-shiny.png";
                shinyIcon.src = "../media/raids/Shiny.png";
                shinyIcon.alt = "Variocolor";
                shinyIcon.title = "Pulsa para ocultar el variocolor"
                <?php if ($lista[0]['Tipo_Raid'] == "Oscura"): ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?> Oscuro variocolor";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?> Oscuro variocolor";
                <?php elseif ($lista[0]['Tipo_Raid'] == "Dinamax"): ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?> Dinamax variocolor";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?> Dinamax variocolor";
                <?php else: ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?> variocolor";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?> variocolor";
                <?php endif; ?>
            } else {
                pokemonSprite.src = "../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png";
                shinyIcon.src = "../media/raids/Shiny-Off.png";
                shinyIcon.alt = "No variocolor";
                shinyIcon.title = "Pulsa para mostrar el variocolor"
                <?php if ($lista[0]['Tipo_Raid'] == "Oscura"): ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?> Oscuro";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?> Oscuro";
                <?php elseif ($lista[0]['Tipo_Raid'] == "Dinamax"): ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?> Dinamax";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?> Dinamax";
                <?php else: ?>
                    pokemonSprite.alt = "<?= $lista[0]['Nombre'] ?>";
                    pokemonSprite.title = "<?= $lista[0]['Nombre'] ?>";
                <?php endif; ?>
            }
        }
    <?php endif; ?>

    var closeAlert = document.getElementsByClassName("closeAlertBtn");
    var i;
    for (i = 0; i < closeAlert.length; i++) {
        closeAlert[i].onclick = function() {
            var childDiv = this.parentElement;
            div = childDiv.parentElement;
            div.style.opacity = "0";
            setTimeout(function() {
                div.style.display = "none";
            }, 600);
        }
    }
</script>

</html>