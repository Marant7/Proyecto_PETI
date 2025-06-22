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

-- Volcando estructura para tabla plan_estrategico.empresas
CREATE TABLE IF NOT EXISTS `empresas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.empresas: ~1 rows (aproximadamente)
INSERT INTO `empresas` (`id`, `nombre`, `descripcion`, `usuario_id`, `fecha_creacion`) VALUES
	(1, 'TechCorp S.A.', 'adsadasd', 1, '2025-06-21 22:01:56'),
	(2, 'oracle', 'descripcion', 1, '2025-06-22 02:51:41');

-- Volcando estructura para tabla plan_estrategico.planes
CREATE TABLE IF NOT EXISTS `planes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empresa_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `vision` text DEFAULT NULL,
  `mision` text DEFAULT NULL,
  `valores` text DEFAULT NULL,
  `objetivos` text DEFAULT NULL,
  `cadena_valor` text DEFAULT NULL,
  `matriz_bcg` text DEFAULT NULL,
  `fuerzas_porter` text DEFAULT NULL,
  `pest` text DEFAULT NULL,
  `estrategias` text DEFAULT NULL,
  `matriz_came` text DEFAULT NULL,
  `resumen_ejecutivo` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `empresa_id` (`empresa_id`),
  CONSTRAINT `planes_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.planes: ~8 rows (aproximadamente)
INSERT INTO `planes` (`id`, `empresa_id`, `fecha_creacion`, `vision`, `mision`, `valores`, `objetivos`, `cadena_valor`, `matriz_bcg`, `fuerzas_porter`, `pest`, `estrategias`, `matriz_came`, `resumen_ejecutivo`) VALUES
	(1, 1, '2025-06-21 22:08:41', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 1, '2025-06-21 22:09:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, 1, '2025-06-21 22:12:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, 1, '2025-06-21 22:12:18', 'sdaas', 'dsadas', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(5, 1, '2025-06-21 22:18:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(6, 1, '2025-06-21 22:18:15', 'eqeqeqeqe', 'qeqeqeq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(7, 1, '2025-06-21 22:31:23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(8, 1, '2025-06-21 22:32:37', 'yayayaya', 'dddddaaaaaaaaa323232', '["valorprobando"]', '{"uen_descripcion":"dsadsadsadas","objetivos_generales":["nombre"],"objetivos_especificos":{"1":["ahora","ahoraa"]}}', '{"respuestas":{"q1":"2","q2":"2","q3":"2","q4":"2","q5":"2","q6":"2","q7":"2","q8":"2","q9":"2","q10":"2","q11":"2","q12":"2","q13":"2","q14":"2","q15":"2","q16":"2","q17":"2","q18":"2","q19":"2","q20":"2","q21":"2","q22":"2","q23":"2","q24":"2","q25":"2"},"resultado":"                reflexopm","porcentaje":"50","fortalezas":["fortaleza balor","dddsd"],"debilidades":["debilidad valor","dsdsds"]}', '{"productos":["a","a2","a3"],"ventas":["3","3","3"],"porcentaje_total":["33.33","33.33","33.33"],"fortalezas":["fortaleza de bcg"],"debilidades":["debilidad de bcg"],"tcm_2012_0":"1","tcm_2012_1":"1","tcm_2012_2":"1","tcm_2013_0":"1","tcm_2013_1":"1","tcm_2013_2":"1","tcm_2014_0":"1","tcm_2014_1":"1","tcm_2014_2":"1","tcm_2015_0":"1","tcm_2015_1":"1","tcm_2015_2":"1","demanda_2012_0":"1","demanda_2012_1":"1","demanda_2012_2":"1","demanda_2013_0":"1","demanda_2013_1":"1","demanda_2013_2":"1","demanda_2014_0":"1","demanda_2014_1":"1","demanda_2014_2":"1","demanda_2015_0":"1","demanda_2015_1":"1","demanda_2015_2":"1","demanda_2016_0":"1","demanda_2016_1":"1","demanda_2016_2":"1","competidor_1_0":"1","competidor_1_1":"1","competidor_1_2":"1","competidor_2_0":"1","competidor_2_1":"1","competidor_2_2":"1","competidor_3_0":"1","competidor_3_1":"1","competidor_3_2":"1"}', '{"factores":[3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3],"oportunidades":["oportunidades porter"],"amenazas":["amenazas porter "],"total":51,"conclusion":"La situaci\\u00f3n actual del mercado es favorable a la empresa.","fecha_guardado":"2025-06-22 04:12:30"}', '{"respuestas":{"pest_1":5,"pest_2":1,"pest_3":1,"pest_4":1,"pest_5":1,"pest_6":1,"pest_7":1,"pest_8":1,"pest_9":1,"pest_10":1,"pest_11":1,"pest_12":1,"pest_13":1,"pest_14":1,"pest_15":1,"pest_16":1,"pest_17":1,"pest_18":1,"pest_19":1,"pest_20":1,"pest_21":1,"pest_22":1,"pest_23":1,"pest_24":1,"pest_25":1},"factores":{"demografico":9,"legal_politico":5,"economico":5,"tecnologico":5,"medioambiental":5},"total":29,"evaluacion":"Desafiante: El entorno presenta significativos retos que requieren estrategias espec\\u00edficas.","oportunidades":["oportunidades pest"],"amenazas":["amenazas pest"],"fecha_guardado":"2025-06-22 03:08:18"}', '{"foda":[],"evaluacion":{"evaluaciones":{"fo_f1_o1":"1","fo_f2_o1":"1","fo_f2_o2":"2","fa_f1_a1":"1","fa_f2_a2":"1","do_d1_o1":"1","do_d2_o1":"3","do_d2_o2":"2","da_d1_a1":"1","da_d2_a2":"2"},"totales":{"fo":"4","fa":"2","do":"6","da":"3"},"sintesis":"Su empresa tiene un <strong>perfil de SUPERVIVENCIA<\\/strong> (DO: 6 pts). Se recomienda superar debilidades aprovechando oportunidades disponibles.","recomendacion":"<div class=\\"alert alert-warning\\"><strong>Estrategia de Supervivencia (DO)<\\/strong><br>Debe superar sus debilidades aprovechando las oportunidades disponibles. Enfoque en mejora y adaptaci\\u00f3n.<\\/div>"},"fecha_actualizacion":"2025-06-22 04:31:33"}', '{"corregir_0":"corregido1","corregir_1":"corregido12","corregir_2":"corregido13","afrontar_0":"corregido14","afrontar_1":"corregido16","mantener_0":"corregido17","mantener_1":"corregido18","mantener_2":"corregido19","explotar_0":"corregido1867","explotar_1":"corregido154"}', '{"emprendedores_promotores":"dsadas","identificacion_estrategica":"dsadasdsa","conclusiones":"dsadasdasdsa","fecha_guardado":"2025-06-22 04:50:50"}'),
	(9, 2, '2025-06-22 02:51:45', 'hola p', 'probando', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- Volcando estructura para tabla plan_estrategico.tb_usuario
CREATE TABLE IF NOT EXISTS `tb_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `correo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla plan_estrategico.tb_usuario: ~1 rows (aproximadamente)
INSERT INTO `tb_usuario` (`id`, `nombre`, `apellido`, `usuario`, `password`, `correo`) VALUES
	(1, 'admin', 'admin', 'admin21', '$2y$10$vSaBrcOY6jVtKzdYSra/AeljUxh.MazE.CWvEWU2YsSj0IKqfEW22', 'admin@g.com');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
