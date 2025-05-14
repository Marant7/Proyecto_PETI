-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.27-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.4.0.6659
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
\
CREATE TABLE IF NOT EXISTS `tb_usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_empresa: ~0 rows (aproximadamente)

-- Volcando estructura para tabla appplanestrategico.tb_obj_estra
CREATE TABLE IF NOT EXISTS `tb_obj_estra` (
  `id_obj_estra` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) DEFAULT NULL,
  `nombre_obj_estra` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_obj_estra`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_obj_estra_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_obj_estra: ~0 rows (aproximadamente)
-- Volcando estructura para la tabla appplanestrategico.tb_obj_especificos
CREATE TABLE IF NOT EXISTS `tb_obj_especificos` (
  `id_obj_espe` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_espe` text DEFAULT NULL,
  `id_obj_estra` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_obj_espe`),
  KEY `id_obj_estra` (`id_obj_estra`),
  CONSTRAINT `tb_obj_especificos_ibfk_1` FOREIGN KEY (`id_obj_estra`) REFERENCES `tb_obj_estra` (`id_obj_estra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla appplanestrategico.tb_obj_especificos: ~0 rows (aproximadamente)

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


-- Volcando datos para la tabla appplanestrategico.tb_usuario: ~0 rows (aproximadamente)

-- Volcando estructura para tabla appplanestrategico.tb_valores
CREATE TABLE IF NOT EXISTS `tb_valores` (
  `id_valores` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) DEFAULT NULL,
  `valor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_valores`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_valores_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Insertar usuario
INSERT INTO `tb_usuario` (`nombre`, `apellido`, `usuario`, `correo`, `password`) 
VALUES 
('Juan', 'Pérez', 'juanperez', 'juan', 'juan');

-- Insertar empresa
INSERT INTO `tb_empresa` (`id_usuario`, `nombre_empresa`, `mision`, `vision`, `fecha_creacion`, `descripcion`)
VALUES
(1, 'Empresa XYZ', 'Nuestra misión es liderar el mercado', 'Ser la empresa más innovadora', '2025-04-24', 'Descripción de la empresa XYZ');

-- Insertar objetivo estratégico
INSERT INTO `tb_obj_estra` (`id_empresa`, `nombre_obj_estra`)
VALUES
(1, 'Objetivo Estratégico de Expansión Global');

-- Insertar objetivo específico
INSERT INTO `tb_obj_especificos` (`descripcion_espe`, `id_obj_estra`)
VALUES
('Expandir la presencia de la empresa en 5 nuevos mercados internacionales', 1);

INSERT INTO `tb_obj_especificos` (`descripcion_espe`, `id_obj_estra`)
VALUES
('asdasdasd', 1);

SELECT * FROM tb_obj_especificos

-- Tabla de Fortalezas
CREATE TABLE IF NOT EXISTS `tb_fortalezas` (
  `id_fortaleza` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_fortaleza`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_fortalezas_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Tabla de Oportunidades
CREATE TABLE IF NOT EXISTS `tb_oportunidades` (
  `id_oportunidad` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_oportunidad`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_oportunidades_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Tabla de Debilidades
CREATE TABLE IF NOT EXISTS `tb_debilidades` (
  `id_debilidad` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_debilidad`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_debilidades_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Tabla de Amenazas
CREATE TABLE IF NOT EXISTS `tb_amenazas` (
  `id_amenaza` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_amenaza`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_amenazas_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE IF NOT EXISTS `tb_evaluacion_cadena_valor` (
  `id_evaluacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `fecha_evaluacion` datetime DEFAULT CURRENT_TIMESTAMP,
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
  `porcentaje_resultado` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id_evaluacion`),
  KEY `id_empresa` (`id_empresa`),
  CONSTRAINT `tb_evaluacion_cadena_valor_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tb_empresa` (`id_empresa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



-- Insertar datos en cada tabla para la empresa con id_empresa = 1

-- Fortalezas
INSERT INTO `tb_fortalezas` (`id_empresa`, `descripcion`) VALUES
(1, 'Equipo altamente capacitado'),
(1, 'Buena reputación en el mercado');

-- Oportunidades
INSERT INTO `tb_oportunidades` (`id_empresa`, `descripcion`) VALUES
(1, 'Crecimiento del sector en la región'),
(1, 'Nuevas tecnologías disponibles');

-- Debilidades
INSERT INTO `tb_debilidades` (`id_empresa`, `descripcion`) VALUES
(1, 'Infraestructura tecnológica obsoleta'),
(1, 'Falta de presencia en redes sociales');

-- Amenazas
INSERT INTO `tb_amenazas` (`id_empresa`, `descripcion`) VALUES
(1, 'Entrada de competidores internacionales'),
(1, 'Cambios en regulaciones del sector');

-- Volcando datos para la tabla appplanestrategico.tb_valores: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
