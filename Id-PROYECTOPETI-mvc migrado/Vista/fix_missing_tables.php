<?php
// Script para verificar y crear las tablas faltantes para el sistema de plan estratégico

require_once '../config/clsconexion.php';

try {
    $conexion = new clsConexion();
    $pdo = $conexion->getConexion();
    
    echo "<h1>Verificación de Tablas de Base de Datos</h1>";
    
    // Lista de tablas que debería tener el sistema
    $tablas_necesarias = [
        'tb_plan_estrategico',
        'tb_vision_mision',
        'tb_valores',
        'tb_objetivos_estrategicos',
        'tb_uen',
        'tb_cadena_valor',
        'tb_matriz_bcg',
        'tb_fuerzas_porter',
        'tb_analisis_pest',
        'tb_fortalezas',
        'tb_debilidades',
        'tb_oportunidades',
        'tb_amenazas',
        'tb_identificacion_estrategica',
        'tb_matriz_came',
        'tb_resumen_ejecutivo'
    ];
    
    // Verificar qué tablas existen
    echo "<h2>Estado de las tablas:</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Tabla</th><th>Estado</th><th>Acción</th></tr>";
    
    $tablas_faltantes = [];
    
    foreach ($tablas_necesarias as $tabla) {
        try {
            $stmt = $pdo->query("SELECT 1 FROM $tabla LIMIT 1");
            echo "<tr><td>$tabla</td><td style='color: green;'>✅ Existe</td><td>-</td></tr>";
        } catch (Exception $e) {
            echo "<tr><td>$tabla</td><td style='color: red;'>❌ No existe</td><td>Crear</td></tr>";
            $tablas_faltantes[] = $tabla;
        }
    }
    echo "</table>";
    
    if (!empty($tablas_faltantes)) {
        echo "<h2>Tablas faltantes encontradas:</h2>";
        echo "<p>Se crearán las siguientes tablas: " . implode(', ', $tablas_faltantes) . "</p>";
        
        // SQL para crear las tablas faltantes
        $scripts_sql = [
            'tb_matriz_bcg' => "
                CREATE TABLE tb_matriz_bcg (
                    id_matriz_bcg INT AUTO_INCREMENT PRIMARY KEY,
                    id_plan INT NOT NULL,
                    num_productos INT DEFAULT 5,
                    num_competidores INT DEFAULT 5,
                    anio_inicio INT DEFAULT 2020,
                    anio_fin INT DEFAULT 2025,
                    productos TEXT,
                    ventas TEXT,
                    fortalezas TEXT,
                    debilidades TEXT,
                    datos_matriz TEXT,
                    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (id_plan) REFERENCES tb_plan_estrategico(id_plan) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ",
            'tb_fuerzas_porter' => "
                CREATE TABLE tb_fuerzas_porter (
                    id_fuerzas_porter INT AUTO_INCREMENT PRIMARY KEY,
                    id_plan INT NOT NULL,
                    poder_negociacion_proveedores TEXT,
                    poder_negociacion_compradores TEXT,
                    amenaza_productos_sustitutos TEXT,
                    amenaza_nuevos_competidores TEXT,
                    rivalidad_competidores TEXT,
                    oportunidades TEXT,
                    amenazas TEXT,
                    respuestas TEXT,
                    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (id_plan) REFERENCES tb_plan_estrategico(id_plan) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ",
            'tb_analisis_pest' => "
                CREATE TABLE tb_analisis_pest (
                    id_analisis_pest INT AUTO_INCREMENT PRIMARY KEY,
                    id_plan INT NOT NULL,
                    factores_politicos TEXT,
                    factores_economicos TEXT,
                    factores_sociales TEXT,
                    factores_tecnologicos TEXT,
                    oportunidades TEXT,
                    amenazas TEXT,
                    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (id_plan) REFERENCES tb_plan_estrategico(id_plan) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ",
            'tb_identificacion_estrategica' => "
                CREATE TABLE tb_identificacion_estrategica (
                    id_identificacion INT AUTO_INCREMENT PRIMARY KEY,
                    id_plan INT NOT NULL,
                    problemas_principales TEXT,
                    causas_identificadas TEXT,
                    efectos_esperados TEXT,
                    soluciones_propuestas TEXT,
                    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (id_plan) REFERENCES tb_plan_estrategico(id_plan) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ",
            'tb_matriz_came' => "
                CREATE TABLE tb_matriz_came (
                    id_matriz_came INT AUTO_INCREMENT PRIMARY KEY,
                    id_plan INT NOT NULL,
                    corregir TEXT,
                    afrontar TEXT,
                    mantener TEXT,
                    explotar TEXT,
                    estrategias_recomendadas TEXT,
                    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (id_plan) REFERENCES tb_plan_estrategico(id_plan) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ",
            'tb_uen' => "
                CREATE TABLE tb_uen (
                    id_uen INT AUTO_INCREMENT PRIMARY KEY,
                    id_plan INT NOT NULL,
                    descripcion TEXT,
                    unidades_estrategicas TEXT,
                    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (id_plan) REFERENCES tb_plan_estrategico(id_plan) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            "
        ];
        
        // Crear las tablas faltantes
        foreach ($tablas_faltantes as $tabla) {
            if (isset($scripts_sql[$tabla])) {
                try {
                    $pdo->exec($scripts_sql[$tabla]);
                    echo "<p style='color: green;'>✅ Tabla $tabla creada exitosamente</p>";
                } catch (Exception $e) {
                    echo "<p style='color: red;'>❌ Error al crear tabla $tabla: " . $e->getMessage() . "</p>";
                }
            }
        }
        
        echo "<h3>¡Tablas creadas!</h3>";
        echo "<p>Ahora puedes probar ver_plan.php nuevamente.</p>";
        
    } else {
        echo "<h2>✅ Todas las tablas están presentes</h2>";
    }
    
    // También verificar que la columna logo_empresa existe
    echo "<h2>Verificación de columna logo_empresa:</h2>";
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM tb_plan_estrategico LIKE 'logo_empresa'");
        $result = $stmt->fetch();
        if ($result) {
            echo "<p style='color: green;'>✅ Columna logo_empresa existe</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Columna logo_empresa no existe. Agregando...</p>";
            $pdo->exec("ALTER TABLE tb_plan_estrategico ADD COLUMN logo_empresa VARCHAR(255) NULL");
            echo "<p style='color: green;'>✅ Columna logo_empresa agregada</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error al verificar/agregar columna logo_empresa: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
}

echo "<h2>Enlaces de prueba después de la corrección:</h2>";
echo "<ul>";
echo "<li><a href='ver_plan.php?id=22'>Ver Plan ID 22</a></li>";
echo "<li><a href='debug_ver_plan.php?id=22'>Debug Plan ID 22</a></li>";
echo "<li><a href='resumen_plan.php?id=22'>Resumen Plan ID 22</a></li>";
echo "</ul>";
?>
