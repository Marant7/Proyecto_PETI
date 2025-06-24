<?php
// Script de prueba para verificar el funcionamiento de la matriz CAME

require_once 'config/clsconexion.php';
require_once 'Models/PlanModel.php';

try {
    echo "<h1>🧪 Test Matriz CAME</h1>";
    
    // Test 1: Verificar conexión
    echo "<h2>✅ Test 1: Conexión a Base de Datos</h2>";
    $db = (new clsConexion())->getConexion();
    echo "✓ Conexión exitosa<br><br>";
    
    // Test 2: Verificar estructura de tabla
    echo "<h2>🔍 Test 2: Estructura de Tabla</h2>";
    $stmt = $db->prepare("SHOW COLUMNS FROM planes LIKE 'matriz_came'");
    $stmt->execute();
    $column = $stmt->fetch();
    
    if ($column) {
        echo "✅ Columna 'matriz_came' existe<br>";
        echo "Tipo: " . $column['Type'] . "<br>";
        echo "Permite NULL: " . $column['Null'] . "<br>";
    } else {
        echo "❌ Falta columna 'matriz_came'<br>";
        echo "💡 Ejecutar: ALTER TABLE planes ADD matriz_came TEXT NULL;<br>";
    }
    echo "<br>";
    
    // Test 3: Verificar métodos del modelo
    echo "<h2>🔧 Test 3: Métodos del Modelo</h2>";
    $model = new PlanModel($db);
    echo "✓ Modelo instanciado correctamente<br>";
    
    // Verificar que existen los métodos
    if (method_exists($model, 'guardarMatrizCame')) {
        echo "✅ Método guardarMatrizCame existe<br>";
    } else {
        echo "❌ Método guardarMatrizCame NO existe<br>";
    }
    
    if (method_exists($model, 'obtenerMatrizCame')) {
        echo "✅ Método obtenerMatrizCame existe<br>";
    } else {
        echo "❌ Método obtenerMatrizCame NO existe<br>";
    }
    echo "<br>";
    
    // Test 4: Test de guardado (si hay datos)
    echo "<h2>💾 Test 4: Test de Guardado</h2>";
    $plan_id_test = 1; // Cambiar por un ID de plan existente
    
    // Verificar que existe el plan
    $stmt = $db->prepare("SELECT id FROM planes WHERE id = ?");
    $stmt->execute([$plan_id_test]);
    $plan_existe = $stmt->fetch();
    
    if ($plan_existe) {
        echo "✓ Plan ID $plan_id_test existe<br>";
        
        // Test de guardado
        $test_data = [
            'corregir_0' => 'Estrategia de prueba para corregir',
            'afrontar_0' => 'Estrategia de prueba para afrontar',
            'mantener_0' => 'Estrategia de prueba para mantener',
            'explotar_0' => 'Estrategia de prueba para explotar',
            'fecha_guardado' => date('Y-m-d H:i:s')
        ];
        
        $resultado = $model->guardarMatrizCame($plan_id_test, $test_data);
        
        if ($resultado) {
            echo "✅ Test de guardado exitoso<br>";
            
            // Verificar que se guardó
            $datos_guardados = $model->obtenerMatrizCame($plan_id_test);
            if ($datos_guardados && !empty($datos_guardados)) {
                echo "✅ Test de lectura exitoso<br>";
                echo "Datos guardados: " . json_encode($datos_guardados, JSON_PRETTY_PRINT) . "<br>";
            } else {
                echo "❌ Error en test de lectura<br>";
            }
        } else {
            echo "❌ Error en test de guardado<br>";
        }
    } else {
        echo "❌ Plan ID $plan_id_test no existe. Cambie el ID en el script.<br>";
    }
    echo "<br>";
    
    // Test 5: Verificar controlador
    echo "<h2>🎮 Test 5: Controlador</h2>";
    if (file_exists('Controllers/PlanController.php')) {
        $controller_content = file_get_contents('Controllers/PlanController.php');
        if (strpos($controller_content, 'guardarMatrizCame') !== false) {
            echo "✅ Método guardarMatrizCame encontrado en controlador<br>";
        } else {
            echo "❌ Método guardarMatrizCame NO encontrado en controlador<br>";
        }
        
        if (strpos($controller_content, "action=guardarMatrizCame") !== false) {
            echo "✅ Ruta 'guardarMatrizCame' configurada<br>";
        } else {
            echo "❌ Ruta 'guardarMatrizCame' NO configurada<br>";
        }
    } else {
        echo "❌ Archivo PlanController.php no encontrado<br>";
    }
    
} catch (Exception $e) {
    echo "<h1>❌ Error en el Test</h1>";
    echo "Error: " . $e->getMessage();
    echo "<br>Trace: " . $e->getTraceAsString();
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background: #f5f5f5;
}
h1, h2 {
    color: #333;
}
pre {
    background: #f8f8f8;
    padding: 10px;
    border-radius: 4px;
    overflow-x: auto;
}
</style>
