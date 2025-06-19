<?php
session_start();

echo "<h1>🎯 PRUEBA FINAL DEL RESUMEN DEL PLAN (SIN ANÁLISIS AUTOMÁTICOS)</h1>";
echo "<p><strong>Fecha:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<hr>";

// Simular sesión de usuario
$_SESSION['user'] = ['id_usuario' => 1];

require_once '../Controllers/PlanEstrategicoController.php';
$controller = new PlanEstrategicoController();

// Probar resumen ejecutivo del plan 16
$id_plan = 16;

echo "<h2>📊 PROBANDO RESUMEN PLAN DEL PLAN $id_plan (SOLO DATOS MANUALES)</h2>";

$resumen = $controller->obtenerResumenEjecutivo($id_plan);

if ($resumen) {
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>RESUMEN EJECUTIVO OBTENIDO EXITOSAMENTE</strong>";
    echo "</div>";
    
    echo "<h3>📝 1. Información Básica:</h3>";
    echo "<ul>";
    echo "<li><strong>Nombre del Plan:</strong> " . ($resumen['nombre_plan'] ?? 'No definido') . "</li>";
    echo "<li><strong>Empresa:</strong> " . ($resumen['empresa'] ?? 'No definida') . "</li>";
    echo "<li><strong>Fecha:</strong> " . ($resumen['fecha_creacion'] ?? 'No definida') . "</li>";
    echo "</ul>";
    
    echo "<h3>👁️ 2. Visión y Misión:</h3>";
    echo "<ul>";
    echo "<li><strong>Visión:</strong> " . ($resumen['vision'] ?? 'No definida') . "</li>";
    echo "<li><strong>Misión:</strong> " . ($resumen['mision'] ?? 'No definida') . "</li>";
    echo "</ul>";
    
    echo "<h3>💎 3. Valores Organizacionales:</h3>";
    if (isset($resumen['valores']) && !empty($resumen['valores'])) {
        echo "<ol>";
        foreach ($resumen['valores'] as $valor) {
            echo "<li><strong>" . $valor['valor'] . "</strong>";
            if (!empty($valor['descripcion'])) {
                echo " - " . $valor['descripcion'];
            }
            echo "</li>";
        }
        echo "</ol>";
    } else {
        echo "<p><em>No hay valores definidos</em></p>";
    }
    
    echo "<h3>🏢 4. Unidad Estratégica (Campo Manual):</h3>";
    if (isset($resumen['unidad_estrategica']) && !empty($resumen['unidad_estrategica'])) {
        echo "<div style='background: #e3f2fd; padding: 10px; border-left: 4px solid #2196f3; margin: 10px 0;'>";
        echo "✅ <strong>ENCONTRADO:</strong> " . htmlspecialchars($resumen['unidad_estrategica']);
        echo "</div>";
    } else {
        echo "<div style='background: #ffebee; padding: 10px; border-left: 4px solid #f44336; margin: 10px 0;'>";
        echo "❌ <strong>NO ENCONTRADO</strong> - Este campo debe contener el texto manual que escribiste sobre la unidad estratégica";
        echo "</div>";
    }
    
    echo "<h3>🎯 5. Objetivos Estratégicos:</h3>";
    if (isset($resumen['objetivos_estrategicos']) && !empty($resumen['objetivos_estrategicos'])) {
        echo "<ul>";
        foreach ($resumen['objetivos_estrategicos'] as $objetivo) {
            echo "<li><strong>" . ($objetivo['categoria'] ?? 'General') . ":</strong> " . $objetivo['objetivo'] . " <span style='color: #666;'>(" . ($objetivo['prioridad'] ?? 'media') . ")</span></li>";
        }
        echo "</ul>";
    } else {
        echo "<p><em>No hay objetivos definidos</em></p>";
    }
    
    echo "<h3>📊 6. Análisis FODA:</h3>";
    echo "<h4>Fortalezas:</h4>";
    if (isset($resumen['fortalezas']) && !empty($resumen['fortalezas'])) {
        echo "<ul>";
        foreach ($resumen['fortalezas'] as $fortaleza) {
            echo "<li>" . htmlspecialchars($fortaleza) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p><em>No hay fortalezas definidas</em></p>";
    }
    
    echo "<h4>Debilidades:</h4>";
    if (isset($resumen['debilidades']) && !empty($resumen['debilidades'])) {
        echo "<ul>";
        foreach ($resumen['debilidades'] as $debilidad) {
            echo "<li>" . htmlspecialchars($debilidad) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p><em>No hay debilidades definidas</em></p>";
    }
    
    echo "<h4>Oportunidades:</h4>";
    if (isset($resumen['oportunidades_foda']) && !empty($resumen['oportunidades_foda'])) {
        echo "<ul>";
        foreach ($resumen['oportunidades_foda'] as $oportunidad) {
            echo "<li>" . htmlspecialchars($oportunidad) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p><em>No hay oportunidades definidas</em></p>";
    }
    
    echo "<h4>Amenazas:</h4>";
    if (isset($resumen['amenazas']) && !empty($resumen['amenazas'])) {
        echo "<ul>";
        foreach ($resumen['amenazas'] as $amenaza) {
            echo "<li>" . htmlspecialchars($amenaza) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p><em>No hay amenazas definidas</em></p>";
    }
    
    echo "<h3>🎯 7. Identificación Estratégica (Textos Manuales CAME):</h3>";
    if (isset($resumen['identificacion_estrategica']) && !empty($resumen['identificacion_estrategica'])) {
        echo "<div style='background: #e8f5e8; padding: 10px; border-left: 4px solid #4caf50; margin: 10px 0;'>";
        echo "✅ <strong>ENCONTRADO:</strong> Textos manuales de la matriz CAME";
        echo "<ul>";
        if (!empty($resumen['identificacion_estrategica']['corregir'])) {
            echo "<li><strong>Corregir (Debilidades):</strong> " . htmlspecialchars($resumen['identificacion_estrategica']['corregir']) . "</li>";
        }
        if (!empty($resumen['identificacion_estrategica']['afrontar'])) {
            echo "<li><strong>Afrontar (Amenazas):</strong> " . htmlspecialchars($resumen['identificacion_estrategica']['afrontar']) . "</li>";
        }
        if (!empty($resumen['identificacion_estrategica']['mantener'])) {
            echo "<li><strong>Mantener (Fortalezas):</strong> " . htmlspecialchars($resumen['identificacion_estrategica']['mantener']) . "</li>";
        }
        if (!empty($resumen['identificacion_estrategica']['explotar'])) {
            echo "<li><strong>Explotar (Oportunidades):</strong> " . htmlspecialchars($resumen['identificacion_estrategica']['explotar']) . "</li>";
        }
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div style='background: #ffebee; padding: 10px; border-left: 4px solid #f44336; margin: 10px 0;'>";
        echo "❌ <strong>NO ENCONTRADO</strong> - Los textos manuales de identificación estratégica";
        echo "</div>";
    }
    
    echo "<h3>🚀 8. Acciones Competitivas:</h3>";
    if (isset($resumen['acciones_competitivas']) && !empty($resumen['acciones_competitivas'])) {
        echo "<ul>";
        foreach ($resumen['acciones_competitivas'] as $accion) {
            echo "<li><strong>" . $accion['tipologia_estrategia'] . "</strong> (" . $accion['relacion'] . "): " . $accion['descripcion'] . " <span style='color: #666;'>[Puntuación: " . $accion['puntuacion'] . "]</span></li>";
        }
        echo "</ul>";
    } else {
        echo "<p><em>No hay acciones competitivas definidas</em></p>";
    }
    
    echo "<h3>📝 9. Conclusiones del Usuario (Campo Manual Más Importante):</h3>";
    if (isset($resumen['conclusiones_usuario']) && !empty($resumen['conclusiones_usuario'])) {
        echo "<div style='background: #e8f5e8; padding: 15px; border-left: 4px solid #4caf50; margin: 10px 0;'>";
        echo "✅ <strong>ENCONTRADO:</strong> Conclusiones que TÚ escribiste manualmente";
        echo "<div style='background: white; padding: 10px; margin-top: 10px; border-radius: 5px;'>";
        echo nl2br(htmlspecialchars($resumen['conclusiones_usuario']));
        echo "</div>";
        echo "</div>";
    } else {
        echo "<div style='background: #ffebee; padding: 15px; border-left: 4px solid #f44336; margin: 10px 0;'>";
        echo "❌ <strong>NO ENCONTRADO</strong> - Las conclusiones que escribiste manualmente son el campo más importante";
        echo "<p>Este campo debe contener exactamente el texto que escribiste en el paso 'Resumen Ejecutivo' del wizard.</p>";
        echo "</div>";
    }
    
    echo "<hr>";
    echo "<h2>🚫 CAMPOS QUE YA NO SE MUESTRAN (ANÁLISIS AUTOMÁTICOS):</h2>";
    echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 10px 0;'>";
    echo "<p>✅ <strong>CORRECTAMENTE EXCLUIDOS:</strong></p>";
    echo "<ul>";
    echo "<li>❌ Análisis PEST automático (resumen['analisis_externo'])</li>";
    echo "<li>❌ Estadísticas automáticas (resumen['estadisticas'])</li>";
    echo "<li>❌ Conclusiones automáticas generadas por el sistema (resumen['conclusiones'])</li>";
    echo "</ul>";
    echo "<p><strong>Nota:</strong> El resumen final debe mostrar SOLO los textos que TÚ escribiste manualmente.</p>";
    echo "</div>";
    
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>ERROR:</strong> No se pudo obtener el resumen del plan";
    echo "</div>";
}

echo "<hr>";
echo "<h2>🎯 PRÓXIMOS PASOS:</h2>";
echo "<ol>";
echo "<li>Verificar que 'unidad_estrategica' aparezca en el resumen</li>";
echo "<li>Verificar que 'conclusiones_usuario' aparezca en el resumen</li>";
echo "<li>Confirmar que NO aparezcan análisis automáticos (PEST, estadísticas)</li>";
echo "<li>Probar la exportación a PDF del resumen limpio</li>";
echo "</ol>";
?>
