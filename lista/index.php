<?php
include 'datos.php';
if (!isset($_SESSION['usuario'])) {
    /* Si no hay una variable de sesión para el usuario, no tiene la sesión iniciada (y no quiero que 
    se pueda acceder a esta página sin iniciar sesión) */
    $_SESSION['warningAlert'] = "¡Tienes que iniciar sesión antes de poder acceder a una lista!";
    /* Redirijo al usuario a la página de inicio de sesión */
    header('Location: ../login');
    /* Y con "exit" hago que se detenga el script, para que no ejecute el 
    resto de funciones */
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
            <?php /* Si hay una sesión de usuario activa, muestro los botones de cerrar sesión, y un saludo (se cambiará a "Mi perfil") */ ?>
            <ul class="navList">
                <li class="navElement"><a href="../logout">Cerrar sesión</a></li>
                <li class="navElement"><span>¡Hola <?= $_SESSION['usuario'] ?>!</span></li>
            </ul>
        <?php else: ?>
            <?php /* Si no hay una sesión de usuario activa, muestro los botones de registro y de inicio de sesión */ ?>
            <ul class="navList">
                <li class="navElement"><a href="../registro">Regístrate</a></li>
                <li class="navElement"><a href="../login">Iniciar sesión</a></li>
            </ul>
        <?php endif; ?>
    </nav>
    <section>
        <article>
            <?php /* Si hay una variable de sesión de una alerta de tipo éxito, la muestro y la desasigno. 
            Las alertas tienen un botón de cerrado (marcado con un símbolo de X codificado como "&times;")
            para que, al ser pulsados, desaparezcan. */ ?>
            <?php if (isset($_SESSION['successAlert'])): ?>
                <div class="alertBox" id="successAlert">
                    <div class="alertSuccess">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['successAlert'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['successAlert']) ?>
            <?php endif; ?>
            <?php /* Si hay una variable de sesión de una alerta de tipo advertencia, la muestro y la desasigno. */ ?>
            <?php if (isset($_SESSION['warningAlert'])): ?>
                <div class="alertBox" id="warningAlert">
                    <div class="alertWarning">
                        <span class="closeAlertBtn">&times;</span>
                        <?= $_SESSION['warningAlert'] ?>
                    </div>
                </div>
                <?php unset($_SESSION['warningAlert']) ?>
            <?php endif; ?>
            <?php /* Si hay una variable de sesión de una alerta de tipo error, la muestro y la desasigno. */ ?>
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
                <?php /* Si la lista obtenida en "datos.php" existe, la muestro */ ?>
                <div class="pokeList">
                    <?php if ($lista[0]['Tipo_Raid'] == "Oscura"): ?>
                        <?php /* Si el tipo de incursión es oscura, muestro el icono de un Pokémon oscuro, y añado "Oscuro" a su nombre */ ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?> Oscuro" title="<?= $lista[0]['Nombre'] ?> Oscuro">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <?php /* Si tiene su variocolor activado, muestro el icono de variocolor, con el atributo "alt" muestro 
                                que el Pokémon no es variocolor, y con el atributo "title" indico que, si se pulsa sobre la imagen, se 
                                mostrará el variocolor del Pokémon */ ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                            <img id="shadowIcon" src="../media/raids/Shadow.webp" height="60" alt="Oscuro" title="Oscuro">
                        </div>
                        <div class="pokemonName">
                            <span><?= $lista[0]['Nombre'] ?> Oscuro</span>
                        </div>
                    <?php elseif ($lista[0]['Tipo_Raid'] == "Dinamax"): ?>
                        <?php /* Si el tipo de incursión es dinamax, muestro el icono de un Pokémon dinamax, y añado "Dinamax" a su nombre */ ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?> Dinamax" title="<?= $lista[0]['Nombre'] ?> Dinamax">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <?php /* Si tiene su variocolor activado, muestro el icono de variocolor, con el atributo "alt" muestro 
                                que el Pokémon no es variocolor, y con el atributo "title" indico que, si se pulsa sobre la imagen, se 
                                mostrará el variocolor del Pokémon */ ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                            <img id="maxIcon" src="../media/raids/Dynamax.webp" height="90" alt="Dinamax" title="Dinamax">
                        </div>
                        <div class="pokemonName">
                            <span><?= $lista[0]['Nombre'] ?> Dinamax</span>
                        </div>
                    <?php elseif ($lista[0]['Tipo_Raid'] == "Gigamax"): ?>
                        <?php /* Si el tipo de incursión es gigamax, muestro el icono de un Pokémon gigamax, 
                                pero no hace falta añadir "Gigamax" a su nombre, ya que viene así en la base de datos */ ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?>" title="<?= $lista[0]['Nombre'] ?>">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <?php /* Si tiene su variocolor activado, muestro el icono de variocolor, con el atributo "alt" muestro 
                                que el Pokémon no es variocolor, y con el atributo "title" indico que, si se pulsa sobre la imagen, se 
                                mostrará el variocolor del Pokémon */ ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                            <img id="maxIcon" src="../media/raids/Gigantamax.webp" height="90" alt="Gigamax" title="Gigamax">
                        </div>
                        <div class="pokemonName">
                            <span><?= $lista[0]['Nombre'] ?></span>
                        </div>
                    <?php else: ?>
                        <?php /* Si no es ninguno de los tipos anteriores, no añado ningún icono */ ?>
                        <div class="pokemonIcon">
                            <img id="pokemonSprite" src="../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png" height="150" alt="<?= $lista[0]['Nombre'] ?>" title="<?= $lista[0]['Nombre'] ?>">
                            <?php if ($lista[0]['Shiny_activado']): ?>
                                <?php /* Si tiene su variocolor activado, muestro el icono de variocolor, con el atributo "alt" muestro 
                                que el Pokémon no es variocolor, y con el atributo "title" indico que, si se pulsa sobre la imagen, se 
                                mostrará el variocolor del Pokémon */ ?>
                                <img id="shinyIcon" src="../media/raids/Shiny-Off.png" height="60" alt="No variocolor" title="Pulsa para mostrar el variocolor">
                            <?php endif; ?>
                        </div>
                        <div class="pokemonName">
                            <?php /* Aquí muestro el nombre del Pokémon */ ?>
                            <span><?= $lista[0]['Nombre'] ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="typeName">
                        <?php if (isset($lista[0]['Tipo2'])): ?>
                            <?php /* Si el Pokémon tiene dos tipos, los muestro juntos */ ?>
                            <span>Tipo: <?= $lista[0]['Tipo1'] ?>/<?= $lista[0]['Tipo2'] ?></span>
                        <?php else: ?>
                            <?php /* Si no, solo muestro su único tipo */ ?>
                            <span>Tipo: <?= $lista[0]['Tipo1'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="meetPlace">
                        <?php if (isset($lista[0]['Enlace_Maps'])): ?>
                            <?php /* Si está definido el enlace a Google Maps, lo muestro junto a la ubicación como un enlace que se 
                            abrirá en una nueva pestaña */ ?>
                            <span>Ubicación: <?= $lista[0]['Ubicacion'] ?> - <a href="<?= $lista[0]['Enlace_Maps'] ?>" target="_blank">¿Cómo llegar?</a></span>
                        <?php else: ?>
                            <?php /* Si no está definido, solo muestro la ubicación */ ?>
                            <span>Ubicación: <?= $lista[0]['Ubicacion'] ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($inicioLista) && isset($finLista)): ?>
                        <?php /* Si están definidas las horas de inicio y fin de la lista, muestro las horas de quedada, creación, 
                        apertura y cierre */ ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?> - Hora apertura: <?= $inicioLista['hora'] ?> - Hora cierre: <?= $finLista['hora'] ?>)</span>
                        </div>
                    <?php elseif (!isset($inicioLista) && isset($finLista)): ?>
                        <?php /* Si no está definida la hora de inicio, pero si la de fin, muestro las horas de quedada, creación 
                        y cierre */ ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?> - Hora cierre: <?= $finLista['hora'] ?>)</span>
                        </div>
                    <?php elseif (isset($inicioLista) && !isset($finLista)): ?>
                        <?php /* Si está definida la hora de inicio, pero no la de fin, muestro lsa horas de quedada, creación y 
                        apertura */ ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?> - Hora apertura: <?= $inicioLista['hora'] ?>)</span>
                        </div>
                    <?php else: ?>
                        <?php /* Si no están definidas ni la hora de inicio ni la de fin, solo muestro las horas de quedada y creación */ ?>
                        <div class="meetTime">
                            <span>Quedada: <?= $quedadaLista['hora'] ?> (Lista creada a las <?= $creacionLista['hora'] ?>)</span>
                        </div>
                    <?php endif; ?>
                    <div class="listHost">
                        <?php /* Aquí muestro el nombre de usuario de Pokémon Go del creador de la lista */ ?>
                        <span>Host: <?= $lista[0]['Pogo_Username'] ?></span>
                    </div>
                    <?php if (isset($lista[0]['Tiempo_atmos'])): ?>
                        <?php /* Si está definido el tiempo atmosférico, lo muestro y, dependiendo de su valor, muestro el icono que le 
                        corresponde y, en el atributo title, muestro los tipos que potencia ese tiempo atmostférico */ ?>
                        <div class="currentWeather">
                            <span>Tiempo: </span><img src="../media/raids/Weather_Icon_<?= $weatherImage ?>.webp" alt="<?= $lista[0]['Tiempo_atmos'] ?>" title="<?= $lista[0]['Tiempo_atmos'] ?> - <?= $descTiempo ?>" height="50">
                        </div>
                    <?php endif; ?>
                    <div class="perfectPC">
                        <?php /* Aquí indico el PC del Pokémon cuyos IVs son perfectos, ya que todos los de incursiones tienen el mismo nivel 
                        (20, y 25 si está potenciado) */ ?>
                        <?php if ($lista[0]['Tipo_Raid'] == "Dinamax" || $lista[0]['Tipo_Raid'] == "Gigamax"): ?>
                            <?php /* Si el tipo de raid es dinamax o gigamax, el Pokémon no puede estar potenciado */ ?>
                            <span>100% = <?= $lista[0]['PC_100_Nivel_20'] ?> PC</span>
                        <?php else: ?>
                            <?php /* Si no, puede estar potenciado */ ?>
                            <span>100% = <?= $lista[0]['PC_100_Nivel_20'] ?> PC (<?= $lista[0]['PC_100_Nivel_25'] ?> PC si está potenciado)</span>
                        <?php endif; ?>
                    </div>
                    <div class="baseStats">
                        <?php /* Aquí indico las estadísticas base del Pokémon */ ?>
                        <span>Estadísticas base: Ataque = <?= $lista[0]['Ataque_base'] ?> / Defensa = <?= $lista[0]['Defensa_base'] ?> / PS = <?= $lista[0]['PS_base'] ?></span>
                    </div>
                    <?php if (isset($lista[0]['Enlace_counters'])): ?>
                        <?php /* Si está definido el enlace a los counters del Pokémon, hago que se muestre como un enlace que abrirá la página 
                        donde consultar los mejores Pokémon que usar contra ese jefe de incursión en una nueva pestaña */ ?>
                        <div class="counterURL">
                            <span><a href=<?= $lista[0]['Enlace_counters'] ?> target="_blank">Counters</a></span>
                        </div>
                    <?php endif; ?>
                    <div class="listParticipants">
                        <?php /* Aquí muestro la cantidad de participantes que hay apuntados, el máximo de participantes que se puedenn apuntar, 
                        la cantidad de remotos apuntados y el máximo de remotos que se pueden apuntar */ ?>
                        <span>Apuntados: <?= $lista[0]['numParticipantes'] ?>/<?= $lista[0]['Maximo_participantes'] ?> (<?= $lista[0]['numRemotos'] ?>/<?= $lista[0]['Maximo_remotos_totales'] ?> remotos)</span>
                    </div>
                    <div class="joinButtons">
                        <?php if ($usuarioApuntado && $lista[0]['numRemotos'] < $lista[0]['Maximo_remotos_totales']): ?>
                            <?php /* Si el usuario está apuntado y el número de remotos es menor que el máximo de remotos: */ ?> 
                            <?php /* Muestro el formulario para cambiar el pase del usuario a "Presencial" */ ?>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?php /* Si ya está apuntado como presencial, desactivo el botón de enviar */ ?>
                                <?= $comprobacionApuntado[0]['Pase'] == "Presencial" ? "<input type='submit' value='Ya estás apuntado como presencial' disabled>" : "<input type='submit' value='Me apunto como presencial'>" ?>
                            </form>
                             <?php /* Muestro el formulario para cambiar el pase del usuario a "Remoto" */ ?>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Remoto">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?php /* Si ya está apuntado como remoto, desactivo el botón de enviar */ ?>
                                <?= $comprobacionApuntado[0]['Pase'] == "Remoto" ? "<input type='submit' value='Ya estás apuntado como remoto' disabled>" : "<input type='submit' value='Me apunto como remoto'>" ?>
                            </form>
                        <?php elseif ($usuarioApuntado && $lista[0]['numRemotos'] == $lista[0]['Maximo_remotos_totales']): ?>
                            <?php /* Si el usuario está apuntado y el número de remotos es igual que el máximo de remotos: */ ?> 
                            <?php /* Muestro el formulario para cambiar el pase del usuario a "Presencial" */ ?>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?php /* Si ya está apuntado como presencial, desactivo el botón de enviar */ ?>
                                <?= $comprobacionApuntado[0]['Pase'] == "Presencial" ? "<input type='submit' value='Ya estás apuntado como presencial' disabled>" : "<input type='submit' value='Me apunto como presencial'>" ?>
                            </form>
                            <?php /* Muestro el formulario para cambiar el pase del usuario a "Remoto", pero con el botón 
                            de enviar desactivado */ ?>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Remoto">
                                <input type="hidden" name="Funcion" value="modificarPase">
                                <?php /* Si el usuario está apuntado como remoto, lo muestro. En caso contrario, indico que 
                                se ha alcanzado el número máximo de remotos */ ?>
                                <?= $comprobacionApuntado[0]['Pase'] == "Remoto" ? "<input type='submit' value='Ya estás apuntado como remoto' disabled>" : "<input type='submit' value='Se ha alcanzado el número máximo de remotos' disabled>" ?>
                            </form>
                        <?php elseif (!$usuarioApuntado && ($lista[0]['numParticipantes'] < $lista[0]['Maximo_participantes'] && $lista[0]['numRemotos'] == $lista[0]['Maximo_remotos_totales'])): ?>
                            <?php /* Si el usuario no está apuntado, el número de participantes es menor que el máximo, y el 
                            número de remotos es igual que el máximo de remotos: */ ?> 
                            <?php /* Muestro el formulario para apuntar al usuario como "Presencial" */ ?>
                            <form action="addToList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type='submit' value='Me apunto como presencial'>
                            </form>
                            <?php /* Con un botón de envío desactivado, indico que se ha alcanzado el máximo de remotos */ ?>
                            <form>
                                <input type='submit' value='Se ha alcanzado el número máximo de remotos' disabled>
                            </form>
                        <?php elseif (!$usuarioApuntado && $lista[0]['numParticipantes'] == $lista[0]['Maximo_participantes']): ?>
                            <?php /* Si el usuario no está apuntado, y el número de participantes es igual que el máximo de 
                            participantes, muestro que la lista está llena */ ?>
                            <span>La lista está llena</span>
                        <?php else: ?>
                            <?php /* Si el usuario no está apuntado: */ ?>
                            <?php /* Muestro el formulario para apuntar al usuario como "Presencial" */ ?>
                            <form action="addToList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Presencial">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type='submit' value='Me apunto como presencial'>
                            </form>
                            <?php /* Muestro el formulario para apuntar al usuario como "Remoto" */ ?>
                            <form action="addToList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Pase" value="Remoto">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type='submit' value='Me apunto como remoto'>
                            </form>
                        <?php endif; ?>
                    </div>
                    <?php if ($usuarioApuntado): ?>
                        <?php /* Si el usuario está apuntado, muestro los formularios para modificar el número de invitados que 
                        le van a acompañar */ ?>
                        <div class="guestManagement">
                            <details>
                                <summary>Añadir o quitar invitados presenciales: </summary>
                                <form action="manageGuests.php" method="POST">
                                    <label>Invitados presenciales: </label>
                                    <?php /* Pongo como valor de "Invitado_presencial" el valor actual que tiene el usuario, y como máximo los 
                                    invitados que puede añadir en total sin contar con los que ya ha invitado */ ?>
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
                                        <?php /* Si el número máximo total de remotos es mayor que el número de remotos que cada usuario apuntado 
                                        puede invitar: */ ?>
                                        <?php if ((($lista[0]['Maximo_participantes'] - $lista[0]['numParticipantes']) + $comprobacionApuntado[0]['Invitado_remoto']) < $lista[0]['Maximo_remotos_por_apuntado']): ?>
                                            <?php /* Si el número de participantes sin contar los invitados remotos que ha añadido el usuario es 
                                            menor que el máximo de remotos que un usuario puede añadir, pongo como valor de "Invitado_remoto" el 
                                            valor actual que tiene el usuario, y como máximo los invitados que puede añadir en total sin contar 
                                            con los que ya ha invitado */ ?>
                                            <input type="number" name="Invitado_remoto" value="<?= $comprobacionApuntado[0]['Invitado_remoto'] ?>" min=0 max=<?= ($lista[0]['Maximo_participantes'] - $lista[0]['numParticipantes']) + $comprobacionApuntado[0]['Invitado_remoto'] ?> required>
                                        <?php else: ?>
                                            <?php /* Si no se cumple esa condición, pongo como valor de "Invitado_remoto" el valor actual que tiene 
                                            el usuario, y como máximo el número máximo de remotos que puede invitar un usuario apuntado */ ?>
                                            <input type="number" name="Invitado_remoto" value="<?= $comprobacionApuntado[0]['Invitado_remoto'] ?>" min=0 max=<?= $lista[0]['Maximo_remotos_por_apuntado'] ?> required>
                                        <?php endif; ?>
                                    <?php else: ?>
                                       <?php /* Si el número máximo total de remotos es igual que el número de remotos que cada usuario apuntado 
                                        puede invitar: */ ?>
                                        <?php if ($lista[0]['Maximo_participantes'] - ($lista[0]['numParticipantes'] - $lista[0]['numRemotos']) < $lista[0]['Maximo_remotos_totales']): ?>
                                            <?php /* Si, al restar el máximo de participantes, el número de participantes totales y el número de 
                                            participantes remotos es menor que el máximo de remotos, pongo como valor de "Invitado_remoto" el 
                                            valor actual que tiene el usuario, y como máximo los invitados que puede añadir en total sin contar 
                                            con los que ya ha invitado de forma que no supere el máximo de participantes totales */ ?>
                                            <input type="number" name="Invitado_remoto" value="<?= $comprobacionApuntado[0]['Invitado_remoto'] ?>" min=0 max=<?= ($lista[0]['Maximo_participantes'] - $lista[0]['numParticipantes']) + $comprobacionApuntado[0]['Invitado_remoto'] ?> required>
                                        <?php else: ?>
                                            <?php /* Si no se cumple esa condición, ongo como valor de "Invitado_remoto" el valor actual que tiene 
                                            el usuario, y como máximo los invitados que puede añadir en total sin contar con los que ya ha invitado 
                                            de forma que no supere el máximo de remotos totales*/ ?>
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
                        <?php /* Aquí muestro la lista de estados en los que puede estar el usuario */ ?>
                        <div class="statusList">
                            <?php /* Aquí muestro el formulario para cambiar el estado del usuario a "Voy" */ ?>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Estado" value="Voy">
                                <input type="hidden" name="Funcion" value="modificarEstado">
                                <?php /* Si su estado es "Voy", desactivo el botón de enviar, indicando que ese es su estado actual */ ?>
                                <?= $comprobacionApuntado[0]['Estado'] == "Voy" ? "<input type='submit' value='🚶 Voy' title='Tu estado ya es \"Voy\"' disabled>" : "<input type='submit' value='🚶 Voy' title='Cambia tu estado a \"Voy\"'>" ?>
                            </form>
                            <?php /* Aquí muestro el formulario para cambiar el estado del usuario a "Estoy" */ ?>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Estado" value="Estoy">
                                <input type="hidden" name="Funcion" value="modificarEstado">
                                <?php /* Si su estado es "Estoy", desactivo el botón de enviar, indicando que ese es su estado actual */ ?>
                                <?= $comprobacionApuntado[0]['Estado'] == "Estoy" ? "<input type='submit' value='✅ Estoy' title='Tu estado ya es \"Estoy\"' disabled>" : "<input type='submit' value='✅ Estoy' title='Cambia tu estado a \"Estoy\"'>" ?>
                            </form>
                            <?php /* Aquí muestro el formulario para cambiar el estado del usuario a "Llego tarde" */ ?>
                            <form action="editListStatus.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type="hidden" name="Estado" value="Llego tarde">
                                <input type="hidden" name="Funcion" value="modificarEstado">
                                <?php /* Si su estado es "Llego tarde", desactivo el botón de enviar, indicando que ese es su estado actual */ ?>
                                <?= $comprobacionApuntado[0]['Estado'] == "Llego tarde" ? "<input type='submit' value='🐌 Llego tarde' title='Tu estado ya es \"Llego tarde\"' disabled>" : "<input type='submit' value='🐌 Llego tarde' title='Cambia tu estado a \"Llego tarde\"'>" ?>
                            </form>
                            <?php /* Aquí muestro el formulario para desapuntar al usuario (cambiando su estado a "No voy") */ ?>
                            <form action="removeFromList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type='submit' value='✖️ No voy' title='Cambia tu estado a "No voy" (no te cuenta como apuntado, aunque aún salgas en la lista)'>
                            </form>
                        </div>
                    <?php endif; ?>
                    <?php /* Inicializó una variable para el orden de los usuarios apuntados a la lista */ ?>
                    <?php $ordenLista = 1 ?>
                    <div class="listJoined">
                        <?php /* Creo un bucle for each para mostrar todos los usuarios apuntados a la lista, junto con sus detalles */ ?>
                        <?php foreach ($apuntados as $apuntado): ?>
                            <div class="meetTime">
                                <span class="apuntadosLista"><?= $ordenLista ?>) <?= $apuntado['Pase'] == "Remoto" ? "<img class='passIcon' src='../media/raids/Remote_Raid_Pass.webp' height=25 alt='Remoto' title='Remoto'> " : "" ?><?= iconoEstado($apuntado["Estado"]) ?> <?= $apuntado['Pogo_Username'] ?> - Nivel <?= $apuntado["Level"] ?> - <img class="teamLogo" src="../media/website/Logo_Equipo_<?= $apuntado['Team'] ?>_GO.png" height="25" alt="<?= $apuntado['Team'] ?>" title="<?= $apuntado['Team'] ?>"> - Apuntado a las <?= $apuntado['Hora_apuntado'] ?> - Último cambio a las <?= $apuntado['Hora_ultimo_cambio'] ?></span>
                                <?php if ($apuntado["Invitado_presencial"] > 0 && $apuntado['Invitado_remoto'] > 0): ?>
                                    <?php /* Si el usuario trae a más de 1 invitado presencial y más de 1 invitado remoto, muestro la cantidad 
                                    de invitados presenciales que trae y la cantidad de invitados remotos que trae */ ?>
                                    <div><span class="invitadosLista">+ <?= $apuntado['Invitado_presencial'] ?> acompañantes presenciales + <?= $apuntado['Invitado_remoto'] ?> acompañantes remotos</span></div>
                                <?php elseif ($apuntado["Invitado_presencial"] > 0 && $apuntado['Invitado_remoto'] == 0): ?>
                                    <?php /* Si solo trae invitados presenciales, muestro la cantidad de invitados presenciales que trae */ ?>
                                    <div><span class="invitadosLista">+ <?= $apuntado['Invitado_presencial'] ?> acompañantes presenciales</span></div>
                                <?php elseif ($apuntado["Invitado_presencial"] == 0 && $apuntado['Invitado_remoto'] > 0): ?>
                                    <?php /* Si solo trae invitados remotos, muestro la cantidad de invitados remotos que trae */ ?>
                                    <div><span class="invitadosLista">+ <?= $apuntado['Invitado_remoto'] ?> acompañantes remotos</span></div>
                                <?php endif; ?>
                            </div>
                            <?php /* Por cada usuario apuntado a la lista, sumo + 1 y el número de invitados (presenciales y remotos) que trae a la variable "ordenLista" */ ?>
                            <?php $ordenLista = $ordenLista + 1 + $apuntado["Invitado_presencial"] + $apuntado["Invitado_remoto"] ?>
                        <?php endforeach; ?>
                        <?php /* Creo un bucle for each para mostrar todos los usuarios que se han desapuntado de la lista, junto con sus detalles */ ?>
                        <?php foreach ($desapuntados as $desapuntado): ?>
                            <div class="meetTime">
                                <span class="desapuntadoLista"><?= $ordenLista ?>) <?= $desapuntado['Pase'] == "Remoto" ? "<img class='passIcon' src='../media/raids/Remote_Raid_Pass.webp' height=25 alt='Remoto' title='Remoto'> " : "" ?><?= iconoEstado($desapuntado["Estado"]) ?> <?= $desapuntado['Pogo_Username'] ?> - Nivel <?= $desapuntado["Level"] ?> - <img class="teamLogo" src="../media/website/Logo_Equipo_<?= $desapuntado['Team'] ?>_GO.png" height="25" alt="<?= $desapuntado['Team'] ?>" title="<?= $desapuntado['Team'] ?>"> - Apuntado a las <?= $desapuntado['Hora_apuntado'] ?> - Último cambio a las <?= $desapuntado['Hora_ultimo_cambio'] ?></span>
                            </div>
                            <?php /* Por cada usuario desapuntado de la lista, sumo +1 a la variable "ordenLista" */ ?>
                            <?php $ordenLista = $ordenLista + 1 ?>
                        <?php endforeach; ?>
                    </div>
                    <div>
                        <?php if ($_SESSION['usuario'] == $lista[0]['Username'] || $borrarLista): ?>
                            <?php /* Si el usuario es el creador de la lista o tiene permisos para borrar listas ajenas, muestro el 
                            formulario para borrar la lista, indicando con el atributo title que no se puede deshacer esa acción */ ?>
                            <form action="deleteList.php" method="POST">
                                <input type="hidden" name="ID_Lista" value="<?= $_GET['id'] ?>">
                                <input type='submit' value='Borrar lista' title="Borra esta lista (Nota: No se puede deshacer)">
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php /* Si no existe esa lista, muestro que no se ha podido encontrar */ ?>
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
    <?php if ((!empty($lista)) && $lista[0]['Shiny_activado']): ?>
        /* Si la lista existe y el variocolor del Pokémon jefe de incursión está activado, creo una variable 
        para seleccionar el elemento cuya ID es "shinyIcon" (el icono de varicolor) */
        const shinyIcon = document.getElementById("shinyIcon");
        /* Creo una variable para seleccionar el elemento cuya ID es "pokemonSprite" (la imagen del Pokémon) */
        const pokemonSprite = document.getElementById("pokemonSprite");
        /* Añado un escuchador de evento para que, si se pulsa sobre el elemento de ID "shinyIcon", 
        se ejecute la función shinyToggle */
        shinyIcon.addEventListener("click", shinyToggle);
        /* Creo la función shinyToggle, que cambiará la imagen del Pokémon a su versión variocolor */
        function shinyToggle() {
            if (shinyIcon.alt == "No variocolor") {
                /* Si el atributo alt del elemento con ID "shinyIcon" es "No variocolor", añado "-shiny" al 
                nombre de la imagen indicada en el atributo src, mostrando la imagen del Pokémon  variocolor */
                pokemonSprite.src = "../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>-shiny.png";
                /* Quito "-off" al nombre de la imagen indicada en el atributo src, mostrando el icono de variocolor
                con el color que tiene en el juego */
                shinyIcon.src = "../media/raids/Shiny.png";
                /* Cambio el valor del atributo alt del elemento con ID "shinyIcon" */
                shinyIcon.alt = "Variocolor";
                /* Cambio el valor del atributo title del elemento con ID "shinyIcon" */
                shinyIcon.title = "Pulsa para ocultar el variocolor"
                /* Añado "variocolor" al final del valor de los atributos alt y title del elemento cuyo ID es
                "pokemonSprite" */
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
                /* Si el atributo alt del elemento con ID "shinyIcon" es "Variocolor", quito "-shiny" al 
                nombre de la imagen indicada en el atributo src, mostrando la imagen del Pokémon no variocolor 
                (si se cumple esta condición, el nombre del fichero del atributo src tenía "-shiny" antes de la 
                extensión) */
                pokemonSprite.src = "../media/pokemon/<?= $lista[0]['ID_Pokemon'] ?>.png";
                /* Añado "-off" al nombre de la imagen indicada en el atributo src, mostrando el icono de variocolor
                con color negro, como si fuese sombreado (si se cumple esta condición, el nombre del fichero del 
                atributo src no tenía "-off" antes de la extensión) */
                shinyIcon.src = "../media/raids/Shiny-Off.png";
                /* Cambio el valor del atributo alt del elemento con ID "shinyIcon" */
                shinyIcon.alt = "No variocolor";
                /* Cambio el valor del atributo title del elemento con ID "shinyIcon" */
                /* Quito "variocolor" al final del valor de los atributos alt y title del elemento cuyo ID es
                "pokemonSprite" (si se cumple esta condición, esos atributos tenían "varicolor" al final) */
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
    /* Obtengo los elementos que tengan como clase "closeAlertBtn" */
    var closeAlert = document.getElementsByClassName("closeAlertBtn");
    /* Inicio una variable para un bucle for */
    var i;
    /* Abro un bucle for desde 0 hasta el número de elementos con 
    clase "closeAlertBtn" */
    for (i = 0; i < closeAlert.length; i++) {
        /* Añado una función para cada elemento de forma que, si se 
        pulsa en ese elemento, seleccione su abuelo (el padre de su
        padre), lo oculte y le de como valor de opacidad 0 */
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