-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para plan_estrategico
CREATE DATABASE IF NOT EXISTS `plan_estrategico` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `plan_estrategico`;

-- Volcando estructura para tabla plan_estrategico.tb_amenazas
CREATE TABLE IF NOT EXISTS `tb_amenazas` (
  `id_amenaza` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `origen` enum('pest','porter','manual') DEFAULT 'manual',
  `orden` int(11) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_amenaza`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_amenazas_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_amenazas: ~11 rows (aproximadamente)
INSERT INTO `tb_amenazas` (`id_amenaza`, `id_plan`, `descripcion`, `origen`, `orden`, `fecha_creacion`) VALUES
	(1, 9, 'amenaza nuevo', 'pest', 0, '2025-06-19 14:31:51'),
	(2, 10, 'amenaza nuevo', 'pest', 0, '2025-06-19 14:31:51'),
	(3, 11, 'amenaza del peste', 'pest', 0, '2025-06-19 14:31:51'),
	(4, 12, 'amenazas nuevas', 'pest', 0, '2025-06-19 14:31:51'),
	(5, 13, 'amenazas nuevas', 'pest', 0, '2025-06-19 14:41:01'),
	(6, 14, 'Amenaza PEST 1', 'pest', 0, '2025-06-19 14:56:14'),
	(7, 14, 'Amenaza PEST 2', 'pest', 1, '2025-06-19 14:56:14'),
	(8, 15, 'Amenaza PEST 1', 'pest', 0, '2025-06-19 14:57:03'),
	(9, 15, 'Amenaza PEST 2', 'pest', 1, '2025-06-19 14:57:03'),
	(10, 16, 'amanezas indetnfificadas', 'pest', 0, '2025-06-19 15:03:07'),
	(11, 17, 'oportundiade nuevo', 'pest', 0, '2025-06-19 15:17:58'),
	(12, 19, 'amenaza nueva', 'pest', 0, '2025-06-19 17:14:32'),
	(13, 22, 'amenazas nuevoo', 'pest', 0, '2025-06-19 17:32:46'),
	(14, 23, 'wdwdwdw', 'pest', 0, '2025-06-19 21:08:34');

-- Volcando estructura para tabla plan_estrategico.tb_analisis_pest
CREATE TABLE IF NOT EXISTS `tb_analisis_pest` (
  `id_pest` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `factor_politico` text DEFAULT NULL,
  `factor_economico` text DEFAULT NULL,
  `factor_social` text DEFAULT NULL,
  `factor_tecnologico` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pest`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_pest_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_analisis_pest: ~9 rows (aproximadamente)
INSERT INTO `tb_analisis_pest` (`id_pest`, `id_plan`, `factor_politico`, `factor_economico`, `factor_social`, `factor_tecnologico`, `fecha_creacion`) VALUES
	(1, 9, '5', '5', '5', '5', '2025-06-19 01:06:38'),
	(2, 10, '5', '5', '5', '5', '2025-06-19 01:45:27'),
	(3, 11, '5', '5', '5', '5', '2025-06-19 09:00:09'),
	(4, 12, '5', '5', '5', '5', '2025-06-19 09:16:16'),
	(5, 13, '5', '5', '5', '5', '2025-06-19 09:41:01'),
	(6, 14, 'Factor político de prueba para debugging', 'Factor económico de prueba para debugging', 'Factor social de prueba para debugging', 'Factor tecnológico de prueba para debugging', '2025-06-19 09:56:14'),
	(7, 15, 'Factor político de prueba para debugging', 'Factor económico de prueba para debugging', 'Factor social de prueba para debugging', 'Factor tecnológico de prueba para debugging', '2025-06-19 09:57:03'),
	(8, 16, '5', '5', '5', '5', '2025-06-19 10:03:07'),
	(9, 17, '5', '5', '5', '5', '2025-06-19 10:17:58'),
	(10, 19, '5', '5', '5', '5', '2025-06-19 12:14:32'),
	(11, 22, '5', '5', '5', '5', '2025-06-19 12:32:46'),
	(12, 23, '5', '5', '5', '5', '2025-06-19 16:08:34');

-- Volcando estructura para tabla plan_estrategico.tb_cadena_valor
CREATE TABLE IF NOT EXISTS `tb_cadena_valor` (
  `id_cadena_valor` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `q1` tinyint(1) DEFAULT NULL,
  `q2` tinyint(1) DEFAULT NULL,
  `q3` tinyint(1) DEFAULT NULL,
  `q4` tinyint(1) DEFAULT NULL,
  `q5` tinyint(1) DEFAULT NULL,
  `q6` tinyint(1) DEFAULT NULL,
  `q7` tinyint(1) DEFAULT NULL,
  `q8` tinyint(1) DEFAULT NULL,
  `q9` tinyint(1) DEFAULT NULL,
  `q10` tinyint(1) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_cadena_valor`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_cadena_valor_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_cadena_valor: ~11 rows (aproximadamente)
INSERT INTO `tb_cadena_valor` (`id_cadena_valor`, `id_plan`, `q1`, `q2`, `q3`, `q4`, `q5`, `q6`, `q7`, `q8`, `q9`, `q10`, `fecha_creacion`) VALUES
	(1, 5, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-18 23:35:35'),
	(2, 6, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-18 23:35:45'),
	(3, 7, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-18 23:35:57'),
	(4, 8, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-18 23:52:18'),
	(5, 9, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-19 01:06:38'),
	(6, 10, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-19 01:45:27'),
	(7, 11, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-19 09:00:09'),
	(8, 12, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-19 09:16:16'),
	(9, 13, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-19 09:41:01'),
	(10, 16, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-19 10:03:07'),
	(11, 17, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-19 10:17:58'),
	(12, 19, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-19 12:14:32'),
	(13, 22, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-19 12:32:46'),
	(14, 23, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, '2025-06-19 16:08:34');

-- Volcando estructura para tabla plan_estrategico.tb_came
CREATE TABLE IF NOT EXISTS `tb_came` (
  `id_came` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL DEFAULT 1,
  `tipo_estrategia` varchar(20) NOT NULL,
  `estrategia` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_came`),
  KEY `id_empresa` (`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla plan_estrategico.tb_came: ~0 rows (aproximadamente)

-- Volcando estructura para tabla plan_estrategico.tb_debilidades
CREATE TABLE IF NOT EXISTS `tb_debilidades` (
  `id_debilidad` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `orden` int(11) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_debilidad`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_debilidades_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_debilidades: ~15 rows (aproximadamente)
INSERT INTO `tb_debilidades` (`id_debilidad`, `id_plan`, `descripcion`, `orden`, `fecha_creacion`) VALUES
	(1, 1, 'debilidad de bcg', 0, '2025-06-19 14:31:51'),
	(2, 2, 'debilidad de bcg', 0, '2025-06-19 14:31:51'),
	(3, 3, 'debilidad de bcg', 0, '2025-06-19 14:31:51'),
	(4, 4, 'debilidad de bcg', 0, '2025-06-19 14:31:51'),
	(5, 5, '3', 0, '2025-06-19 14:31:51'),
	(6, 6, '3', 0, '2025-06-19 14:31:51'),
	(7, 7, '3', 0, '2025-06-19 14:31:51'),
	(8, 8, 'debilidad bcg', 0, '2025-06-19 14:31:51'),
	(9, 9, 'debilidades bcg', 0, '2025-06-19 14:31:51'),
	(10, 10, 'debilidad de bcgggggggggg', 0, '2025-06-19 14:31:51'),
	(11, 11, 'fortaleza de bicigi', 0, '2025-06-19 14:31:51'),
	(12, 12, 'debilidade bcg', 0, '2025-06-19 14:31:51'),
	(13, 13, 'debilidades bcggg', 0, '2025-06-19 14:41:01'),
	(14, 16, 'debilidades bcg 2', 0, '2025-06-19 15:03:07'),
	(15, 17, 'debilidad bcccccccccccg', 0, '2025-06-19 15:17:58'),
	(16, 19, 'fortaleza bgc', 0, '2025-06-19 17:14:32'),
	(17, 22, 'debiliades bcggsgsgs', 0, '2025-06-19 17:32:46'),
	(18, 23, 'dsadsadsa', 0, '2025-06-19 21:08:34');

-- Volcando estructura para tabla plan_estrategico.tb_empresa
CREATE TABLE IF NOT EXISTS `tb_empresa` (
  `id_empresa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `sector` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla plan_estrategico.tb_empresa: ~1 rows (aproximadamente)
INSERT INTO `tb_empresa` (`id_empresa`, `nombre`, `descripcion`, `sector`, `fecha_creacion`) VALUES
	(1, 'Empresa por defecto', 'Empresa creada automáticamente', NULL, '2025-06-19 14:31:51');

-- Volcando estructura para tabla plan_estrategico.tb_estrategias
CREATE TABLE IF NOT EXISTS `tb_estrategias` (
  `id_estrategia` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `tipo_estrategia` enum('FO','FA','DO','DA') NOT NULL,
  `descripcion` text NOT NULL,
  `prioridad` enum('alta','media','baja') DEFAULT 'media',
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_estrategia`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_estrategias_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_estrategias: ~27 rows (aproximadamente)
INSERT INTO `tb_estrategias` (`id_estrategia`, `id_plan`, `tipo_estrategia`, `descripcion`, `prioridad`, `orden`) VALUES
	(1, 8, 'FO', '', 'media', 0),
	(2, 8, 'FO', '', 'media', 0),
	(3, 8, 'FO', '', 'media', 0),
	(4, 8, 'FO', '', 'media', 0),
	(5, 8, 'FO', '', 'media', 0),
	(6, 8, 'FO', '', 'media', 0),
	(7, 8, 'FO', '', 'media', 0),
	(8, 8, 'FO', '', 'media', 0),
	(9, 8, 'FO', '', 'media', 0),
	(10, 9, 'FO', '', 'media', 0),
	(11, 9, 'FO', '', 'media', 0),
	(12, 9, 'FO', '', 'media', 0),
	(13, 9, 'FO', '', 'media', 0),
	(14, 9, 'FO', '', 'media', 0),
	(15, 9, 'FO', '', 'media', 0),
	(16, 9, 'FO', '', 'media', 0),
	(17, 9, 'FO', '', 'media', 0),
	(18, 9, 'FO', '', 'media', 0),
	(19, 10, 'FO', '', 'media', 0),
	(20, 10, 'FO', '', 'media', 0),
	(21, 10, 'FO', '', 'media', 0),
	(22, 10, 'FO', '', 'media', 0),
	(23, 10, 'FO', '', 'media', 0),
	(24, 10, 'FO', '', 'media', 0),
	(25, 10, 'FO', '', 'media', 0),
	(26, 10, 'FO', '', 'media', 0),
	(27, 10, 'FO', '', 'media', 0);

-- Volcando estructura para tabla plan_estrategico.tb_estrategias_came_detalle
CREATE TABLE IF NOT EXISTS `tb_estrategias_came_detalle` (
  `id_estrategia_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `tipo_came` enum('corregir','afrontar','mantener','explotar') NOT NULL,
  `indice_elemento` int(11) NOT NULL,
  `estrategia` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_estrategia_detalle`),
  KEY `id_plan` (`id_plan`),
  CONSTRAINT `tb_estrategias_came_detalle_ibfk_1` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_estrategias_came_detalle: ~105 rows (aproximadamente)
INSERT INTO `tb_estrategias_came_detalle` (`id_estrategia_detalle`, `id_plan`, `tipo_came`, `indice_elemento`, `estrategia`, `fecha_creacion`) VALUES
	(1, 2, 'corregir', 0, 'a1', '2025-06-19 04:23:15'),
	(2, 2, 'corregir', 1, 'a2', '2025-06-19 04:23:15'),
	(3, 2, 'corregir', 2, 'a3', '2025-06-19 04:23:15'),
	(4, 2, 'corregir', 3, 'e4', '2025-06-19 04:23:15'),
	(5, 2, 'afrontar', 0, 'e5', '2025-06-19 04:23:15'),
	(6, 2, 'afrontar', 1, 'e6', '2025-06-19 04:23:15'),
	(7, 2, 'afrontar', 2, 'e7', '2025-06-19 04:23:15'),
	(8, 2, 'mantener', 0, 'e8', '2025-06-19 04:23:15'),
	(9, 2, 'mantener', 1, 'e9', '2025-06-19 04:23:15'),
	(10, 2, 'mantener', 2, 'e10', '2025-06-19 04:23:15'),
	(11, 2, 'explotar', 0, 'e11', '2025-06-19 04:23:15'),
	(12, 2, 'explotar', 1, 'e12', '2025-06-19 04:23:15'),
	(13, 2, 'explotar', 2, 'e13', '2025-06-19 04:23:15'),
	(14, 3, 'corregir', 0, 'a1', '2025-06-19 04:23:28'),
	(15, 3, 'corregir', 1, 'a2', '2025-06-19 04:23:28'),
	(16, 3, 'corregir', 2, 'a3', '2025-06-19 04:23:28'),
	(17, 3, 'corregir', 3, 'e4', '2025-06-19 04:23:28'),
	(18, 3, 'afrontar', 0, 'e5', '2025-06-19 04:23:28'),
	(19, 3, 'afrontar', 1, 'e6', '2025-06-19 04:23:28'),
	(20, 3, 'afrontar', 2, 'e7', '2025-06-19 04:23:28'),
	(21, 3, 'mantener', 0, 'e8', '2025-06-19 04:23:28'),
	(22, 3, 'mantener', 1, 'e9', '2025-06-19 04:23:28'),
	(23, 3, 'mantener', 2, 'e10', '2025-06-19 04:23:28'),
	(24, 3, 'explotar', 0, 'e11', '2025-06-19 04:23:28'),
	(25, 3, 'explotar', 1, 'e12', '2025-06-19 04:23:28'),
	(26, 3, 'explotar', 2, 'e13', '2025-06-19 04:23:28'),
	(27, 4, 'corregir', 0, 'a1', '2025-06-19 04:25:28'),
	(28, 4, 'corregir', 1, 'a2', '2025-06-19 04:25:28'),
	(29, 4, 'corregir', 2, 'a3', '2025-06-19 04:25:28'),
	(30, 4, 'corregir', 3, 'e4', '2025-06-19 04:25:28'),
	(31, 4, 'afrontar', 0, 'e5', '2025-06-19 04:25:28'),
	(32, 4, 'afrontar', 1, 'e6', '2025-06-19 04:25:28'),
	(33, 4, 'afrontar', 2, 'e7', '2025-06-19 04:25:28'),
	(34, 4, 'mantener', 0, 'e8', '2025-06-19 04:25:28'),
	(35, 4, 'mantener', 1, 'e9', '2025-06-19 04:25:28'),
	(36, 4, 'mantener', 2, 'e10', '2025-06-19 04:25:28'),
	(37, 4, 'explotar', 0, 'e11', '2025-06-19 04:25:28'),
	(38, 4, 'explotar', 1, 'e12', '2025-06-19 04:25:28'),
	(39, 4, 'explotar', 2, 'e13', '2025-06-19 04:25:28'),
	(40, 7, 'corregir', 0, 'a', '2025-06-19 04:35:57'),
	(41, 7, 'afrontar', 0, 'ds', '2025-06-19 04:35:57'),
	(42, 7, 'mantener', 0, 'dsd', '2025-06-19 04:35:57'),
	(43, 7, 'explotar', 0, 'dsds', '2025-06-19 04:35:57'),
	(44, 8, 'corregir', 0, 'aaaaa', '2025-06-19 04:52:18'),
	(45, 8, 'corregir', 1, 'aaaaaaassssss', '2025-06-19 04:52:18'),
	(46, 8, 'afrontar', 0, 'sssssssss', '2025-06-19 04:52:18'),
	(47, 8, 'mantener', 0, 'eeeeeee', '2025-06-19 04:52:18'),
	(48, 8, 'mantener', 1, 'eeeeeeeee', '2025-06-19 04:52:18'),
	(49, 8, 'explotar', 0, 'eeeeeeeeeeee', '2025-06-19 04:52:18'),
	(50, 9, 'corregir', 0, 'dsadsa', '2025-06-19 06:06:38'),
	(51, 9, 'corregir', 1, 'dsadsa', '2025-06-19 06:06:38'),
	(52, 9, 'afrontar', 0, 'dsad', '2025-06-19 06:06:38'),
	(53, 9, 'afrontar', 1, 'ddd', '2025-06-19 06:06:38'),
	(54, 9, 'mantener', 0, 'dsad', '2025-06-19 06:06:38'),
	(55, 9, 'mantener', 1, 'dsadsa', '2025-06-19 06:06:38'),
	(56, 9, 'explotar', 0, 'dsd', '2025-06-19 06:06:38'),
	(57, 9, 'explotar', 1, 'dsadsadsa', '2025-06-19 06:06:38'),
	(58, 10, 'corregir', 0, 'dsadsas', '2025-06-19 06:45:27'),
	(59, 10, 'corregir', 1, 'dsadsa', '2025-06-19 06:45:27'),
	(60, 10, 'afrontar', 0, 'dsadsss', '2025-06-19 06:45:27'),
	(61, 10, 'afrontar', 1, 'ddd', '2025-06-19 06:45:27'),
	(62, 10, 'mantener', 0, 'dsad', '2025-06-19 06:45:27'),
	(63, 10, 'mantener', 1, 'dsadsa', '2025-06-19 06:45:27'),
	(64, 10, 'explotar', 0, 'dsd', '2025-06-19 06:45:27'),
	(65, 10, 'explotar', 1, 'dsadsadsa', '2025-06-19 06:45:27'),
	(66, 11, 'corregir', 0, 'corrigiendo', '2025-06-19 14:00:09'),
	(67, 11, 'corregir', 1, 't1', '2025-06-19 14:00:09'),
	(68, 11, 'afrontar', 0, 't2', '2025-06-19 14:00:09'),
	(69, 11, 'afrontar', 1, 't3', '2025-06-19 14:00:09'),
	(70, 11, 'mantener', 0, 't4', '2025-06-19 14:00:09'),
	(71, 11, 'mantener', 1, 't5', '2025-06-19 14:00:09'),
	(72, 11, 'explotar', 0, 't1', '2025-06-19 14:00:09'),
	(73, 11, 'explotar', 1, 't2', '2025-06-19 14:00:09'),
	(74, 12, 'corregir', 0, 't1', '2025-06-19 14:16:16'),
	(75, 12, 'corregir', 1, 't2', '2025-06-19 14:16:16'),
	(76, 12, 'afrontar', 0, 't3', '2025-06-19 14:16:16'),
	(77, 12, 'afrontar', 1, 't5', '2025-06-19 14:16:16'),
	(78, 12, 'mantener', 0, 't6', '2025-06-19 14:16:16'),
	(79, 12, 'mantener', 1, 't3', '2025-06-19 14:16:16'),
	(80, 12, 'explotar', 0, 't7t8', '2025-06-19 14:16:16'),
	(81, 12, 'explotar', 1, 't8', '2025-06-19 14:16:16'),
	(82, 13, 'corregir', 0, 't1', '2025-06-19 14:41:01'),
	(83, 13, 'corregir', 1, 't2', '2025-06-19 14:41:01'),
	(84, 13, 'afrontar', 0, 't3', '2025-06-19 14:41:01'),
	(85, 13, 'afrontar', 1, 't5', '2025-06-19 14:41:01'),
	(86, 13, 'mantener', 0, 't6', '2025-06-19 14:41:01'),
	(87, 13, 'mantener', 1, 't3', '2025-06-19 14:41:01'),
	(88, 13, 'explotar', 0, 't7t8', '2025-06-19 14:41:01'),
	(89, 13, 'explotar', 1, 't8', '2025-06-19 14:41:01'),
	(90, 16, 'corregir', 0, 'debilidad corregida', '2025-06-19 15:03:07'),
	(91, 16, 'corregir', 1, 'debilidad corregida', '2025-06-19 15:03:07'),
	(92, 16, 'afrontar', 0, 'Amenazas corregida', '2025-06-19 15:03:07'),
	(93, 16, 'afrontar', 1, 'Amenazas corregida', '2025-06-19 15:03:07'),
	(94, 16, 'mantener', 0, 'fortaleza corregida', '2025-06-19 15:03:07'),
	(95, 16, 'mantener', 1, 'fortaleza corregida2', '2025-06-19 15:03:07'),
	(96, 16, 'explotar', 0, 'oportunidad corregida', '2025-06-19 15:03:07'),
	(97, 16, 'explotar', 1, 'oportunidad corregida2', '2025-06-19 15:03:07'),
	(98, 17, 'corregir', 0, 'debilidad valorcorregicod', '2025-06-19 15:17:58'),
	(99, 17, 'corregir', 1, 'debilidad valors22', '2025-06-19 15:17:58'),
	(100, 17, 'afrontar', 0, 'oportundiade nuevo222', '2025-06-19 15:17:58'),
	(101, 17, 'afrontar', 1, 'amenaza poter23232', '2025-06-19 15:17:58'),
	(102, 17, 'mantener', 0, 'fprtalza valor232323', '2025-06-19 15:17:58'),
	(103, 17, 'mantener', 1, 'fortaleza bcccgg43535353', '2025-06-19 15:17:58'),
	(104, 17, 'explotar', 0, 'oportundiade neuv2112321', '2025-06-19 15:17:58'),
	(105, 17, 'explotar', 1, 'oprotunidade poter5556', '2025-06-19 15:17:58'),
	(106, 19, 'corregir', 0, 'adsa', '2025-06-19 17:14:32'),
	(107, 19, 'corregir', 1, 'dsada', '2025-06-19 17:14:32'),
	(108, 19, 'afrontar', 0, 'dsd', '2025-06-19 17:14:32'),
	(109, 19, 'afrontar', 1, 'sdsds', '2025-06-19 17:14:32'),
	(110, 19, 'mantener', 0, 'dsdsd', '2025-06-19 17:14:32'),
	(111, 19, 'mantener', 1, 'sdsds', '2025-06-19 17:14:32'),
	(112, 19, 'explotar', 0, 'dsdsds', '2025-06-19 17:14:32'),
	(113, 19, 'explotar', 1, 'dsdsdsds', '2025-06-19 17:14:32'),
	(114, 22, 'corregir', 0, 't1', '2025-06-19 17:32:46'),
	(115, 22, 'corregir', 1, 't2', '2025-06-19 17:32:46'),
	(116, 22, 'afrontar', 0, 't3', '2025-06-19 17:32:46'),
	(117, 22, 'afrontar', 1, 't4', '2025-06-19 17:32:46'),
	(118, 22, 'mantener', 0, 't5', '2025-06-19 17:32:46'),
	(119, 22, 'mantener', 1, 't6', '2025-06-19 17:32:46'),
	(120, 22, 'explotar', 0, 't7', '2025-06-19 17:32:46'),
	(121, 22, 'explotar', 1, 't9', '2025-06-19 17:32:46'),
	(122, 23, 'corregir', 0, 'dasd', '2025-06-19 21:08:34'),
	(123, 23, 'corregir', 1, 'adsad', '2025-06-19 21:08:34'),
	(124, 23, 'afrontar', 0, 'dsa', '2025-06-19 21:08:34'),
	(125, 23, 'afrontar', 1, 'dsadas', '2025-06-19 21:08:34'),
	(126, 23, 'mantener', 0, 'dsad', '2025-06-19 21:08:34'),
	(127, 23, 'mantener', 1, 'dasdasdas', '2025-06-19 21:08:34'),
	(128, 23, 'explotar', 0, 'dsadas', '2025-06-19 21:08:34'),
	(129, 23, 'explotar', 1, 'dasdas', '2025-06-19 21:08:34');

-- Volcando estructura para tabla plan_estrategico.tb_fortalezas
CREATE TABLE IF NOT EXISTS `tb_fortalezas` (
  `id_fortaleza` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `orden` int(11) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_fortaleza`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_fortalezas_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_fortalezas: ~15 rows (aproximadamente)
INSERT INTO `tb_fortalezas` (`id_fortaleza`, `id_plan`, `descripcion`, `orden`, `fecha_creacion`) VALUES
	(1, 1, 'fortaleza de bcg', 0, '2025-06-19 14:31:51'),
	(2, 2, 'fortaleza de bcg', 0, '2025-06-19 14:31:51'),
	(3, 3, 'fortaleza de bcg', 0, '2025-06-19 14:31:51'),
	(4, 4, 'fortaleza de bcg', 0, '2025-06-19 14:31:51'),
	(5, 5, '3', 0, '2025-06-19 14:31:51'),
	(6, 6, '3', 0, '2025-06-19 14:31:51'),
	(7, 7, '3', 0, '2025-06-19 14:31:51'),
	(8, 8, 'fortaleza de bcg', 0, '2025-06-19 14:31:51'),
	(9, 9, 'fortaleza de bcgggg', 0, '2025-06-19 14:31:51'),
	(10, 10, 'fortaleza de bcg', 0, '2025-06-19 14:31:51'),
	(11, 11, 'fortaleza de bicigi', 0, '2025-06-19 14:31:51'),
	(12, 12, 'fortaleza bcg', 0, '2025-06-19 14:31:51'),
	(13, 13, 'fortaleeza bcgg', 0, '2025-06-19 14:41:01'),
	(14, 16, 'fortalezas bcggg', 0, '2025-06-19 15:03:07'),
	(15, 17, 'fortaleza bcccgg', 0, '2025-06-19 15:17:58'),
	(16, 19, 'fortaleza bcg', 0, '2025-06-19 17:14:32'),
	(17, 22, 'fortaleza bdsdsccggg', 0, '2025-06-19 17:32:46'),
	(18, 23, 'fortaleza', 0, '2025-06-19 21:08:34');

-- Volcando estructura para tabla plan_estrategico.tb_fuerzas_porter
CREATE TABLE IF NOT EXISTS `tb_fuerzas_porter` (
  `id_fuerza_porter` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `poder_negociacion_proveedores` text DEFAULT NULL,
  `poder_negociacion_compradores` text DEFAULT NULL,
  `amenaza_productos_sustitutos` text DEFAULT NULL,
  `amenaza_nuevos_competidores` text DEFAULT NULL,
  `rivalidad_competidores` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_fuerza_porter`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_fuerzas_porter_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_fuerzas_porter: ~7 rows (aproximadamente)
INSERT INTO `tb_fuerzas_porter` (`id_fuerza_porter`, `id_plan`, `poder_negociacion_proveedores`, `poder_negociacion_compradores`, `amenaza_productos_sustitutos`, `amenaza_nuevos_competidores`, `rivalidad_competidores`, `fecha_creacion`) VALUES
	(1, 9, '', '', '', '', '', '2025-06-19 01:06:38'),
	(2, 10, '', '', '', '', '', '2025-06-19 01:45:27'),
	(3, 11, '', '', '', '', '', '2025-06-19 09:00:09'),
	(4, 12, '', '', '', '', '', '2025-06-19 09:16:16'),
	(5, 13, '', '', '', '', '', '2025-06-19 09:41:01'),
	(6, 16, '', '', '', '', '', '2025-06-19 10:03:07'),
	(7, 17, '', '', '', '', '', '2025-06-19 10:17:58'),
	(8, 19, '', '', '', '', '', '2025-06-19 12:14:32'),
	(9, 22, '', '', '', '', '', '2025-06-19 12:32:46'),
	(10, 23, '', '', '', '', '', '2025-06-19 16:08:34');

-- Volcando estructura para tabla plan_estrategico.tb_matrices_foda
CREATE TABLE IF NOT EXISTS `tb_matrices_foda` (
  `id_matriz` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL DEFAULT 1,
  `tipo_relacion` varchar(10) NOT NULL,
  `elemento_1` varchar(255) NOT NULL,
  `elemento_2` varchar(255) NOT NULL,
  `puntuacion` int(11) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_matriz`),
  KEY `id_empresa` (`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla plan_estrategico.tb_matrices_foda: ~0 rows (aproximadamente)

-- Volcando estructura para tabla plan_estrategico.tb_matriz_bcg_competidores
CREATE TABLE IF NOT EXISTS `tb_matriz_bcg_competidores` (
  `id_competidor` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `competidor` varchar(100) NOT NULL,
  `valor` decimal(15,2) DEFAULT 0.00,
  PRIMARY KEY (`id_competidor`),
  KEY `idx_plan` (`id_plan`),
  KEY `idx_producto` (`id_producto`),
  CONSTRAINT `fk_bcg_competidores_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE,
  CONSTRAINT `fk_bcg_competidores_producto` FOREIGN KEY (`id_producto`) REFERENCES `tb_matriz_bcg_productos` (`id_producto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_matriz_bcg_competidores: ~0 rows (aproximadamente)

-- Volcando estructura para tabla plan_estrategico.tb_matriz_bcg_config
CREATE TABLE IF NOT EXISTS `tb_matriz_bcg_config` (
  `id_config` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `num_productos` int(11) NOT NULL DEFAULT 5,
  `num_competidores` int(11) NOT NULL DEFAULT 5,
  `anio_inicio` int(11) NOT NULL DEFAULT 2020,
  `anio_fin` int(11) NOT NULL DEFAULT 2025,
  PRIMARY KEY (`id_config`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_bcg_config_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_matriz_bcg_config: ~15 rows (aproximadamente)
INSERT INTO `tb_matriz_bcg_config` (`id_config`, `id_plan`, `num_productos`, `num_competidores`, `anio_inicio`, `anio_fin`) VALUES
	(1, 1, 3, 3, 2012, 2016),
	(2, 2, 3, 3, 2012, 2016),
	(3, 3, 3, 3, 2012, 2016),
	(4, 4, 3, 3, 2012, 2016),
	(5, 5, 2, 3, 2012, 2016),
	(6, 6, 2, 3, 2012, 2016),
	(7, 7, 2, 3, 2012, 2016),
	(8, 8, 3, 3, 2012, 2016),
	(9, 9, 3, 3, 2012, 2016),
	(10, 10, 3, 3, 2012, 2016),
	(11, 11, 3, 3, 2012, 2016),
	(12, 12, 3, 3, 2012, 2016),
	(13, 13, 3, 3, 2012, 2016),
	(14, 16, 3, 3, 2012, 2016),
	(15, 17, 3, 3, 2012, 2016),
	(16, 19, 3, 3, 2012, 2016),
	(17, 22, 3, 3, 2012, 2016),
	(18, 23, 3, 3, 2012, 2016);

-- Volcando estructura para tabla plan_estrategico.tb_matriz_bcg_demanda_global
CREATE TABLE IF NOT EXISTS `tb_matriz_bcg_demanda_global` (
  `id_demanda` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `valor` decimal(15,2) DEFAULT 0.00,
  PRIMARY KEY (`id_demanda`),
  KEY `idx_plan` (`id_plan`),
  KEY `idx_producto` (`id_producto`),
  CONSTRAINT `fk_bcg_demanda_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE,
  CONSTRAINT `fk_bcg_demanda_producto` FOREIGN KEY (`id_producto`) REFERENCES `tb_matriz_bcg_productos` (`id_producto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_matriz_bcg_demanda_global: ~0 rows (aproximadamente)

-- Volcando estructura para tabla plan_estrategico.tb_matriz_bcg_productos
CREATE TABLE IF NOT EXISTS `tb_matriz_bcg_productos` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_producto`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_bcg_productos_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_matriz_bcg_productos: ~42 rows (aproximadamente)
INSERT INTO `tb_matriz_bcg_productos` (`id_producto`, `id_plan`, `nombre_producto`, `orden`) VALUES
	(1, 1, 'a1', 0),
	(2, 1, 'a2', 1),
	(3, 1, 'a3', 2),
	(4, 2, 'a1', 0),
	(5, 2, 'a2', 1),
	(6, 2, 'a3', 2),
	(7, 3, 'a1', 0),
	(8, 3, 'a2', 1),
	(9, 3, 'a3', 2),
	(10, 4, 'a1', 0),
	(11, 4, 'a2', 1),
	(12, 4, 'a3', 2),
	(13, 5, 'a2', 0),
	(14, 5, 'a3', 1),
	(15, 6, 'a2', 0),
	(16, 6, 'a3', 1),
	(17, 7, 'a2', 0),
	(18, 7, 'a3', 1),
	(19, 8, 'a1', 0),
	(20, 8, 'a2', 1),
	(21, 8, 'aa3', 2),
	(22, 9, 'a1', 0),
	(23, 9, 'a2', 1),
	(24, 9, 'a3', 2),
	(25, 10, 'a1', 0),
	(26, 10, 'a2', 1),
	(27, 10, 'a3', 2),
	(28, 11, 'a1', 0),
	(29, 11, 'a2', 1),
	(30, 11, 'a3', 2),
	(31, 12, 'a1', 0),
	(32, 12, 'a2', 1),
	(33, 12, 'a3', 2),
	(34, 13, 'a1', 0),
	(35, 13, 'a2', 1),
	(36, 13, 'a3', 2),
	(37, 16, 'a1', 0),
	(38, 16, 'a2', 1),
	(39, 16, 'a3', 2),
	(40, 17, 'a1', 0),
	(41, 17, 'a2', 1),
	(42, 17, 'a3', 2),
	(43, 19, 'a1', 0),
	(44, 19, 'a2', 1),
	(45, 19, 'a3', 2),
	(46, 22, 'a1', 0),
	(47, 22, 'a2', 1),
	(48, 22, 'a3', 2),
	(49, 23, 'a1', 0),
	(50, 23, 'a2', 1),
	(51, 23, 'a3', 2);

-- Volcando estructura para tabla plan_estrategico.tb_matriz_bcg_tcm
CREATE TABLE IF NOT EXISTS `tb_matriz_bcg_tcm` (
  `id_tcm` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `periodo` varchar(50) NOT NULL,
  `valor` decimal(5,2) DEFAULT 0.00,
  PRIMARY KEY (`id_tcm`),
  KEY `idx_plan` (`id_plan`),
  KEY `idx_producto` (`id_producto`),
  CONSTRAINT `fk_bcg_tcm_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE,
  CONSTRAINT `fk_bcg_tcm_producto` FOREIGN KEY (`id_producto`) REFERENCES `tb_matriz_bcg_productos` (`id_producto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_matriz_bcg_tcm: ~0 rows (aproximadamente)

-- Volcando estructura para tabla plan_estrategico.tb_matriz_bcg_ventas
CREATE TABLE IF NOT EXISTS `tb_matriz_bcg_ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `valor` decimal(15,2) DEFAULT 0.00,
  PRIMARY KEY (`id_venta`),
  KEY `idx_plan` (`id_plan`),
  KEY `idx_producto` (`id_producto`),
  CONSTRAINT `fk_bcg_ventas_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE,
  CONSTRAINT `fk_bcg_ventas_producto` FOREIGN KEY (`id_producto`) REFERENCES `tb_matriz_bcg_productos` (`id_producto`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_matriz_bcg_ventas: ~210 rows (aproximadamente)
INSERT INTO `tb_matriz_bcg_ventas` (`id_venta`, `id_plan`, `id_producto`, `anio`, `valor`) VALUES
	(1, 1, 1, 2012, 1.00),
	(2, 1, 1, 2013, 0.00),
	(3, 1, 1, 2014, 0.00),
	(4, 1, 1, 2015, 0.00),
	(5, 1, 1, 2016, 0.00),
	(6, 1, 2, 2012, 1.00),
	(7, 1, 2, 2013, 0.00),
	(8, 1, 2, 2014, 0.00),
	(9, 1, 2, 2015, 0.00),
	(10, 1, 2, 2016, 0.00),
	(11, 1, 3, 2012, 1.00),
	(12, 1, 3, 2013, 0.00),
	(13, 1, 3, 2014, 0.00),
	(14, 1, 3, 2015, 0.00),
	(15, 1, 3, 2016, 0.00),
	(16, 2, 4, 2012, 1.00),
	(17, 2, 4, 2013, 0.00),
	(18, 2, 4, 2014, 0.00),
	(19, 2, 4, 2015, 0.00),
	(20, 2, 4, 2016, 0.00),
	(21, 2, 5, 2012, 1.00),
	(22, 2, 5, 2013, 0.00),
	(23, 2, 5, 2014, 0.00),
	(24, 2, 5, 2015, 0.00),
	(25, 2, 5, 2016, 0.00),
	(26, 2, 6, 2012, 1.00),
	(27, 2, 6, 2013, 0.00),
	(28, 2, 6, 2014, 0.00),
	(29, 2, 6, 2015, 0.00),
	(30, 2, 6, 2016, 0.00),
	(31, 3, 7, 2012, 1.00),
	(32, 3, 7, 2013, 0.00),
	(33, 3, 7, 2014, 0.00),
	(34, 3, 7, 2015, 0.00),
	(35, 3, 7, 2016, 0.00),
	(36, 3, 8, 2012, 1.00),
	(37, 3, 8, 2013, 0.00),
	(38, 3, 8, 2014, 0.00),
	(39, 3, 8, 2015, 0.00),
	(40, 3, 8, 2016, 0.00),
	(41, 3, 9, 2012, 1.00),
	(42, 3, 9, 2013, 0.00),
	(43, 3, 9, 2014, 0.00),
	(44, 3, 9, 2015, 0.00),
	(45, 3, 9, 2016, 0.00),
	(46, 4, 10, 2012, 1.00),
	(47, 4, 10, 2013, 0.00),
	(48, 4, 10, 2014, 0.00),
	(49, 4, 10, 2015, 0.00),
	(50, 4, 10, 2016, 0.00),
	(51, 4, 11, 2012, 1.00),
	(52, 4, 11, 2013, 0.00),
	(53, 4, 11, 2014, 0.00),
	(54, 4, 11, 2015, 0.00),
	(55, 4, 11, 2016, 0.00),
	(56, 4, 12, 2012, 1.00),
	(57, 4, 12, 2013, 0.00),
	(58, 4, 12, 2014, 0.00),
	(59, 4, 12, 2015, 0.00),
	(60, 4, 12, 2016, 0.00),
	(61, 5, 13, 2012, 2.00),
	(62, 5, 13, 2013, 0.00),
	(63, 5, 13, 2014, 0.00),
	(64, 5, 13, 2015, 0.00),
	(65, 5, 13, 2016, 0.00),
	(66, 5, 14, 2012, 5.00),
	(67, 5, 14, 2013, 0.00),
	(68, 5, 14, 2014, 0.00),
	(69, 5, 14, 2015, 0.00),
	(70, 5, 14, 2016, 0.00),
	(71, 6, 15, 2012, 2.00),
	(72, 6, 15, 2013, 0.00),
	(73, 6, 15, 2014, 0.00),
	(74, 6, 15, 2015, 0.00),
	(75, 6, 15, 2016, 0.00),
	(76, 6, 16, 2012, 5.00),
	(77, 6, 16, 2013, 0.00),
	(78, 6, 16, 2014, 0.00),
	(79, 6, 16, 2015, 0.00),
	(80, 6, 16, 2016, 0.00),
	(81, 7, 17, 2012, 2.00),
	(82, 7, 17, 2013, 0.00),
	(83, 7, 17, 2014, 0.00),
	(84, 7, 17, 2015, 0.00),
	(85, 7, 17, 2016, 0.00),
	(86, 7, 18, 2012, 5.00),
	(87, 7, 18, 2013, 0.00),
	(88, 7, 18, 2014, 0.00),
	(89, 7, 18, 2015, 0.00),
	(90, 7, 18, 2016, 0.00),
	(91, 8, 19, 2012, 5.00),
	(92, 8, 19, 2013, 0.00),
	(93, 8, 19, 2014, 0.00),
	(94, 8, 19, 2015, 0.00),
	(95, 8, 19, 2016, 0.00),
	(96, 8, 20, 2012, 3.00),
	(97, 8, 20, 2013, 0.00),
	(98, 8, 20, 2014, 0.00),
	(99, 8, 20, 2015, 0.00),
	(100, 8, 20, 2016, 0.00),
	(101, 8, 21, 2012, 4.00),
	(102, 8, 21, 2013, 0.00),
	(103, 8, 21, 2014, 0.00),
	(104, 8, 21, 2015, 0.00),
	(105, 8, 21, 2016, 0.00),
	(106, 9, 22, 2012, 1.00),
	(107, 9, 22, 2013, 0.00),
	(108, 9, 22, 2014, 0.00),
	(109, 9, 22, 2015, 0.00),
	(110, 9, 22, 2016, 0.00),
	(111, 9, 23, 2012, 1.00),
	(112, 9, 23, 2013, 0.00),
	(113, 9, 23, 2014, 0.00),
	(114, 9, 23, 2015, 0.00),
	(115, 9, 23, 2016, 0.00),
	(116, 9, 24, 2012, 1.00),
	(117, 9, 24, 2013, 0.00),
	(118, 9, 24, 2014, 0.00),
	(119, 9, 24, 2015, 0.00),
	(120, 9, 24, 2016, 0.00),
	(121, 10, 25, 2012, 1.00),
	(122, 10, 25, 2013, 0.00),
	(123, 10, 25, 2014, 0.00),
	(124, 10, 25, 2015, 0.00),
	(125, 10, 25, 2016, 0.00),
	(126, 10, 26, 2012, 1.00),
	(127, 10, 26, 2013, 0.00),
	(128, 10, 26, 2014, 0.00),
	(129, 10, 26, 2015, 0.00),
	(130, 10, 26, 2016, 0.00),
	(131, 10, 27, 2012, 1.00),
	(132, 10, 27, 2013, 0.00),
	(133, 10, 27, 2014, 0.00),
	(134, 10, 27, 2015, 0.00),
	(135, 10, 27, 2016, 0.00),
	(136, 11, 28, 2012, 1.00),
	(137, 11, 28, 2013, 0.00),
	(138, 11, 28, 2014, 0.00),
	(139, 11, 28, 2015, 0.00),
	(140, 11, 28, 2016, 0.00),
	(141, 11, 29, 2012, 3.00),
	(142, 11, 29, 2013, 0.00),
	(143, 11, 29, 2014, 0.00),
	(144, 11, 29, 2015, 0.00),
	(145, 11, 29, 2016, 0.00),
	(146, 11, 30, 2012, 4.00),
	(147, 11, 30, 2013, 0.00),
	(148, 11, 30, 2014, 0.00),
	(149, 11, 30, 2015, 0.00),
	(150, 11, 30, 2016, 0.00),
	(151, 12, 31, 2012, 2.00),
	(152, 12, 31, 2013, 0.00),
	(153, 12, 31, 2014, 0.00),
	(154, 12, 31, 2015, 0.00),
	(155, 12, 31, 2016, 0.00),
	(156, 12, 32, 2012, 2.00),
	(157, 12, 32, 2013, 0.00),
	(158, 12, 32, 2014, 0.00),
	(159, 12, 32, 2015, 0.00),
	(160, 12, 32, 2016, 0.00),
	(161, 12, 33, 2012, 3.00),
	(162, 12, 33, 2013, 0.00),
	(163, 12, 33, 2014, 0.00),
	(164, 12, 33, 2015, 0.00),
	(165, 12, 33, 2016, 0.00),
	(166, 13, 34, 2012, 1.00),
	(167, 13, 34, 2013, 0.00),
	(168, 13, 34, 2014, 0.00),
	(169, 13, 34, 2015, 0.00),
	(170, 13, 34, 2016, 0.00),
	(171, 13, 35, 2012, 1.00),
	(172, 13, 35, 2013, 0.00),
	(173, 13, 35, 2014, 0.00),
	(174, 13, 35, 2015, 0.00),
	(175, 13, 35, 2016, 0.00),
	(176, 13, 36, 2012, 1.00),
	(177, 13, 36, 2013, 0.00),
	(178, 13, 36, 2014, 0.00),
	(179, 13, 36, 2015, 0.00),
	(180, 13, 36, 2016, 0.00),
	(181, 16, 37, 2012, 1.00),
	(182, 16, 37, 2013, 0.00),
	(183, 16, 37, 2014, 0.00),
	(184, 16, 37, 2015, 0.00),
	(185, 16, 37, 2016, 0.00),
	(186, 16, 38, 2012, 1.00),
	(187, 16, 38, 2013, 0.00),
	(188, 16, 38, 2014, 0.00),
	(189, 16, 38, 2015, 0.00),
	(190, 16, 38, 2016, 0.00),
	(191, 16, 39, 2012, 1.00),
	(192, 16, 39, 2013, 0.00),
	(193, 16, 39, 2014, 0.00),
	(194, 16, 39, 2015, 0.00),
	(195, 16, 39, 2016, 0.00),
	(196, 17, 40, 2012, 1.00),
	(197, 17, 40, 2013, 0.00),
	(198, 17, 40, 2014, 0.00),
	(199, 17, 40, 2015, 0.00),
	(200, 17, 40, 2016, 0.00),
	(201, 17, 41, 2012, 1.00),
	(202, 17, 41, 2013, 0.00),
	(203, 17, 41, 2014, 0.00),
	(204, 17, 41, 2015, 0.00),
	(205, 17, 41, 2016, 0.00),
	(206, 17, 42, 2012, 1.00),
	(207, 17, 42, 2013, 0.00),
	(208, 17, 42, 2014, 0.00),
	(209, 17, 42, 2015, 0.00),
	(210, 17, 42, 2016, 0.00),
	(211, 19, 43, 2012, 1.00),
	(212, 19, 43, 2013, 0.00),
	(213, 19, 43, 2014, 0.00),
	(214, 19, 43, 2015, 0.00),
	(215, 19, 43, 2016, 0.00),
	(216, 19, 44, 2012, 1.00),
	(217, 19, 44, 2013, 0.00),
	(218, 19, 44, 2014, 0.00),
	(219, 19, 44, 2015, 0.00),
	(220, 19, 44, 2016, 0.00),
	(221, 19, 45, 2012, 1.00),
	(222, 19, 45, 2013, 0.00),
	(223, 19, 45, 2014, 0.00),
	(224, 19, 45, 2015, 0.00),
	(225, 19, 45, 2016, 0.00),
	(226, 22, 46, 2012, 1.00),
	(227, 22, 46, 2013, 2.00),
	(228, 22, 46, 2014, 0.00),
	(229, 22, 46, 2015, 0.00),
	(230, 22, 46, 2016, 0.00),
	(231, 22, 47, 2012, 1.00),
	(232, 22, 47, 2013, 2.00),
	(233, 22, 47, 2014, 0.00),
	(234, 22, 47, 2015, 0.00),
	(235, 22, 47, 2016, 0.00),
	(236, 22, 48, 2012, 2.00),
	(237, 22, 48, 2013, 2.00),
	(238, 22, 48, 2014, 0.00),
	(239, 22, 48, 2015, 0.00),
	(240, 22, 48, 2016, 0.00),
	(241, 23, 49, 2012, 2.00),
	(242, 23, 49, 2013, 0.00),
	(243, 23, 49, 2014, 0.00),
	(244, 23, 49, 2015, 0.00),
	(245, 23, 49, 2016, 0.00),
	(246, 23, 50, 2012, 2.00),
	(247, 23, 50, 2013, 3.00),
	(248, 23, 50, 2014, 0.00),
	(249, 23, 50, 2015, 0.00),
	(250, 23, 50, 2016, 0.00),
	(251, 23, 51, 2012, 3.00),
	(252, 23, 51, 2013, 0.00),
	(253, 23, 51, 2014, 0.00),
	(254, 23, 51, 2015, 0.00),
	(255, 23, 51, 2016, 0.00);

-- Volcando estructura para tabla plan_estrategico.tb_matriz_came
CREATE TABLE IF NOT EXISTS `tb_matriz_came` (
  `id_came` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `corregir` text DEFAULT NULL,
  `afrontar` text DEFAULT NULL,
  `mantener` text DEFAULT NULL,
  `explotar` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_came`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_came_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_matriz_came: ~17 rows (aproximadamente)
INSERT INTO `tb_matriz_came` (`id_came`, `id_plan`, `corregir`, `afrontar`, `mantener`, `explotar`, `fecha_creacion`) VALUES
	(1, 1, 'a1\n\na2\n\na3\n\ne4', 'e5\n\ne6\n\ne7', 'e8\n\ne9\n\ne10', 'e11\n\ne12\n\ne13', '2025-06-18 23:21:14'),
	(2, 2, 'a1\n\na2\n\na3\n\ne4', 'e5\n\ne6\n\ne7', 'e8\n\ne9\n\ne10', 'e11\n\ne12\n\ne13', '2025-06-18 23:23:15'),
	(3, 3, 'a1\n\na2\n\na3\n\ne4', 'e5\n\ne6\n\ne7', 'e8\n\ne9\n\ne10', 'e11\n\ne12\n\ne13', '2025-06-18 23:23:28'),
	(4, 4, 'a1\n\na2\n\na3\n\ne4', 'e5\n\ne6\n\ne7', 'e8\n\ne9\n\ne10', 'e11\n\ne12\n\ne13', '2025-06-18 23:25:28'),
	(5, 5, '', '', '', '', '2025-06-18 23:35:35'),
	(6, 6, '', '', '', '', '2025-06-18 23:35:45'),
	(7, 7, 'a', 'ds', 'dsd', 'dsds', '2025-06-18 23:35:57'),
	(8, 8, 'aaaaa\n\naaaaaaassssss', 'sssssssss', 'eeeeeee\n\neeeeeeeee', 'eeeeeeeeeeee', '2025-06-18 23:52:18'),
	(9, 9, 'dsadsa\n\ndsadsa', 'dsad\n\nddd', 'dsad\n\ndsadsa', 'dsd\n\ndsadsadsa', '2025-06-19 01:06:38'),
	(10, 10, 'dsadsas\n\ndsadsa', 'dsadsss\n\nddd', 'dsad\n\ndsadsa', 'dsd\n\ndsadsadsa', '2025-06-19 01:45:27'),
	(11, 11, 'corrigiendo\n\nt1', 't2\n\nt3', 't4\n\nt5', 't1\n\nt2', '2025-06-19 09:00:09'),
	(12, 12, 't1\n\nt2', 't3\n\nt5', 't6\n\nt3', 't7t8\n\nt8', '2025-06-19 09:16:16'),
	(13, 13, 't1\n\nt2', 't3\n\nt5', 't6\n\nt3', 't7t8\n\nt8', '2025-06-19 09:41:01'),
	(14, 14, 'Estrategia corregir 1\n\nEstrategia corregir 2', 'Estrategia afrontar 1\n\nEstrategia afrontar 2', 'Estrategia mantener 1\n\nEstrategia mantener 2', 'Estrategia explotar 1\n\nEstrategia explotar 2', '2025-06-19 09:56:14'),
	(15, 15, 'Estrategia corregir 1\n\nEstrategia corregir 2', 'Estrategia afrontar 1\n\nEstrategia afrontar 2', 'Estrategia mantener 1\n\nEstrategia mantener 2', 'Estrategia explotar 1\n\nEstrategia explotar 2', '2025-06-19 09:57:03'),
	(16, 16, 'debilidad corregida\n\ndebilidad corregida', 'Amenazas corregida\n\nAmenazas corregida', 'fortaleza corregida\n\nfortaleza corregida2', 'oportunidad corregida\n\noportunidad corregida2', '2025-06-19 10:03:07'),
	(17, 17, 'debilidad valorcorregicod\n\ndebilidad valors22', 'oportundiade nuevo222\n\namenaza poter23232', 'fprtalza valor232323\n\nfortaleza bcccgg43535353', 'oportundiade neuv2112321\n\noprotunidade poter5556', '2025-06-19 10:17:58'),
	(18, 19, 'adsa\n\ndsada', 'dsd\n\nsdsds', 'dsdsd\n\nsdsds', 'dsdsds\n\ndsdsdsds', '2025-06-19 12:14:32'),
	(19, 22, 't1\n\nt2', 't3\n\nt4', 't5\n\nt6', 't7\n\nt9', '2025-06-19 12:32:46'),
	(20, 23, 'dasd\n\nadsad', 'dsa\n\ndsadas', 'dsad\n\ndasdasdas', 'dsadas\n\ndasdas', '2025-06-19 16:08:34');

-- Volcando estructura para tabla plan_estrategico.tb_matriz_came_individuales
CREATE TABLE IF NOT EXISTS `tb_matriz_came_individuales` (
  `id_estrategia_individual` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `tipo` enum('corregir','afrontar','mantener','explotar') NOT NULL,
  `indice_elemento` int(11) NOT NULL,
  `estrategia` text NOT NULL,
  PRIMARY KEY (`id_estrategia_individual`),
  KEY `idx_plan` (`id_plan`),
  KEY `idx_tipo_indice` (`tipo`,`indice_elemento`),
  CONSTRAINT `fk_came_individuales_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_matriz_came_individuales: ~0 rows (aproximadamente)

-- Volcando estructura para tabla plan_estrategico.tb_objetivos_estrategicos
CREATE TABLE IF NOT EXISTS `tb_objetivos_estrategicos` (
  `id_objetivo` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `objetivo` text NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `prioridad` enum('alta','media','baja') DEFAULT 'media',
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_objetivo`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_objetivos_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_objetivos_estrategicos: ~21 rows (aproximadamente)
INSERT INTO `tb_objetivos_estrategicos` (`id_objetivo`, `id_plan`, `objetivo`, `categoria`, `prioridad`, `orden`) VALUES
	(1, 9, '123', 'general', 'alta', 0),
	(2, 9, '3321', 'especifico', 'media', 0),
	(3, 9, '321', 'especifico', 'media', 1),
	(4, 10, '123', 'general', 'alta', 0),
	(5, 10, '3321', 'especifico', 'media', 0),
	(6, 10, '321', 'especifico', 'media', 1),
	(7, 11, 'objetivosss', 'general', 'alta', 0),
	(8, 11, 'objetivo1', 'especifico', 'media', 0),
	(9, 11, 'objetivo12', 'especifico', 'media', 1),
	(10, 12, 'defina objetivo', 'general', 'alta', 0),
	(11, 12, 'primer objetivo', 'especifico', 'media', 0),
	(12, 12, 'segundo objetivov', 'especifico', 'media', 1),
	(13, 13, 'defina objetivo', 'general', 'alta', 0),
	(14, 13, 'primer objetivo', 'especifico', 'media', 0),
	(15, 13, 'segundo objetivov', 'especifico', 'media', 1),
	(16, 16, 'obje general', 'general', 'alta', 0),
	(17, 16, 'obje especifico', 'especifico', 'media', 0),
	(18, 16, 'obj especicfcoo', 'especifico', 'media', 1),
	(19, 17, 'estrategico', 'general', 'alta', 0),
	(20, 17, 'objetivoi especifico', 'especifico', 'media', 0),
	(21, 17, 'objetivo especifoc', 'especifico', 'media', 1),
	(22, 19, 'obj genre', 'general', 'alta', 0),
	(23, 19, 'obj espcefifico', 'especifico', 'media', 0),
	(24, 19, 'obj especifico', 'especifico', 'media', 1),
	(25, 20, 'Objetivo general 1', 'general', 'alta', 0),
	(26, 21, 'Objetivo general 1', 'general', 'alta', 0),
	(27, 22, 'adadada', 'general', 'alta', 0),
	(28, 22, 'dedede', 'especifico', 'media', 0),
	(29, 22, 'dedede', 'especifico', 'media', 1),
	(30, 23, 'dsad', 'general', 'alta', 0),
	(31, 23, 'asdas', 'especifico', 'media', 0),
	(32, 23, 'dasdas', 'especifico', 'media', 1);

-- Volcando estructura para tabla plan_estrategico.tb_oportunidades
CREATE TABLE IF NOT EXISTS `tb_oportunidades` (
  `id_oportunidad` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `origen` enum('pest','porter','manual') DEFAULT 'manual',
  `orden` int(11) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_oportunidad`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_oportunidades_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_oportunidades: ~11 rows (aproximadamente)
INSERT INTO `tb_oportunidades` (`id_oportunidad`, `id_plan`, `descripcion`, `origen`, `orden`, `fecha_creacion`) VALUES
	(1, 9, 'oportunidades nuevo', 'pest', 0, '2025-06-19 14:31:51'),
	(2, 10, 'oportunidades nuevo', 'pest', 0, '2025-06-19 14:31:51'),
	(3, 11, 'oportunidades de l peste', 'pest', 0, '2025-06-19 14:31:51'),
	(4, 12, 'oportunidades nuevas', 'pest', 0, '2025-06-19 14:31:51'),
	(5, 13, 'oportunidades nuevas', 'pest', 0, '2025-06-19 14:41:01'),
	(6, 14, 'Oportunidad PEST 1', 'pest', 0, '2025-06-19 14:56:14'),
	(7, 14, 'Oportunidad PEST 2', 'pest', 1, '2025-06-19 14:56:14'),
	(8, 15, 'Oportunidad PEST 1', 'pest', 0, '2025-06-19 14:57:03'),
	(9, 15, 'Oportunidad PEST 2', 'pest', 1, '2025-06-19 14:57:03'),
	(10, 16, 'oportunidades indetificadasd', 'pest', 0, '2025-06-19 15:03:07'),
	(11, 17, 'oportundiade neuv', 'pest', 0, '2025-06-19 15:17:58'),
	(12, 19, 'opoprtunidade nueva', 'pest', 0, '2025-06-19 17:14:32'),
	(13, 22, 'oportunidades nuevo', 'pest', 0, '2025-06-19 17:32:46'),
	(14, 23, 'dsadwdwd', 'pest', 0, '2025-06-19 21:08:34');

-- Volcando estructura para tabla plan_estrategico.tb_plan_estrategico
CREATE TABLE IF NOT EXISTS `tb_plan_estrategico` (
  `id_plan` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `nombre_plan` varchar(255) NOT NULL,
  `empresa` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_modificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` enum('borrador','completado','archivado') DEFAULT 'borrador',
  `logo_empresa` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_plan`),
  KEY `idx_usuario` (`id_usuario`),
  CONSTRAINT `fk_plan_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuario` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_plan_estrategico: ~18 rows (aproximadamente)
INSERT INTO `tb_plan_estrategico` (`id_plan`, `id_usuario`, `nombre_plan`, `empresa`, `descripcion`, `fecha_creacion`, `fecha_modificacion`, `estado`, `logo_empresa`) VALUES
	(1, 4, 'Plan Estratégico', 'Mi Empresa', '', '2025-06-18 23:21:14', '2025-06-18 23:21:14', 'completado', NULL),
	(2, 4, 'Plan Estratégico', 'Mi Empresa', '', '2025-06-18 23:23:15', '2025-06-18 23:23:15', 'completado', NULL),
	(3, 4, 'Plan Estratégico', 'Mi Empresa', '', '2025-06-18 23:23:28', '2025-06-18 23:23:28', 'completado', NULL),
	(4, 4, 'Plan Estratégico', 'Mi Empresa', '', '2025-06-18 23:25:28', '2025-06-18 23:25:28', 'completado', NULL),
	(5, 4, 'Plan Estratégico', 'Mi Empresa', '', '2025-06-18 23:35:35', '2025-06-18 23:35:35', 'completado', NULL),
	(6, 4, 'Plan Estratégico', 'Mi Empresa', '', '2025-06-18 23:35:45', '2025-06-18 23:35:45', 'completado', NULL),
	(7, 4, 'Plan Estratégico', 'Mi Empresa', '', '2025-06-18 23:35:57', '2025-06-18 23:35:57', 'completado', NULL),
	(8, 4, 'Plan Estratégico', 'Mi Empresa', '', '2025-06-18 23:52:18', '2025-06-18 23:52:18', 'completado', NULL),
	(9, 4, 'dsdsds', 'ddsdsd', 'sdsdsds', '2025-06-19 01:06:38', '2025-06-19 01:06:38', 'completado', NULL),
	(10, 4, 'aaaaaaaaaa', 'aasssss', 'sssssssss', '2025-06-19 01:45:27', '2025-06-19 01:45:27', 'completado', NULL),
	(11, 4, 'plan2025', 'nombre', 'nombresito', '2025-06-19 09:00:09', '2025-06-19 09:00:09', 'completado', NULL),
	(12, 4, 'plan2025', 'nombrecito', 'descrito', '2025-06-19 09:16:16', '2025-06-19 09:16:16', 'completado', NULL),
	(13, 4, 'plannuevo', 'empresanueva', 'descirpcion nueva', '2025-06-19 09:41:01', '2025-06-19 09:41:01', 'completado', NULL),
	(14, 1, 'Plan Test Debug', 'Empresa Debug', 'Plan para debugging detallado', '2025-06-19 09:56:14', '2025-06-19 09:56:14', 'completado', NULL),
	(15, 1, 'Plan Test Debug', 'Empresa Debug', 'Plan para debugging detallado', '2025-06-19 09:57:03', '2025-06-19 09:57:03', 'completado', NULL),
	(16, 4, 'plan 2025', 'nombrecito', 'nombredescripcion', '2025-06-19 10:03:07', '2025-06-19 13:22:38', 'completado', 'public/assets/logos/logo_plan_16.png'),
	(17, 4, 'plan', 'empresa', 'description', '2025-06-19 10:17:58', '2025-06-19 10:17:58', 'completado', NULL),
	(18, 4, 'Plan Estratégico', 'Mi Empresa', '', '2025-06-19 10:38:34', '2025-06-19 10:38:34', 'completado', NULL),
	(19, 4, 'plan 2025', 'Perusac', 'perusac', '2025-06-19 12:14:32', '2025-06-19 12:14:32', 'completado', NULL),
	(20, 1, 'Plan Test Valores', 'Empresa Test', 'Descripción test', '2025-06-19 12:19:14', '2025-06-19 12:19:14', 'completado', NULL),
	(21, 1, 'Plan Test Valores', 'Empresa Test', 'Plan de prueba para verificar valores', '2025-06-19 12:27:38', '2025-06-19 12:27:38', 'completado', NULL),
	(22, 4, 'plan2025', 'PRODUCTOR', 'PRODOSOADSA', '2025-06-19 12:32:46', '2025-06-19 13:30:29', 'completado', 'public/assets/logos/logo_plan_22.png'),
	(23, 4, 'plan 25', 'probando', 'testing', '2025-06-19 16:08:34', '2025-06-19 16:08:34', 'completado', NULL);

-- Volcando estructura para tabla plan_estrategico.tb_respuestas_pest
CREATE TABLE IF NOT EXISTS `tb_respuestas_pest` (
  `id_respuesta` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL DEFAULT 1,
  `factor` varchar(50) NOT NULL,
  `respuesta` text NOT NULL,
  `impacto` varchar(20) DEFAULT 'medio',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_respuesta`),
  KEY `id_empresa` (`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla plan_estrategico.tb_respuestas_pest: ~0 rows (aproximadamente)

-- Volcando estructura para tabla plan_estrategico.tb_resumen_ejecutivo
CREATE TABLE IF NOT EXISTS `tb_resumen_ejecutivo` (
  `id_resumen` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `emprendedores_promotores` text DEFAULT NULL,
  `identificacion_estrategica` text DEFAULT NULL,
  `conclusiones` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_resumen`),
  KEY `id_plan` (`id_plan`),
  CONSTRAINT `tb_resumen_ejecutivo_ibfk_1` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_resumen_ejecutivo: ~6 rows (aproximadamente)
INSERT INTO `tb_resumen_ejecutivo` (`id_resumen`, `id_plan`, `emprendedores_promotores`, `identificacion_estrategica`, `conclusiones`, `fecha_creacion`) VALUES
	(1, 10, 'dsadsa', 'ddddddddddd', 'dsadsadsa', '2025-06-19 01:45:27'),
	(2, 11, 'yoyoyoyo111111111', 'dsadsadas', 'ddddddddddddddddd', '2025-06-19 09:00:09'),
	(3, 12, 'maicol', 'identificacion', 'conclusion', '2025-06-19 09:16:16'),
	(4, 13, 'maicol', 'identificacion', 'conclusion', '2025-06-19 09:41:01'),
	(5, 16, 'yoloyolito', 'uen identificacion', 'conclusiones', '2025-06-19 10:03:07'),
	(6, 17, 'yo lo ', ' nada q ver', 'concluimos', '2025-06-19 10:17:58'),
	(7, 19, 'dsdsdsdwwwwww', 'swswswsw', 'ssssssssssssssssssssssssssssssssw', '2025-06-19 12:14:32'),
	(8, 20, 'Test emprendedores', 'Test identificación', 'Test conclusiones', '2025-06-19 12:19:14'),
	(9, 21, '', 'Identificación estratégica de prueba', 'Conclusiones del plan estratégico', '2025-06-19 12:27:38'),
	(10, 22, 'tqqqqqqqqqqqqqq', 'teeeeeeeeeetqtqtq', 'frfrfrfrfr', '2025-06-19 12:32:46'),
	(11, 23, 'dsadsa', 'dsada', 'dsadas', '2025-06-19 16:08:34');

-- Volcando estructura para tabla plan_estrategico.tb_sintesis_estrategias
CREATE TABLE IF NOT EXISTS `tb_sintesis_estrategias` (
  `id_sintesis` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL DEFAULT 1,
  `relacion` varchar(10) NOT NULL,
  `tipologia_estrategia` varchar(100) NOT NULL,
  `puntuacion` int(11) DEFAULT 0,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_sintesis`),
  UNIQUE KEY `unique_empresa_relacion` (`id_empresa`,`relacion`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla plan_estrategico.tb_sintesis_estrategias: ~4 rows (aproximadamente)
INSERT INTO `tb_sintesis_estrategias` (`id_sintesis`, `id_empresa`, `relacion`, `tipologia_estrategia`, `puntuacion`, `descripcion`, `fecha_creacion`, `fecha_actualizacion`) VALUES
	(1, 1, 'FO', 'Estrategia Ofensiva', 8, 'Usar fortalezas para aprovechar oportunidades', '2025-06-19 14:56:14', '2025-06-19 14:56:14'),
	(2, 1, 'AF', 'Estrategia Defensiva', 6, 'Usar fortalezas para defenderse de amenazas', '2025-06-19 14:56:14', '2025-06-19 14:56:14'),
	(3, 1, 'OD', 'Estrategia de Supervivencia', 5, 'Superar debilidades aprovechando oportunidades', '2025-06-19 14:56:14', '2025-06-19 14:56:14'),
	(4, 1, 'AD', 'Estrategia de Reorientación', 4, 'Reestructuración para afrontar debilidades y amenazas', '2025-06-19 14:56:14', '2025-06-19 14:56:14');

-- Volcando estructura para tabla plan_estrategico.tb_uen
CREATE TABLE IF NOT EXISTS `tb_uen` (
  `id_uen` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `uen_descripcion` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_uen`),
  KEY `fk_uen_plan` (`id_plan`),
  CONSTRAINT `fk_uen_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla plan_estrategico.tb_uen: ~2 rows (aproximadamente)
INSERT INTO `tb_uen` (`id_uen`, `id_plan`, `uen_descripcion`, `fecha_creacion`) VALUES
	(3, 16, 'Esta es la unidad estratégica de negocio que yo escribí manualmente en el wizard para definir el enfoque estratégico de mi empresa.', '2025-06-19 16:44:10'),
	(4, 16, 'Unidad estratégica enfocada en el desarrollo sostenible y la innovación tecnológica para lograr los objetivos generales y específicos definidos en el plan estratégico, maximizando el valor para los stakeholders.', '2025-06-19 17:07:08'),
	(5, 19, 'uen es lo mejor', '2025-06-19 17:14:32'),
	(6, 20, 'UEN Test', '2025-06-19 17:19:14'),
	(7, 21, 'Unidad estratégica de prueba', '2025-06-19 17:27:38'),
	(8, 22, 'dawssssssssssss', '2025-06-19 17:32:46'),
	(9, 23, 'dsadasdas', '2025-06-19 21:08:34');

-- Volcando estructura para tabla plan_estrategico.tb_usuario
CREATE TABLE IF NOT EXISTS `tb_usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_usuario: ~2 rows (aproximadamente)
INSERT INTO `tb_usuario` (`id_usuario`, `nombre`, `apellido`, `usuario`, `correo`, `password`, `fecha_creacion`) VALUES
	(1, 'Admin', 'Sistema', 'admin', 'admin@sistema.com', '$2y$10$example', '2025-06-18 22:03:28'),
	(2, 'Usuario', 'Demo', 'demo', 'demo@demo.com', '$2y$10$example', '2025-06-18 22:03:28'),
	(4, 'nuevo', 'mario', 'admin21', 'admin@g.com', '$2y$10$ZVezLoagSjnHViHH8jnznuYp3RUAXXEcOsJKcP6rh/MhcIjXBfM1K', '2025-06-18 22:11:46');

-- Volcando estructura para tabla plan_estrategico.tb_valores
CREATE TABLE IF NOT EXISTS `tb_valores` (
  `id_valor` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `valor` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_valor`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_valores_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_valores: ~16 rows (aproximadamente)
INSERT INTO `tb_valores` (`id_valor`, `id_plan`, `valor`, `descripcion`, `orden`) VALUES
	(1, 5, '', '', 0),
	(2, 6, '', '', 0),
	(3, 7, '', '', 0),
	(4, 8, '', '', 0),
	(5, 9, '', '', 0),
	(6, 10, '', '', 0),
	(7, 11, '', '', 0),
	(8, 12, '', '', 0),
	(9, 13, '', '', 0),
	(10, 14, 'Precisión', 'Identificar problemas con exactitud', 0),
	(11, 14, 'Eficiencia', 'Resolver problemas rápidamente', 1),
	(12, 15, 'Precisión', 'Identificar problemas con exactitud', 0),
	(13, 15, 'Eficiencia', 'Resolver problemas rápidamente', 1),
	(14, 16, '', '', 0),
	(15, 17, '', '', 0),
	(16, 19, '', '', 0),
	(17, 20, 'Innovación', '', 0),
	(18, 20, 'Excelencia', '', 1),
	(19, 20, 'Responsabilidad Social', '', 2),
	(20, 20, 'Trabajo en Equipo', '', 3),
	(21, 20, 'Transparencia', '', 4),
	(22, 21, 'Honestidad', '', 0),
	(23, 21, 'Responsabilidad', '', 1),
	(24, 21, 'Calidad', '', 2),
	(25, 21, 'Innovación', '', 3),
	(26, 21, 'Compromiso', '', 4),
	(27, 22, 'haaaaaaaa', '', 0),
	(28, 23, 'asdasdsa', '', 0);

-- Volcando estructura para tabla plan_estrategico.tb_vision_mision
CREATE TABLE IF NOT EXISTS `tb_vision_mision` (
  `id_vision_mision` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `vision` text NOT NULL,
  `mision` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_vision_mision`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_vision_mision_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_vision_mision: ~13 rows (aproximadamente)
INSERT INTO `tb_vision_mision` (`id_vision_mision`, `id_plan`, `vision`, `mision`, `fecha_creacion`) VALUES
	(1, 5, 'asdasd', 'asdasd', '2025-06-18 23:35:35'),
	(2, 6, 'asdasd', 'asdasd', '2025-06-18 23:35:45'),
	(3, 7, 'asdasd', 'asdasd', '2025-06-18 23:35:57'),
	(4, 8, 'z1z1', 'z1z1', '2025-06-18 23:52:18'),
	(5, 9, 'aaaaaaaa', 'aassssssssssss', '2025-06-19 01:06:38'),
	(6, 10, 'aaaaaaaaddddddd', 'aassssssssssssddddddddsds', '2025-06-19 01:45:27'),
	(7, 11, 'visiones', 'misiones', '2025-06-19 09:00:09'),
	(8, 12, 'visiones', 'misiones', '2025-06-19 09:16:16'),
	(9, 13, 'visiones', 'misiones', '2025-06-19 09:41:01'),
	(10, 14, 'Ser una empresa líder en debugging', 'Identificar y resolver errores de guardado', '2025-06-19 09:56:14'),
	(11, 15, 'Ser una empresa líder en debugging', 'Identificar y resolver errores de guardado', '2025-06-19 09:57:03'),
	(12, 16, 'vision', 'mision', '2025-06-19 10:03:07'),
	(13, 17, 'visones', 'misiines', '2025-06-19 10:17:58'),
	(14, 19, 'visionero', 'mision2', '2025-06-19 12:14:32'),
	(15, 20, 'Visión test', 'Misión test', '2025-06-19 12:19:14'),
	(16, 21, 'Ser una empresa líder en el mercado', 'Brindar productos y servicios de calidad', '2025-06-19 12:27:38'),
	(17, 22, 'vision ', 'maision', '2025-06-19 12:32:46'),
	(18, 23, 'mision d', 'sivision', '2025-06-19 16:08:34');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
