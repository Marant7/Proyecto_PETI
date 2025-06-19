-- ===============================================
-- BASE DE DATOS PARA PLAN ESTRATÉGICO COMPLETO
-- ===============================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `plan_estrategico` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `plan_estrategico`;

-- ===============================================
-- TABLA PRINCIPAL: PLAN ESTRATÉGICO
-- ===============================================
CREATE TABLE `tb_plan_estrategico` (
  `id_plan` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `nombre_plan` varchar(255) NOT NULL,
  `empresa` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_modificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` enum('borrador','completado','archivado') DEFAULT 'borrador',
  PRIMARY KEY (`id_plan`),
  KEY `idx_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- TABLA: USUARIOS
-- ===============================================
CREATE TABLE `tb_usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL UNIQUE,
  `correo` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- VISIÓN Y MISIÓN
-- ===============================================
CREATE TABLE `tb_vision_mision` (
  `id_vision_mision` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `vision` text NOT NULL,
  `mision` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_vision_mision`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_vision_mision_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- VALORES
-- ===============================================
CREATE TABLE `tb_valores` (
  `id_valor` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `valor` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_valor`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_valores_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- OBJETIVOS ESTRATÉGICOS
-- ===============================================
CREATE TABLE `tb_objetivos_estrategicos` (
  `id_objetivo` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `objetivo` text NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `prioridad` enum('alta','media','baja') DEFAULT 'media',
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_objetivo`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_objetivos_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- CADENA DE VALOR
-- ===============================================
CREATE TABLE `tb_cadena_valor` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- MATRIZ BCG - CONFIGURACIÓN
-- ===============================================
CREATE TABLE `tb_matriz_bcg_config` (
  `id_config` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `num_productos` int(11) NOT NULL DEFAULT 5,
  `num_competidores` int(11) NOT NULL DEFAULT 5,
  `anio_inicio` int(11) NOT NULL DEFAULT 2020,
  `anio_fin` int(11) NOT NULL DEFAULT 2025,
  PRIMARY KEY (`id_config`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_bcg_config_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- MATRIZ BCG - PRODUCTOS
-- ===============================================
CREATE TABLE `tb_matriz_bcg_productos` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_producto`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_bcg_productos_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- MATRIZ BCG - PREVISIÓN DE VENTAS
-- ===============================================
CREATE TABLE `tb_matriz_bcg_ventas` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- MATRIZ BCG - TCM (Tasa de Crecimiento del Mercado)
-- ===============================================
CREATE TABLE `tb_matriz_bcg_tcm` (
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

-- ===============================================
-- MATRIZ BCG - DEMANDA GLOBAL
-- ===============================================
CREATE TABLE `tb_matriz_bcg_demanda_global` (
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

-- ===============================================
-- MATRIZ BCG - COMPETIDORES
-- ===============================================
CREATE TABLE `tb_matriz_bcg_competidores` (
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

-- ===============================================
-- FORTALEZAS Y DEBILIDADES (FODA INTERNO)
-- ===============================================
CREATE TABLE `tb_fortalezas` (
  `id_fortaleza` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_fortaleza`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_fortalezas_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tb_debilidades` (
  `id_debilidad` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_debilidad`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_debilidades_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- FUERZAS DE PORTER
-- ===============================================
CREATE TABLE `tb_fuerzas_porter` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- ANÁLISIS PEST
-- ===============================================
CREATE TABLE `tb_analisis_pest` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- OPORTUNIDADES Y AMENAZAS (FODA EXTERNO)
-- ===============================================
CREATE TABLE `tb_oportunidades` (
  `id_oportunidad` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `origen` enum('pest','porter','manual') DEFAULT 'manual',
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_oportunidad`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_oportunidades_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tb_amenazas` (
  `id_amenaza` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `origen` enum('pest','porter','manual') DEFAULT 'manual',
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_amenaza`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_amenazas_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- IDENTIFICACIÓN DE ESTRATEGIAS
-- ===============================================
CREATE TABLE `tb_estrategias` (
  `id_estrategia` int(11) NOT NULL AUTO_INCREMENT,
  `id_plan` int(11) NOT NULL,
  `tipo_estrategia` enum('FO','FA','DO','DA') NOT NULL,
  `descripcion` text NOT NULL,
  `prioridad` enum('alta','media','baja') DEFAULT 'media',
  `orden` int(11) DEFAULT 0,
  PRIMARY KEY (`id_estrategia`),
  KEY `idx_plan` (`id_plan`),
  CONSTRAINT `fk_estrategias_plan` FOREIGN KEY (`id_plan`) REFERENCES `tb_plan_estrategico` (`id_plan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- MATRIZ CAME
-- ===============================================
CREATE TABLE `tb_matriz_came` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- DATOS DE EJEMPLO
-- ===============================================
INSERT INTO `tb_usuario` (`nombre`, `apellido`, `usuario`, `correo`, `password`) VALUES
('Admin', 'Sistema', 'admin', 'admin@sistema.com', '$2y$10$example'),
('Usuario', 'Demo', 'demo', 'demo@demo.com', '$2y$10$example');

-- ===============================================
-- FOREIGN KEYS ADICIONALES
-- ===============================================
ALTER TABLE `tb_plan_estrategico` 
ADD CONSTRAINT `fk_plan_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuario` (`id_usuario`) ON DELETE CASCADE;
