-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 25-05-2026 a las 03:06:23
-- Versión del servidor: 12.2.2-MariaDB-ubu2404
-- Versión de PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pogo_velez`
--
CREATE DATABASE IF NOT EXISTS `pogo_velez` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;
USE `pogo_velez`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apuntados_lista`
--

CREATE TABLE `apuntados_lista` (
  `ID_Lista` int(11) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Pase` enum('Presencial','Remoto') NOT NULL,
  `Estado` enum('Voy','Estoy','Llego tarde','No voy') NOT NULL DEFAULT 'Voy',
  `Invitado_presencial` int(11) NOT NULL DEFAULT 0,
  `Invitado_remoto` int(11) NOT NULL DEFAULT 0,
  `Hora_apuntado` datetime NOT NULL,
  `Hora_ultimo_cambio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `apuntados_lista`
--

INSERT INTO `apuntados_lista` (`ID_Lista`, `Username`, `Pase`, `Estado`, `Invitado_presencial`, `Invitado_remoto`, `Hora_apuntado`, `Hora_ultimo_cambio`) VALUES
(3, '08Juan80', 'Presencial', 'Voy', 2, 5, '2026-04-29 15:23:49', '2026-04-29 15:23:49'),
(3, 'jgommar464', 'Remoto', 'Voy', 3, 0, '2026-04-30 13:20:00', '2026-04-30 13:20:00'),
(5, '08Juan80', 'Presencial', 'Estoy', 0, 0, '2026-04-30 11:01:10', '2026-05-08 12:29:25'),
(5, 'jgommar464', 'Presencial', 'No voy', 0, 0, '2026-04-30 11:02:10', '2026-04-30 11:06:10'),
(6, '08Juan80', 'Presencial', 'Voy', 0, 0, '2026-04-30 11:39:09', '2026-05-15 17:31:16'),
(7, '08Juan80', 'Presencial', 'Estoy', 0, 5, '2026-04-30 15:19:44', '2026-05-15 13:30:37'),
(7, 'jgommar464', 'Presencial', 'Voy', 12, 1, '2026-05-04 18:00:48', '2026-05-05 10:13:24'),
(8, '08Juan80', 'Presencial', 'Voy', 0, 7, '2026-05-08 13:42:23', '2026-05-15 17:30:34'),
(8, 'jgommar464', 'Presencial', 'Voy', 0, 20, '2026-05-05 17:36:25', '2026-05-05 17:36:25'),
(9, '08Juan80', 'Presencial', 'Llego tarde', 0, 0, '2026-05-21 16:36:26', '2026-05-21 17:40:13'),
(9, 'jgommar464', 'Remoto', 'Voy', 0, 0, '2026-05-05 17:41:32', '2026-05-08 11:59:50'),
(10, '08Juan80', 'Presencial', 'Voy', 0, 0, '2026-05-15 12:59:01', '2026-05-15 12:59:01'),
(10, 'jgommar464', 'Remoto', 'Voy', 0, 9, '2026-05-15 13:02:57', '2026-05-15 13:03:43'),
(11, '08Juan80', 'Presencial', 'Voy', 0, 0, '2026-05-25 03:43:58', '2026-05-25 03:43:58');

--
-- Disparadores `apuntados_lista`
--
DELIMITER $$
CREATE TRIGGER `borrado_apuntados_lista` BEFORE DELETE ON `apuntados_lista` FOR EACH ROW BEGIN
	INSERT INTO apuntados_lista_borrada
    VALUES(old.ID_Lista, old.Username, old.Pase, old.Estado, old.Invitado_presencial, old.Invitado_remoto, old.Hora_apuntado, old.Hora_ultimo_cambio);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apuntados_lista_borrada`
--

CREATE TABLE `apuntados_lista_borrada` (
  `ID_Lista_Borrada` int(11) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Pase` enum('Presencial','Remoto') NOT NULL,
  `Estado` enum('Voy','Estoy','Llego tarde','No voy') NOT NULL DEFAULT 'Voy',
  `Invitado_presencial` int(11) NOT NULL DEFAULT 0,
  `Invitado_remoto` int(11) NOT NULL DEFAULT 0,
  `Hora_apuntado` datetime NOT NULL,
  `Hora_ultimo_cambio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `apuntados_lista_borrada`
--

INSERT INTO `apuntados_lista_borrada` (`ID_Lista_Borrada`, `Username`, `Pase`, `Estado`, `Invitado_presencial`, `Invitado_remoto`, `Hora_apuntado`, `Hora_ultimo_cambio`) VALUES
(4, '08Juan80', 'Remoto', 'Voy', 2, 0, '2026-04-29 15:42:06', '2026-04-29 15:42:06'),
(4, 'jgommar464', 'Presencial', 'Voy', 0, 0, '2026-05-05 10:56:54', '2026-05-05 10:56:54'),
(9, '08Juan80', 'Presencial', 'Voy', 0, 0, '2026-05-21 16:26:12', '2026-05-21 16:35:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incursiones`
--

CREATE TABLE `incursiones` (
  `ID_Raid` int(11) NOT NULL,
  `ID_Pokemon` varchar(8) DEFAULT NULL,
  `Tipo_Raid` enum('Normal','Mega','Super Mega','Oscura','Dinamax','Gigamax','Primigenia','Evento','Elite') NOT NULL,
  `Dificultad` int(11) NOT NULL,
  `Eclosionado` tinyint(1) DEFAULT 1,
  `Activo` tinyint(1) NOT NULL,
  `Enlace_counters` varchar(100) DEFAULT NULL,
  `Maximo_participantes` int(11) NOT NULL,
  `Maximo_remotos_totales` int(11) NOT NULL,
  `Maximo_remotos_por_apuntado` int(11) NOT NULL,
  `Shiny_activado` tinyint(1) NOT NULL
) ;

--
-- Volcado de datos para la tabla `incursiones`
--

INSERT INTO `incursiones` (`ID_Raid`, `ID_Pokemon`, `Tipo_Raid`, `Dificultad`, `Eclosionado`, `Activo`, `Enlace_counters`, `Maximo_participantes`, `Maximo_remotos_totales`, `Maximo_remotos_por_apuntado`, `Shiny_activado`) VALUES
(1, '0380-0', 'Normal', 5, 1, 1, 'https://www.pokebattler.com/raids/latias', 20, 10, 10, 1),
(2, '0380-1', 'Mega', 6, 1, 1, 'https://www.pokebattler.com/raids/latias_mega', 20, 10, 10, 1),
(3, '0380-0', 'Dinamax', 5, NULL, 1, NULL, 4, 4, 4, 1),
(4, '0381-0', 'Normal', 5, 1, 1, 'https://www.pokebattler.com/raids/latios', 20, 10, 10, 1),
(5, '0381-1', 'Mega', 6, 1, 1, 'https://www.pokebattler.com/raids/latios_mega', 20, 10, 10, 1),
(6, '0381-0', 'Dinamax', 5, NULL, 1, NULL, 4, 4, 4, 1),
(7, '0380-0', 'Oscura', 5, 1, 1, 'https://www.pokebattler.com/raids/latias_shadow_form', 20, 10, 10, 1),
(8, '0381-0', 'Oscura', 5, 1, 1, 'https://www.pokebattler.com/raids/latios_shadow_form', 20, 10, 10, 1),
(9, '0382-1', 'Primigenia', 6, 1, 1, 'https://www.pokebattler.com/raids/kyogre_primal', 20, 10, 10, 1),
(10, '0383-1', 'Primigenia', 6, 1, 1, 'https://www.pokebattler.com/raids/groudon_primal', 20, 10, 10, 1),
(11, '0384-1', 'Mega', 6, 1, 1, 'https://www.pokebattler.com/raids/rayquaza_mega', 20, 10, 10, 1),
(12, '0003-3', 'Gigamax', 6, 1, 1, NULL, 100, 100, 20, 1),
(13, '0006-3', 'Gigamax', 6, 1, 1, NULL, 100, 100, 20, 1),
(14, '0009-2', 'Gigamax', 6, 1, 1, NULL, 100, 100, 20, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listas`
--

CREATE TABLE `listas` (
  `ID_Lista` int(11) NOT NULL,
  `ID_Raid` int(11) NOT NULL,
  `Creado_por` varchar(20) NOT NULL,
  `Ubicacion` varchar(30) NOT NULL,
  `Enlace_Maps` varchar(50) DEFAULT NULL,
  `Hora_creacion` datetime NOT NULL,
  `Hora_quedada` datetime NOT NULL,
  `Hora_inicio` datetime DEFAULT NULL,
  `Hora_fin` datetime DEFAULT NULL,
  `Tiempo_atmos` enum('Soleado','Despejado','Parcialmente nublado (día)','Parcialmente nublado (noche)','Nublado','Lluvia','Viento','Niebla','Nieve','Extremo') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `listas`
--

INSERT INTO `listas` (`ID_Lista`, `ID_Raid`, `Creado_por`, `Ubicacion`, `Enlace_Maps`, `Hora_creacion`, `Hora_quedada`, `Hora_inicio`, `Hora_fin`, `Tiempo_atmos`) VALUES
(3, 4, '08Juan80', 'Caballo Pegaso', NULL, '2026-04-29 15:23:49', '2026-04-30 12:28:00', NULL, NULL, 'Soleado'),
(5, 3, '08Juan80', 'Orange', NULL, '2026-04-30 11:01:10', '2026-05-21 19:15:00', NULL, NULL, 'Soleado'),
(6, 8, '08Juan80', 'Cruz de Hierro', NULL, '2026-04-30 11:39:09', '2026-05-21 18:45:00', NULL, NULL, 'Viento'),
(7, 9, '08Juan80', 'Cruz de Hierro', 'https://maps.app.goo.gl/CK166tZuZiyXdLxF8', '2026-04-30 15:19:44', '2026-05-21 19:00:00', '2026-05-08 19:00:00', '2026-05-08 19:45:00', 'Soleado'),
(8, 13, 'jgommar464', 'Parque María Zambrano', NULL, '2026-05-05 17:36:25', '2026-05-21 18:30:00', NULL, NULL, 'Parcialmente nublado (día)'),
(9, 14, 'jgommar464', 'Bem Idiomas', NULL, '2026-05-05 17:41:32', '2026-05-21 18:15:00', NULL, NULL, 'Parcialmente nublado (día)'),
(10, 11, '08Juan80', 'Camaleón Metálico', 'https://maps.app.goo.gl/zSmFibfASH3RSzKCA', '2026-05-15 12:59:01', '2026-05-21 18:00:00', '2026-05-15 18:00:00', '2026-05-15 18:45:00', 'Viento'),
(11, 12, '08Juan80', 'Supermercado Día', NULL, '2026-05-25 03:43:58', '2026-05-25 14:00:00', NULL, NULL, 'Soleado');

--
-- Disparadores `listas`
--
DELIMITER $$
CREATE TRIGGER `borrado_listas` BEFORE DELETE ON `listas` FOR EACH ROW BEGIN
	INSERT INTO listas_borradas
    VALUES(old.ID_Lista, NOW(), old.ID_Raid, old.Creado_por, old.Ubicacion, old.Enlace_Maps, old.Hora_creacion, old.Hora_quedada, old.Hora_inicio, old.Hora_fin, old.Tiempo_atmos);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listas_borradas`
--

CREATE TABLE `listas_borradas` (
  `ID_Lista_Borrada` int(11) NOT NULL,
  `Hora_borrada` datetime NOT NULL,
  `ID_Raid` int(11) NOT NULL,
  `Creado_por` varchar(20) NOT NULL,
  `Ubicacion` varchar(30) NOT NULL,
  `Enlace_Maps` varchar(50) DEFAULT NULL,
  `Hora_creacion` datetime NOT NULL,
  `Hora_quedada` datetime NOT NULL,
  `Hora_inicio` datetime DEFAULT NULL,
  `Hora_fin` datetime DEFAULT NULL,
  `Tiempo_atmos` enum('Soleado','Despejado','Parcialmente nublado (día)','Parcialmente nublado (noche)','Nublado','Lluvia','Viento','Niebla','Nieve','Extremo') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `listas_borradas`
--

INSERT INTO `listas_borradas` (`ID_Lista_Borrada`, `Hora_borrada`, `ID_Raid`, `Creado_por`, `Ubicacion`, `Enlace_Maps`, `Hora_creacion`, `Hora_quedada`, `Hora_inicio`, `Hora_fin`, `Tiempo_atmos`) VALUES
(4, '2026-05-05 08:57:18', 1, '08Juan80', 'Caballo Pegaso', NULL, '2026-04-29 15:42:06', '2026-04-30 12:10:00', NULL, NULL, 'Soleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `ID_Log` int(11) NOT NULL,
  `Tipo_Log` varchar(50) NOT NULL,
  `Nivel_Log` varchar(20) NOT NULL DEFAULT 'Info',
  `Fecha_Log` datetime NOT NULL,
  `Mensaje` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `ID_Perfil` int(11) NOT NULL,
  `NombrePerfil` varchar(25) NOT NULL,
  `P_VerLista` tinyint(1) DEFAULT 1,
  `P_ApuntarseLista` tinyint(1) DEFAULT 1,
  `P_BorrarListasAjenas` tinyint(1) DEFAULT 0,
  `P_Banear` tinyint(1) DEFAULT 0,
  `P_ModifDatos` tinyint(1) DEFAULT 0,
  `P_Log` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`ID_Perfil`, `NombrePerfil`, `P_VerLista`, `P_ApuntarseLista`, `P_BorrarListasAjenas`, `P_Banear`, `P_ModifDatos`, `P_Log`) VALUES
(1, 'Usuario', 1, 1, 0, 0, 0, 0),
(2, 'Administrador', 1, 1, 1, 1, 1, 1),
(3, 'Baneado', 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pokemon`
--

CREATE TABLE `pokemon` (
  `ID_Pokemon` varchar(8) NOT NULL,
  `Numero_Pokedex` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Tipo1` enum('Normal','Planta','Fuego','Agua','Eléctrico','Bicho','Volador','Roca','Veneno','Tierra','Hielo','Lucha','Psíquico','Fantasma','Dragón','Siniestro','Acero','Hada') NOT NULL,
  `Tipo2` enum('Normal','Planta','Fuego','Agua','Eléctrico','Bicho','Volador','Roca','Veneno','Tierra','Hielo','Lucha','Psíquico','Fantasma','Dragón','Siniestro','Acero','Hada') DEFAULT NULL,
  `PC_100_Nivel_20` int(11) NOT NULL,
  `PC_100_Nivel_25` int(11) NOT NULL,
  `Ataque_base` int(11) NOT NULL,
  `Defensa_base` int(11) NOT NULL,
  `PS_base` int(11) NOT NULL
) ;

--
-- Volcado de datos para la tabla `pokemon`
--

INSERT INTO `pokemon` (`ID_Pokemon`, `Numero_Pokedex`, `Nombre`, `Tipo1`, `Tipo2`, `PC_100_Nivel_20`, `PC_100_Nivel_25`, `Ataque_base`, `Defensa_base`, `PS_base`) VALUES
('0001-0', 1, 'Bulbasaur', 'Planta', 'Veneno', 637, 796, 118, 111, 128),
('0001-1', 1, 'Bulbasaur (Gorro de fiesta)', 'Planta', 'Veneno', 637, 796, 118, 111, 128),
('0001-2', 1, 'Bulbasaur (Visera de Pikachu)', 'Planta', 'Veneno', 637, 796, 118, 111, 128),
('0001-3', 1, 'Bulbasaur (Disfraz Shedinja)', 'Planta', 'Veneno', 637, 796, 118, 111, 128),
('0002-0', 2, 'Ivysaur', 'Planta', 'Veneno', 970, 1213, 151, 143, 155),
('0002-1', 2, 'Ivysaur (Gorro de fiesta)', 'Planta', 'Veneno', 970, 1213, 151, 143, 155),
('0003-0', 3, 'Venusaur', 'Planta', 'Veneno', 1554, 1943, 198, 189, 190),
('0003-1', 3, 'Venusaur (Hembra)', 'Planta', 'Veneno', 1554, 1943, 198, 189, 190),
('0003-2', 3, 'Mega-Venusaur', 'Planta', 'Veneno', 1554, 1943, 241, 246, 190),
('0003-3', 3, 'Venusaur Gigamax', 'Planta', 'Veneno', 1554, 1943, 198, 189, 190),
('0003-4', 3, 'Venusaur (Gorro de fiesta)', 'Planta', 'Veneno', 1554, 1943, 198, 189, 190),
('0003-5', 3, 'Venusaur (Gorro de fiesta - Hembra)', 'Planta', 'Veneno', 1554, 1943, 198, 189, 190),
('0003-6', 3, 'Venusaur (Clon)', 'Planta', 'Veneno', 1554, 1943, 198, 189, 190),
('0003-7', 3, 'Venusaur (Clon - Hembra)', 'Planta', 'Veneno', 1554, 1943, 198, 189, 190),
('0004-0', 4, 'Charmander', 'Fuego', NULL, 560, 700, 116, 93, 118),
('0004-1', 4, 'Charmander (Gorro de fiesta)', 'Fuego', NULL, 560, 700, 116, 93, 118),
('0004-2', 4, 'Charmander (Visera de Pikachu)', 'Fuego', NULL, 560, 700, 116, 93, 118),
('0004-3', 4, 'Charmander (Disfraz de Cubone)', 'Fuego', NULL, 560, 700, 116, 93, 118),
('0005-0', 5, 'Charmeleon', 'Fuego', NULL, 944, 1180, 158, 126, 151),
('0005-1', 5, 'Charmeleon (Gorro de fiesta)', 'Fuego', NULL, 944, 1180, 158, 126, 151),
('0006-0', 6, 'Charizard', 'Fuego', 'Volador', 1651, 2064, 223, 173, 186),
('0006-1', 6, 'Mega-Charizard X', 'Fuego', 'Dragón', 1651, 2064, 273, 213, 186),
('0006-2', 6, 'Mega-Charizard Y', 'Fuego', 'Volador', 1651, 2064, 319, 212, 186),
('0006-3', 6, 'Charizard Gigamax', 'Fuego', 'Volador', 1651, 2064, 223, 173, 186),
('0006-4', 6, 'Charizard (Gorro de fiesta)', 'Fuego', 'Volador', 1651, 2064, 223, 173, 186),
('0006-5', 6, 'Charizard (Clon)', 'Fuego', 'Volador', 1651, 2064, 223, 173, 186),
('0007-0', 7, 'Squirtle', 'Agua', NULL, 540, 675, 94, 121, 127),
('0007-1', 7, 'Squirtle (Gafas de sol)', 'Agua', NULL, 540, 675, 94, 121, 127),
('0007-2', 7, 'Squirtle (Gorro de fiesta)', 'Agua', NULL, 540, 675, 94, 121, 127),
('0007-3', 7, 'Squirtle (Visera de Pikachu)', 'Agua', NULL, 540, 675, 94, 121, 127),
('0007-4', 7, 'Squirtle (Disfraz de Yamask)', 'Agua', NULL, 540, 675, 94, 121, 127),
('0008-0', 8, 'Wartortle', 'Agua', NULL, 850, 1063, 126, 155, 153),
('0008-1', 8, 'Wartortle (Gafas de sol)', 'Agua', NULL, 850, 1063, 126, 155, 153),
('0008-2', 8, 'Wartortle (Gorro de fiesta)', 'Agua', NULL, 850, 1063, 126, 155, 153),
('0009-0', 9, 'Blastoise', 'Agua', NULL, 1409, 1761, 171, 207, 188),
('0009-1', 9, 'Mega-Blastoise', 'Agua', NULL, 1409, 1761, 264, 237, 188),
('0009-2', 9, 'Blastoise Gigamax', 'Agua', NULL, 1409, 1761, 171, 207, 188),
('0009-3', 9, 'Blastoise (Gafas de sol)', 'Agua', NULL, 1409, 1761, 171, 207, 188),
('0009-4', 9, 'Blastoise (Gorro de fiesta)', 'Agua', NULL, 1409, 1761, 171, 207, 188),
('0009-5', 9, 'Blastoise (Clon)', 'Agua', NULL, 1409, 1761, 171, 207, 188),
('0147-0', 147, 'Dratini', 'Dragón', NULL, 574, 717, 119, 91, 121),
('0148-0', 148, 'Dragonair', 'Dragón', NULL, 1017, 1271, 163, 135, 156),
('0149-0', 149, 'Dragonite', 'Dragón', 'Volador', 2167, 2709, 263, 198, 209),
('0149-1', 149, 'Mega-Dragonite', 'Dragón', 'Volador', 2167, 2709, 299, 255, 209),
('0149-2', 149, 'Dragonite (Semana Fashion)', 'Dragón', 'Volador', 2167, 2709, 263, 198, 209),
('0150-0', 150, 'Mewtwo', 'Psíquico', NULL, 2387, 2984, 300, 182, 214),
('0150-1', 150, 'Mega-Mewtwo X', 'Psíquico', 'Lucha', 2387, 2984, 399, 215, 228),
('0150-2', 150, 'Mega-Mewtwo Y', 'Psíquico', NULL, 2387, 2984, 413, 223, 228),
('0150-3', 150, 'Mewtwo Acorazado', 'Psíquico', NULL, 1821, 2276, 182, 278, 214),
('0246-0', 246, 'Larvitar', 'Roca', 'Tierra', 594, 743, 115, 93, 137),
('0247-0', 247, 'Pupitar', 'Roca', 'Tierra', 1009, 1261, 155, 133, 172),
('0248-0', 248, 'Tyranitar', 'Roca', 'Siniestro', 2191, 2739, 251, 207, 225),
('0248-1', 248, 'Mega-Tyranitar', 'Roca', 'Siniestro', 2191, 2739, 309, 276, 225),
('0249-0', 249, 'Lugia', 'Psíquico', 'Volador', 2115, 2645, 193, 310, 235),
('0249-1', 249, 'Lugia Ápex', 'Psíquico', 'Volador', 2115, 2645, 193, 310, 235),
('0250-0', 250, 'Ho-Oh', 'Fuego', 'Volador', 2207, 2759, 239, 244, 214),
('0250-1', 250, 'Ho-Oh Ápex', 'Fuego', 'Volador', 2207, 2759, 239, 244, 214),
('0380-0', 380, 'Latias', 'Dragón', 'Psíquico', 2006, 2507, 228, 246, 190),
('0380-1', 380, 'Mega-Latias', 'Dragón', 'Psíquico', 2006, 2507, 289, 297, 190),
('0381-0', 381, 'Latios', 'Dragón', 'Psíquico', 2178, 2723, 268, 212, 190),
('0381-1', 381, 'Mega-Latios', 'Dragón', 'Psíquico', 2178, 2723, 335, 241, 190),
('0382-0', 382, 'Kyogre', 'Agua', NULL, 2351, 2939, 270, 228, 205),
('0382-1', 382, 'Kyogre Primigenio', 'Agua', NULL, 2351, 2939, 353, 268, 218),
('0383-0', 383, 'Groudon', 'Tierra', NULL, 2351, 2939, 270, 228, 205),
('0383-1', 383, 'Groudon Primigenio', 'Tierra', 'Fuego', 2351, 2939, 353, 268, 218),
('0384-0', 384, 'Rayquaza', 'Dragón', 'Volador', 2191, 2739, 284, 170, 213),
('0384-1', 384, 'Mega-Rayquaza', 'Dragón', 'Volador', 2191, 2739, 377, 210, 227),
('0387-0', 387, 'Turtwig', 'Planta', NULL, 678, 848, 119, 110, 146),
('0387-1', 387, 'Turtwig (Gorro de Maya)', 'Planta', NULL, 678, 848, 119, 110, 146),
('0387-2', 387, 'Turtwig (Boina de León)', 'Planta', NULL, 678, 848, 119, 110, 146),
('0388-0', 388, 'Grotle', 'Planta', NULL, 1080, 1350, 157, 143, 181),
('0389-0', 389, 'Torterra', 'Planta', 'Tierra', 1677, 2096, 202, 188, 216),
('0390-0', 390, 'Chimchar', 'Fuego', NULL, 547, 683, 113, 86, 127),
('0390-1', 390, 'Chimchar (Gorro de Maya)', 'Fuego', NULL, 547, 683, 113, 86, 127),
('0390-2', 390, 'Chimchar (Boina de León)', 'Fuego', NULL, 547, 683, 113, 86, 127),
('0391-0', 391, 'Monferno', 'Fuego', 'Lucha', 899, 1124, 158, 105, 162),
('0392-0', 392, 'Infernape', 'Fuego', 'Lucha', 1533, 1916, 222, 151, 183),
('0393-0', 393, 'Piplup', 'Agua', NULL, 614, 767, 112, 102, 142),
('0393-1', 393, 'Piplup (Disfraz de Halloween)', 'Agua', NULL, 614, 767, 112, 102, 142),
('0393-2', 393, 'Piplup (Gorro de Maya)', 'Agua', NULL, 614, 767, 112, 102, 142),
('0393-3', 393, 'Piplup (Boina de León)', 'Agua', NULL, 614, 767, 112, 102, 142),
('0394-0', 394, 'Prinplup', 'Agua', NULL, 972, 1215, 150, 139, 162),
('0395-0', 395, 'Empoleon', 'Agua', 'Acero', 1657, 2072, 210, 186, 197),
('0483-0', 483, 'Dialga', 'Acero', 'Dragón', 2307, 2884, 275, 211, 205),
('0483-1', 483, 'Dialga (Forma Origen)', 'Acero', 'Dragón', 2337, 2921, 270, 225, 205),
('0484-0', 484, 'Palkia', 'Agua', 'Dragón', 2280, 2850, 280, 215, 189),
('0484-1', 484, 'Palkia (Forma Origen)', 'Agua', 'Dragón', 2367, 2958, 286, 223, 189),
('0487-0', 487, 'Giratina (Forma Modificada)', 'Fantasma', 'Dragón', 1931, 2414, 187, 225, 284),
('0487-1', 487, 'Giratina (Forma Origen)', 'Fantasma', 'Dragón', 2105, 2631, 225, 187, 284),
('0643-0', 643, 'Reshiram', 'Dragón', 'Fuego', 2307, 2884, 275, 211, 205),
('0644-0', 644, 'Zekrom', 'Dragón', 'Eléctrico', 2307, 2884, 275, 211, 205),
('0646-0', 646, 'Kyurem', 'Dragón', 'Hielo', 2042, 2553, 246, 170, 245),
('0646-1', 646, 'Kyurem Blanco', 'Dragón', 'Hielo', 2042, 2553, 310, 183, 245),
('0646-2', 646, 'Kyurem Negro', 'Dragón', 'Hielo', 2042, 2553, 310, 183, 245),
('0716-0', 716, 'Xerneas', 'Hada', NULL, 2160, 2701, 250, 185, 246),
('0717-0', 717, 'Yveltal', 'Siniestro', 'Volador', 2160, 2701, 250, 185, 246),
('0791-0', 791, 'Solgaleo', 'Psíquico', 'Acero', 2310, 2887, 255, 191, 264),
('0792-0', 792, 'Lunala', 'Psíquico', 'Fantasma', 2310, 2887, 255, 191, 264),
('0800-0', 800, 'Necrozma', 'Psíquico', NULL, 2104, 2630, 251, 195, 219),
('0800-1', 800, 'Necrozma Melena Crepuscular', 'Psíquico', 'Acero', 2104, 2630, 277, 220, 200),
('0800-2', 800, 'Necrozma Alas del Alba', 'Psíquico', 'Fantasma', 2104, 2630, 277, 220, 200),
('0800-3', 800, 'Ultra-Necrozma', 'Psíquico', 'Dragón', 2104, 2630, 337, 196, 200),
('0888-0', 888, 'Zacian Guerrero Avezado', 'Hada', NULL, 2188, 2735, 254, 236, 192),
('0888-1', 888, 'Zacian Espada Suprema', 'Hada', 'Acero', 2188, 2735, 332, 240, 192),
('0889-0', 889, 'Zamazenta Guerrero Avezado', 'Lucha', NULL, 2188, 2735, 254, 236, 192),
('0889-1', 889, 'Zamazenta Escudo Supremo', 'Lucha', 'Acero', 2188, 2735, 250, 292, 192),
('0890-0', 890, 'Eternatus', 'Veneno', 'Dragón', 2530, 3163, 278, 192, 268),
('0890-1', 890, 'Eternatus Dinamax Infinito', 'Veneno', 'Dragón', 2530, 3163, 251, 505, 452);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sanciones`
--

CREATE TABLE `sanciones` (
  `ID_Sancion` int(11) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Tipo_sancion` enum('Ban','Restricción') NOT NULL,
  `Duracion` datetime DEFAULT NULL,
  `Motivo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Username` varchar(20) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `Pogo_Username` varchar(15) NOT NULL,
  `Level` int(11) NOT NULL,
  `Team` enum('Valor','Instinto','Sabiduría') DEFAULT NULL,
  `Friend_code` bigint(11) DEFAULT NULL,
  `Profile_Picture` varchar(50) DEFAULT NULL,
  `ID_Perfil` int(11) DEFAULT 1
) ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Username`, `Email`, `Password`, `Pogo_Username`, `Level`, `Team`, `Friend_code`, `Profile_Picture`, `ID_Perfil`) VALUES
('08Juan80', 'familia_gomez_martin@hotmail.com', '$2y$10$/mtrp0Qz2H77By9.KV8vTuXuLdF3EjNlv8ueIMQS282QT1Q5NGZnG', 'x08Juan80x', 77, 'Valor', 123456789012, NULL, 2),
('jgommar464', 'jgommar464@g.educaand.es', '$2y$10$yKpxY8/9VG4l2Nz07BLC2OSOLmYn22gsRjbwlv9T5N7UEAVnU7Bkm', 'jgommar464', 73, 'Sabiduría', 152493562456, NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apuntados_lista`
--
ALTER TABLE `apuntados_lista`
  ADD PRIMARY KEY (`ID_Lista`,`Username`),
  ADD KEY `FK_apuntados_usuario` (`Username`);

--
-- Indices de la tabla `apuntados_lista_borrada`
--
ALTER TABLE `apuntados_lista_borrada`
  ADD PRIMARY KEY (`ID_Lista_Borrada`,`Username`),
  ADD KEY `FK_apuntados_usuario` (`Username`);

--
-- Indices de la tabla `incursiones`
--
ALTER TABLE `incursiones`
  ADD PRIMARY KEY (`ID_Raid`),
  ADD KEY `FK_incursiones_pokemon` (`ID_Pokemon`);

--
-- Indices de la tabla `listas`
--
ALTER TABLE `listas`
  ADD PRIMARY KEY (`ID_Lista`),
  ADD KEY `FK_listas_raid` (`ID_Raid`),
  ADD KEY `FK_listas_creador` (`Creado_por`);

--
-- Indices de la tabla `listas_borradas`
--
ALTER TABLE `listas_borradas`
  ADD PRIMARY KEY (`ID_Lista_Borrada`),
  ADD KEY `FK_listas_raid` (`ID_Raid`),
  ADD KEY `FK_listas_creador` (`Creado_por`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`ID_Log`);

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`ID_Perfil`);

--
-- Indices de la tabla `pokemon`
--
ALTER TABLE `pokemon`
  ADD PRIMARY KEY (`ID_Pokemon`);

--
-- Indices de la tabla `sanciones`
--
ALTER TABLE `sanciones`
  ADD PRIMARY KEY (`ID_Sancion`),
  ADD KEY `FK_sanciones_usuario` (`Username`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Pogo_Username` (`Pogo_Username`),
  ADD KEY `FK_perfil_usuario` (`ID_Perfil`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `incursiones`
--
ALTER TABLE `incursiones`
  MODIFY `ID_Raid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `listas`
--
ALTER TABLE `listas`
  MODIFY `ID_Lista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `listas_borradas`
--
ALTER TABLE `listas_borradas`
  MODIFY `ID_Lista_Borrada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `ID_Log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `ID_Perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sanciones`
--
ALTER TABLE `sanciones`
  MODIFY `ID_Sancion` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `apuntados_lista`
--
ALTER TABLE `apuntados_lista`
  ADD CONSTRAINT `FK_apuntados_lista` FOREIGN KEY (`ID_Lista`) REFERENCES `listas` (`ID_Lista`),
  ADD CONSTRAINT `FK_apuntados_usuario` FOREIGN KEY (`Username`) REFERENCES `usuarios` (`Username`);

--
-- Filtros para la tabla `incursiones`
--
ALTER TABLE `incursiones`
  ADD CONSTRAINT `FK_incursiones_pokemon` FOREIGN KEY (`ID_Pokemon`) REFERENCES `pokemon` (`ID_Pokemon`);

--
-- Filtros para la tabla `listas`
--
ALTER TABLE `listas`
  ADD CONSTRAINT `FK_listas_creador` FOREIGN KEY (`Creado_por`) REFERENCES `usuarios` (`Username`),
  ADD CONSTRAINT `FK_listas_raid` FOREIGN KEY (`ID_Raid`) REFERENCES `incursiones` (`ID_Raid`);

--
-- Filtros para la tabla `listas_borradas`
--
ALTER TABLE `listas_borradas`
  ADD CONSTRAINT `FK_listas_creador` FOREIGN KEY (`Creado_por`) REFERENCES `usuarios` (`Username`),
  ADD CONSTRAINT `FK_listas_raid` FOREIGN KEY (`ID_Raid`) REFERENCES `incursiones` (`ID_Raid`);

--
-- Filtros para la tabla `sanciones`
--
ALTER TABLE `sanciones`
  ADD CONSTRAINT `FK_sanciones_usuario` FOREIGN KEY (`Username`) REFERENCES `usuarios` (`Username`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `FK_perfil_usuario` FOREIGN KEY (`ID_Perfil`) REFERENCES `perfiles` (`ID_Perfil`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
