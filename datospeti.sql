-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.7.0.6850
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para appplanestrategico
CREATE DATABASE IF NOT EXISTS `appplanestrategico` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `appplanestrategico`;

-- Volcando estructura para tabla appplanestrategico.tb_amenazas
CREATE TABLE IF NOT EXISTS `tb_amenazas` (
  `id_amenaza` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_amenaza`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_amenazas_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_amenazas: ~2 rows (aproximadamente)
INSERT INTO `tb_amenazas` (`id_amenaza`, `id_empresa`, `descripcion`, `fecha_creacion`) VALUES
	(1, 1, 'Entrada de competidores internacionales', '2025-06-12 11:30:03'),
	(2, 1, 'Cambios en regulaciones del sector', '2025-06-12 11:30:03');

-- Volcando estructura para tabla appplanestrategico.tb_cadena_valor
CREATE TABLE IF NOT EXISTS `tb_cadena_valor` (
  `id_evaluacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `fecha_evaluacion` datetime DEFAULT current_timestamp(),
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
  `q11` tinyint(1) DEFAULT NULL,
  `q12` tinyint(1) DEFAULT NULL,
  `q13` tinyint(1) DEFAULT NULL,
  `q14` tinyint(1) DEFAULT NULL,
  `q15` tinyint(1) DEFAULT NULL,
  `q16` tinyint(1) DEFAULT NULL,
  `q17` tinyint(1) DEFAULT NULL,
  `q18` tinyint(1) DEFAULT NULL,
  `q19` tinyint(1) DEFAULT NULL,
  `q20` tinyint(1) DEFAULT NULL,
  `q21` tinyint(1) DEFAULT NULL,
  `q22` tinyint(1) DEFAULT NULL,
  `q23` tinyint(1) DEFAULT NULL,
  `q24` tinyint(1) DEFAULT NULL,
  `q25` tinyint(1) DEFAULT NULL,
  `resultado` text NOT NULL,
  `porcentaje_resultado` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id_evaluacion`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_evaluacion_cadena_valor_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_cadena_valor: ~0 rows (aproximadamente)

-- Volcando estructura para tabla appplanestrategico.tb_came
CREATE TABLE IF NOT EXISTS `tb_came` (
  `id_came` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `tipo` enum('C','A','M','E') NOT NULL,
  `id_factor` int(11) NOT NULL,
  `descripcion_accion` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_came`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_came_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_came: ~0 rows (aproximadamente)

-- Volcando estructura para tabla appplanestrategico.tb_debilidades
CREATE TABLE IF NOT EXISTS `tb_debilidades` (
  `id_debilidad` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_debilidad`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_debilidades_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_debilidades: ~2 rows (aproximadamente)
INSERT INTO `tb_debilidades` (`id_debilidad`, `id_empresa`, `descripcion`, `fecha_creacion`) VALUES
	(1, 1, 'Infraestructura tecnológica obsoleta', '2025-06-12 11:30:03'),
	(2, 1, 'Falta de presencia en redes sociales', '2025-06-12 11:30:03');

-- Volcando estructura para tabla appplanestrategico.tb_empresa
CREATE TABLE IF NOT EXISTS `tb_empresa` (
  `id_empresa` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre_empresa` varchar(150) DEFAULT NULL,
  `mision` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id_empresa`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `tb_empresa_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_empresa: ~1 rows (aproximadamente)
INSERT INTO `tb_empresa` (`id_empresa`, `id_usuario`, `nombre_empresa`, `mision`, `vision`, `fecha_creacion`, `descripcion`) VALUES
	(1, 1, 'Empresa XYZ', 'Nuestra misión es liderar el mercado', 'Ser la empresa más innovadora', '2025-04-24', 'Descripción de la empresa XYZ');

-- Volcando estructura para tabla appplanestrategico.tb_fortalezas
CREATE TABLE IF NOT EXISTS `tb_fortalezas` (
  `id_fortaleza` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_fortaleza`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_fortalezas_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_fortalezas: ~2 rows (aproximadamente)
INSERT INTO `tb_fortalezas` (`id_fortaleza`, `id_empresa`, `descripcion`, `fecha_creacion`) VALUES
	(1, 1, 'Equipo altamente capacitado', '2025-06-12 11:30:03'),
	(2, 1, 'Buena reputación en el mercado', '2025-06-12 11:30:03');

-- Volcando estructura para tabla appplanestrategico.tb_fuerza_porter
CREATE TABLE IF NOT EXISTS `tb_fuerza_porter` (
  `id_fp` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `fecha_analisis` datetime DEFAULT current_timestamp(),
  `q0` tinyint(4) DEFAULT NULL,
  `q1` tinyint(4) DEFAULT NULL,
  `q2` tinyint(4) DEFAULT NULL,
  `q3` tinyint(4) DEFAULT NULL,
  `q4` tinyint(4) DEFAULT NULL,
  `q5` tinyint(4) DEFAULT NULL,
  `q6` tinyint(4) DEFAULT NULL,
  `q7` tinyint(4) DEFAULT NULL,
  `q8` tinyint(4) DEFAULT NULL,
  `q9` tinyint(4) DEFAULT NULL,
  `q10` tinyint(4) DEFAULT NULL,
  `q11` tinyint(4) DEFAULT NULL,
  `q12` tinyint(4) DEFAULT NULL,
  `q13` tinyint(4) DEFAULT NULL,
  `q14` tinyint(4) DEFAULT NULL,
  `q15` tinyint(4) DEFAULT NULL,
  `q16` tinyint(4) DEFAULT NULL,
  `puntaje_total` int(11) DEFAULT NULL,
  `texto_conclusion_generada` text DEFAULT NULL,
  PRIMARY KEY (`id_fp`),
  KEY `FK_tb_fuerza_porter_tb_empresa` (`id_empresa`),
  CONSTRAINT `FK_tb_fuerza_porter_tb_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_fuerza_porter: ~0 rows (aproximadamente)

-- Volcando estructura para tabla appplanestrategico.tb_matrices_foda
CREATE TABLE IF NOT EXISTS `tb_matrices_foda` (
  `id_matriz` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `tipo_matriz` enum('fo','fa','do','da') NOT NULL,
  `fila` varchar(10) NOT NULL,
  `columna` varchar(10) NOT NULL,
  `valor` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_matriz`),
  UNIQUE KEY `unique_celda` (`id_empresa`,`tipo_matriz`,`fila`,`columna`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_matrices_foda_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_matrices_foda: ~64 rows (aproximadamente)
INSERT INTO `tb_matrices_foda` (`id_matriz`, `id_empresa`, `tipo_matriz`, `fila`, `columna`, `valor`, `fecha_actualizacion`) VALUES
	(1, 1, 'fo', 'F1', 'O1', 0, '2025-06-12 19:35:25'),
	(2, 1, 'fo', 'F1', 'O2', 0, '2025-06-12 19:35:25'),
	(3, 1, 'fo', 'F1', 'O3', 0, '2025-06-12 19:35:25'),
	(4, 1, 'fo', 'F1', 'O4', 0, '2025-06-12 19:35:25'),
	(5, 1, 'fo', 'F2', 'O1', 1, '2025-06-12 19:35:25'),
	(6, 1, 'fo', 'F2', 'O2', 0, '2025-06-12 19:35:25'),
	(7, 1, 'fo', 'F2', 'O3', 0, '2025-06-12 19:35:26'),
	(8, 1, 'fo', 'F2', 'O4', 0, '2025-06-12 19:35:26'),
	(9, 1, 'fo', 'F3', 'O1', 0, '2025-06-12 19:35:26'),
	(10, 1, 'fo', 'F3', 'O2', 0, '2025-06-12 19:35:26'),
	(11, 1, 'fo', 'F3', 'O3', 0, '2025-06-12 19:35:26'),
	(12, 1, 'fo', 'F3', 'O4', 0, '2025-06-12 19:35:26'),
	(13, 1, 'fo', 'F4', 'O1', 0, '2025-06-12 19:35:26'),
	(14, 1, 'fo', 'F4', 'O2', 0, '2025-06-12 19:35:26'),
	(15, 1, 'fo', 'F4', 'O3', 0, '2025-06-12 19:35:26'),
	(16, 1, 'fo', 'F4', 'O4', 0, '2025-06-12 19:35:26'),
	(17, 1, 'fa', 'F1', 'A1', 0, '2025-06-12 19:35:26'),
	(18, 1, 'fa', 'F1', 'A2', 0, '2025-06-12 19:35:26'),
	(19, 1, 'fa', 'F1', 'A3', 0, '2025-06-12 19:35:26'),
	(20, 1, 'fa', 'F1', 'A4', 0, '2025-06-12 19:35:26'),
	(21, 1, 'fa', 'F2', 'A1', 0, '2025-06-12 19:35:26'),
	(22, 1, 'fa', 'F2', 'A2', 0, '2025-06-12 19:35:26'),
	(23, 1, 'fa', 'F2', 'A3', 0, '2025-06-12 19:35:26'),
	(24, 1, 'fa', 'F2', 'A4', 0, '2025-06-12 19:35:26'),
	(25, 1, 'fa', 'F3', 'A1', 0, '2025-06-12 19:35:26'),
	(26, 1, 'fa', 'F3', 'A2', 0, '2025-06-12 19:35:26'),
	(27, 1, 'fa', 'F3', 'A3', 0, '2025-06-12 19:35:26'),
	(28, 1, 'fa', 'F3', 'A4', 0, '2025-06-12 19:35:26'),
	(29, 1, 'fa', 'F4', 'A1', 0, '2025-06-12 19:35:26'),
	(30, 1, 'fa', 'F4', 'A2', 0, '2025-06-12 19:35:26'),
	(31, 1, 'fa', 'F4', 'A3', 0, '2025-06-12 19:35:26'),
	(32, 1, 'fa', 'F4', 'A4', 0, '2025-06-12 19:35:26'),
	(33, 1, 'do', 'D1', 'O1', 0, '2025-06-12 19:35:26'),
	(34, 1, 'do', 'D1', 'O2', 0, '2025-06-12 19:35:26'),
	(35, 1, 'do', 'D1', 'O3', 0, '2025-06-12 19:35:26'),
	(36, 1, 'do', 'D1', 'O4', 0, '2025-06-12 19:35:26'),
	(37, 1, 'do', 'D2', 'O1', 0, '2025-06-12 19:35:26'),
	(38, 1, 'do', 'D2', 'O2', 0, '2025-06-12 19:35:26'),
	(39, 1, 'do', 'D2', 'O3', 0, '2025-06-12 19:35:26'),
	(40, 1, 'do', 'D2', 'O4', 0, '2025-06-12 19:35:26'),
	(41, 1, 'do', 'D3', 'O1', 0, '2025-06-12 19:35:26'),
	(42, 1, 'do', 'D3', 'O2', 0, '2025-06-12 19:35:26'),
	(43, 1, 'do', 'D3', 'O3', 0, '2025-06-12 19:35:26'),
	(44, 1, 'do', 'D3', 'O4', 0, '2025-06-12 19:35:26'),
	(45, 1, 'do', 'D4', 'O1', 0, '2025-06-12 19:35:26'),
	(46, 1, 'do', 'D4', 'O2', 0, '2025-06-12 19:35:26'),
	(47, 1, 'do', 'D4', 'O3', 0, '2025-06-12 19:35:26'),
	(48, 1, 'do', 'D4', 'O4', 0, '2025-06-12 19:35:26'),
	(49, 1, 'da', 'D1', 'A1', 0, '2025-06-12 19:35:26'),
	(50, 1, 'da', 'D1', 'A2', 0, '2025-06-12 19:35:26'),
	(51, 1, 'da', 'D1', 'A3', 0, '2025-06-12 19:35:26'),
	(52, 1, 'da', 'D1', 'A4', 0, '2025-06-12 19:35:26'),
	(53, 1, 'da', 'D2', 'A1', 0, '2025-06-12 19:35:26'),
	(54, 1, 'da', 'D2', 'A2', 0, '2025-06-12 19:35:26'),
	(55, 1, 'da', 'D2', 'A3', 0, '2025-06-12 19:35:26'),
	(56, 1, 'da', 'D2', 'A4', 0, '2025-06-12 19:35:26'),
	(57, 1, 'da', 'D3', 'A1', 0, '2025-06-12 19:35:26'),
	(58, 1, 'da', 'D3', 'A2', 0, '2025-06-12 19:35:26'),
	(59, 1, 'da', 'D3', 'A3', 0, '2025-06-12 19:35:26'),
	(60, 1, 'da', 'D3', 'A4', 0, '2025-06-12 19:35:26'),
	(61, 1, 'da', 'D4', 'A1', 0, '2025-06-12 19:35:26'),
	(62, 1, 'da', 'D4', 'A2', 0, '2025-06-12 19:35:26'),
	(63, 1, 'da', 'D4', 'A3', 0, '2025-06-12 19:35:26'),
	(64, 1, 'da', 'D4', 'A4', 0, '2025-06-12 19:35:26');

-- Volcando estructura para tabla appplanestrategico.tb_obj_especificos
CREATE TABLE IF NOT EXISTS `tb_obj_especificos` (
  `id_obj_espe` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_espe` text DEFAULT NULL,
  `id_obj_estra` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_obj_espe`),
  KEY `id_obj_estra` (`id_obj_estra`),
  CONSTRAINT `tb_obj_especificos_ibfk_1` FOREIGN KEY (`id_obj_estra`) REFERENCES `tb_obj_estra` (`id_obj_estra`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_obj_especificos: ~2 rows (aproximadamente)
INSERT INTO `tb_obj_especificos` (`id_obj_espe`, `descripcion_espe`, `id_obj_estra`) VALUES
	(1, 'Expandir la presencia de la empresa en 5 nuevos mercados internacionales', 1),
	(2, 'asdasdasd', 1);

-- Volcando estructura para tabla appplanestrategico.tb_obj_estra
CREATE TABLE IF NOT EXISTS `tb_obj_estra` (
  `id_obj_estra` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) DEFAULT NULL,
  `nombre_obj_estra` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_obj_estra`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_obj_estra_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_obj_estra: ~1 rows (aproximadamente)
INSERT INTO `tb_obj_estra` (`id_obj_estra`, `id_empresa`, `nombre_obj_estra`) VALUES
	(1, 1, 'Objetivo Estratégico de Expansión Global');

-- Volcando estructura para tabla appplanestrategico.tb_oportunidades
CREATE TABLE IF NOT EXISTS `tb_oportunidades` (
  `id_oportunidad` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_oportunidad`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_oportunidades_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_oportunidades: ~2 rows (aproximadamente)
INSERT INTO `tb_oportunidades` (`id_oportunidad`, `id_empresa`, `descripcion`, `fecha_creacion`) VALUES
	(1, 1, 'Crecimiento del sector en la región', '2025-06-12 11:30:03'),
	(2, 1, 'Nuevas tecnologías disponibles', '2025-06-12 11:30:03');

-- Volcando estructura para tabla appplanestrategico.tb_respuestas_pest
CREATE TABLE IF NOT EXISTS `tb_respuestas_pest` (
  `id_respuesta` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
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
  `q11` tinyint(1) DEFAULT NULL,
  `q12` tinyint(1) DEFAULT NULL,
  `q13` tinyint(1) DEFAULT NULL,
  `q14` tinyint(1) DEFAULT NULL,
  `q15` tinyint(1) DEFAULT NULL,
  `q16` tinyint(1) DEFAULT NULL,
  `q17` tinyint(1) DEFAULT NULL,
  `q18` tinyint(1) DEFAULT NULL,
  `q19` tinyint(1) DEFAULT NULL,
  `q20` tinyint(1) DEFAULT NULL,
  `q21` tinyint(1) DEFAULT NULL,
  `q22` tinyint(1) DEFAULT NULL,
  `q23` tinyint(1) DEFAULT NULL,
  `q24` tinyint(1) DEFAULT NULL,
  `q25` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_respuesta`) USING BTREE,
  KEY `id_empresa` (`id_empresa`) USING BTREE,
  CONSTRAINT `tb_respuestas_pest_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_respuestas_pest: ~0 rows (aproximadamente)

-- Volcando estructura para tabla appplanestrategico.tb_sintesis_estrategias
CREATE TABLE IF NOT EXISTS `tb_sintesis_estrategias` (
  `id_sintesis` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `relacion` varchar(10) NOT NULL COMMENT 'FO, AF, AD, OD',
  `tipologia_estrategia` varchar(100) NOT NULL,
  `puntuacion` int(11) DEFAULT 0,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_sintesis`),
  UNIQUE KEY `unique_empresa_relacion` (`id_empresa`,`relacion`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_sintesis_estrategias_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_sintesis_estrategias: ~4 rows (aproximadamente)
INSERT INTO `tb_sintesis_estrategias` (`id_sintesis`, `id_empresa`, `relacion`, `tipologia_estrategia`, `puntuacion`, `descripcion`, `fecha_creacion`, `fecha_actualizacion`) VALUES
	(1, 1, 'FO', 'Estrategia Ofensiva', 1, 'Deberá adoptar estrategias de crecimiento', '2025-06-12 16:45:02', '2025-06-12 19:35:23'),
	(2, 1, 'AF', 'Estrategia Defensiva', 0, 'La empresa no está preparada para enfrentarse a las amenazas', '2025-06-12 16:45:02', '2025-06-12 19:35:23'),
	(3, 1, 'AD', 'Estrategia de Supervivencia', 0, 'Se enfrenta a amenazas externas sin las fortalezas necesarias para luchar con la competencia', '2025-06-12 16:45:02', '2025-06-12 19:35:23'),
	(4, 1, 'OD', 'Estrategia de Reorientación', 0, 'La empresa no puede aprovechar las oportunidades porque carece de preparación adecuada', '2025-06-12 16:45:02', '2025-06-12 19:35:23');

-- Volcando estructura para tabla appplanestrategico.tb_tcm
CREATE TABLE IF NOT EXISTS `tb_tcm` (
  `id_tcm` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  PRIMARY KEY (`id_tcm`),
  KEY `idx_empresa` (`id_empresa`),
  KEY `idx_venta` (`id_venta`),
  CONSTRAINT `fk_tcm_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE,
  CONSTRAINT `fk_tcm_venta` FOREIGN KEY (`id_venta`) REFERENCES `tb_venta` (`id_venta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_tcm: ~0 rows (aproximadamente)

-- Volcando estructura para tabla appplanestrategico.tb_uen
CREATE TABLE IF NOT EXISTS `tb_uen` (
  `id_uen` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_uen` varchar(150) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_uen`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_uen_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_uen: ~0 rows (aproximadamente)

-- Volcando estructura para tabla appplanestrategico.tb_usuario
CREATE TABLE IF NOT EXISTS `tb_usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_usuario: ~1 rows (aproximadamente)
INSERT INTO `tb_usuario` (`id_usuario`, `nombre`, `apellido`, `usuario`, `correo`, `password`) VALUES
	(1, 'Juan', 'Pérez', 'juanperez', 'juan', 'juan');

-- Volcando estructura para tabla appplanestrategico.tb_valores
CREATE TABLE IF NOT EXISTS `tb_valores` (
  `id_valores` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) DEFAULT NULL,
  `valor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_valores`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_valores_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_valores: ~0 rows (aproximadamente)

-- Volcando estructura para tabla appplanestrategico.tb_venta
CREATE TABLE IF NOT EXISTS `tb_venta` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `nombre_producto` varchar(20) NOT NULL,
  `prevision_ventas` int(11) NOT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_venta_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_venta: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;


-- TABLA 1: Productos
CREATE TABLE IF NOT EXISTS `tb_bcg_productos` (
  `id_producto` INT(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` INT(11) NOT NULL,
  `nombre_producto` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `fk_bcg_producto_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- TABLA 2: TCM por año
CREATE TABLE IF NOT EXISTS `tb_bcg_tcm` (
  `id_tcm` INT(11) NOT NULL AUTO_INCREMENT,
  `id_producto` INT(11) NOT NULL,
  `periodo` VARCHAR(20) NOT NULL, -- Ej: '2023-2024'
  `porcentaje_tcm` DECIMAL(5,2) NOT NULL,
  PRIMARY KEY (`id_tcm`),
  KEY `id_producto` (`id_producto`),
  CONSTRAINT `fk_tcm_producto` FOREIGN KEY (`id_producto`) REFERENCES `tb_bcg_productos` (`id_producto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- TABLA 3: Ventas y PRM
CREATE TABLE IF NOT EXISTS `tb_bcg_ventas` (
  `id_venta` INT(11) NOT NULL AUTO_INCREMENT,
  `id_producto` INT(11) NOT NULL,
  `ventas` DECIMAL(10,2) NOT NULL,
  `porcentaje_total` DECIMAL(5,2),
  `prm` DECIMAL(5,2),
  `mayor_venta_competidor` DECIMAL(10,2),
  `fecha_registro` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_venta`),
  KEY `id_producto` (`id_producto`),
  CONSTRAINT `fk_ventas_producto` FOREIGN KEY (`id_producto`) REFERENCES `tb_bcg_productos` (`id_producto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- INSERTAR PRODUCTOS (empresa con ID 1)
INSERT INTO `tb_bcg_productos` (`id_empresa`, `nombre_producto`) VALUES
(1, 'Producto A'),
(1, 'Producto B'),
(1, 'Producto C'),
(1, 'Producto D');

-- INSERTAR TCM (por producto y año)
INSERT INTO `tb_bcg_tcm` (`id_producto`, `periodo`, `porcentaje_tcm`) VALUES
(1, '2023-2024', 12.5),
(2, '2023-2024', 8.4),
(3, '2023-2024', 10.2),
(4, '2023-2024', 6.9);

-- INSERTAR VENTAS y PRM
-- PRM = ventas / mayor_venta_competidor
INSERT INTO `tb_bcg_ventas` (`id_producto`, `ventas`, `porcentaje_total`, `prm`, `mayor_venta_competidor`) VALUES
(1, 100000, 35.00, 1.10, 90000),
(2, 70000, 25.00, 0.85, 82000),
(3, 50000, 20.00, 0.70, 71000),
(4, 30000, 20.00, 1.20, 25000);


CREATE TABLE tb_ventas_competidores (
  id_venta_competidor INT AUTO_INCREMENT PRIMARY KEY,
  id_empresa INT NOT NULL,
  nombre_producto VARCHAR(100) NOT NULL,
  nombre_competidor VARCHAR(50) NOT NULL,
  ventas INT NOT NULL,
  FOREIGN KEY (id_empresa) REFERENCES tb_empresa(id_empresa) ON DELETE CASCADE
);

INSERT INTO tb_ventas_competidores (id_empresa, nombre_producto, nombre_competidor, ventas) VALUES
(1, 'Producto A', 'Pr-1', 90000),
(1, 'Producto A', 'Pr-2', 85000),
(1, 'Producto B', 'Pr-1', 60000),
(1, 'Producto B', 'Pr-2', 55000),
(1, 'Producto C', 'Pr-1', 40000),
(1, 'Producto D', 'Pr-1', 25000);