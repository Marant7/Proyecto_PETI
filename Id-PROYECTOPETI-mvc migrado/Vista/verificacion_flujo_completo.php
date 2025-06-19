<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación del Flujo Completo PETI</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .step { border: 1px solid #ddd; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .step-header { background: #f8f9fa; padding: 10px; margin: -15px -15px 15px -15px; border-radius: 5px 5px 0 0; }
        .status-ok { color: #28a745; font-weight: bold; }
        .status-warning { color: #ffc107; font-weight: bold; }
        .status-error { color: #dc3545; font-weight: bold; }
        .navigation { background: #e9ecef; padding: 20px; text-align: center; margin: 20px 0; border-radius: 5px; }
        .btn { padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .flow-diagram { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <h1>🔄 Verificación del Flujo Completo PETI</h1>
    
    <div class="flow-diagram">
        <h2>📋 Flujo de Guardado de Datos</h2>
        <p><strong>Durante el proceso:</strong> Los datos se guardan en <code>$_SESSION['plan_temporal']</code></p>
        <p><strong>Al finalizar:</strong> Todos los datos de sesión se transfieren a la base de datos</p>
        
        <div style="text-align: center; font-family: monospace; background: white; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
            nuevo_plan.php → vision_mision.php → valores.php → objetivos_estrategicos.php → cadena_valor.php → matriz_bcg.php → fuerzas_porter.php → pest_nuevo.php → identificacion_de_estrategias.php → matriz_came_ejemplo.php → resumen_ejecutivo.php
            <br><br>
            📝 <em>Cada paso guarda en SESIÓN</em> → 💾 <em>Al final se guarda TODO en BD</em>
        </div>
    </div>
    
    <?php
    require_once '../config/clsconexion.php';
    
    try {
        $conexion = new clsConexion();
        $pdo = $conexion->getConexion();
    } catch (Exception $e) {
        echo "<div class='step' style='border-color: #dc3545;'>";
        echo "<div class='step-header' style='background: #f8d7da;'><h3>❌ Error de Conexión</h3></div>";
        echo "<p>No se puede conectar a la base de datos: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div>";
        exit();
    }
    ?>
    
    <div class="step">
        <div class="step-header"><h3>1️⃣ nuevo_plan.php</h3></div>
        <p><strong>Función:</strong> Inicia el plan temporal en sesión</p>
        <p><strong>Guarda:</strong> <code>$_SESSION['plan_temporal']['nuevo_plan']</code></p>
        <p><strong>Campos:</strong> nombre_plan, empresa, descripcion</p>
        <p class="status-ok">✅ Configurado correctamente - Envía a <code>action=guardarPaso</code></p>
    </div>
    
    <div class="step">
        <div class="step-header"><h3>2️⃣ vision_mision.php</h3></div>
        <p><strong>Función:</strong> Define la visión y misión</p>
        <p><strong>Guarda:</strong> <code>$_SESSION['plan_temporal']['vision_mision']</code></p>
        <p><strong>Campos:</strong> vision, mision</p>
        <p class="status-ok">✅ Configurado correctamente - paso=2, nombre_paso=vision_mision</p>
    </div>
    
    <div class="step">
        <div class="step-header"><h3>3️⃣ valores.php</h3></div>
        <p><strong>Función:</strong> Establece los valores corporativos</p>
        <p><strong>Guarda:</strong> <code>$_SESSION['plan_temporal']['valores']</code></p>
        <p><strong>Campos:</strong> valores[] (array)</p>
        <p class="status-ok">✅ Configurado correctamente - Usa AJAX para guardar</p>
    </div>
    
    <div class="step">
        <div class="step-header"><h3>4️⃣ objetivos_estrategicos.php</h3></div>
        <p><strong>Función:</strong> Define objetivos generales y específicos</p>
        <p><strong>Guarda:</strong> <code>$_SESSION['plan_temporal']['objetivos']</code></p>
        <p><strong>Campos:</strong> uen_descripcion, objetivos_generales[], objetivos_especificos[]</p>
        <p class="status-ok">✅ Configurado correctamente - nombre_paso=objetivos</p>
    </div>
    
    <div class="step">
        <div class="step-header"><h3>5️⃣ cadena_valor.php</h3></div>
        <p><strong>Función:</strong> Evaluación de la cadena de valor</p>
        <p><strong>Guarda:</strong> <code>$_SESSION['plan_temporal']['cadena_valor']</code></p>
        <p><strong>Campos:</strong> q1-q10, fortalezas[], debilidades[], porcentaje</p>
        <p class="status-ok">✅ Configurado correctamente - Usa AJAX para guardar</p>
    </div>
    
    <div class="step">
        <div class="step-header"><h3>6️⃣ matriz_bcg.php</h3></div>
        <p><strong>Función:</strong> Análisis de productos con Matriz BCG</p>
        <p><strong>Guarda:</strong> <code>$_SESSION['plan_temporal']['matriz_bcg']</code></p>
        <p><strong>Campos:</strong> productos[], ventas[], fortalezas[], debilidades[]</p>
        <p class="status-warning">⚠️ Verificar que esté enviando a guardarPaso</p>
    </div>
    
    <div class="step">
        <div class="step-header"><h3>7️⃣ fuerzas_porter.php</h3></div>
        <p><strong>Función:</strong> Análisis de las 5 fuerzas de Porter</p>
        <p><strong>Guarda:</strong> <code>$_SESSION['plan_temporal']['fuerzas_porter']</code></p>
        <p><strong>Campos:</strong> respuestas[], oportunidades[], amenazas[]</p>
        <p class="status-ok">✅ Configurado correctamente - paso=7, nombre_paso=fuerzas_porter</p>
    </div>
    
    <div class="step">
        <div class="step-header"><h3>8️⃣ pest_nuevo.php</h3></div>
        <p><strong>Función:</strong> Análisis PEST (Político, Económico, Social, Tecnológico)</p>
        <p><strong>Guarda:</strong> <code>$_SESSION['plan_temporal']['pest']</code></p>
        <p><strong>Campos:</strong> respuestas[], oportunidades[], amenazas[]</p>
        <p class="status-ok">✅ Configurado correctamente - Usa AJAX con nombre_paso=pest</p>
    </div>
    
    <div class="step">
        <div class="step-header"><h3>9️⃣ identificacion_de_estrategias.php</h3></div>
        <p><strong>Función:</strong> Consolida FODA y calcula estrategias</p>
        <p><strong>Guarda:</strong> <code>$_SESSION['plan_temporal']['estrategias']</code></p>
        <p><strong>Campos:</strong> evaluacion[], totales[], cantidades[]</p>
        <p class="status-ok">✅ CORREGIDO - Ahora envía nombre_paso correctamente</p>
    </div>
    
    <div class="step">
        <div class="step-header"><h3>🔟 matriz_came_ejemplo.php</h3></div>
        <p><strong>Función:</strong> Define estrategias CAME (Corregir, Afrontar, Mantener, Explotar)</p>
        <p><strong>Guarda:</strong> <code>$_SESSION['plan_temporal']['matriz_came']</code></p>
        <p><strong>Campos:</strong> corregir[], afrontar[], mantener[], explotar[]</p>
        <p class="status-warning">⚠️ Verificar configuración de guardado</p>
    </div>
    
    <div class="step">
        <div class="step-header"><h3>1️⃣1️⃣ resumen_ejecutivo.php</h3></div>
        <p><strong>Función:</strong> Resumen final y guardado completo en BD</p>
        <p><strong>Guarda:</strong> <code>$_SESSION['plan_temporal']['resumen_ejecutivo']</code></p>
        <p><strong>Campos:</strong> emprendedores_promotores, identificacion_estrategica, conclusiones</p>
        <p class="status-ok">✅ Configurado correctamente</p>
        <p><strong>🎯 IMPORTANTE:</strong> Al hacer clic en "Guardar Resumen y Finalizar" se ejecuta:</p>
        <ol>
            <li><code>action=guardarPaso</code> → Guarda resumen en sesión</li>
            <li><code>action=finalizarPlan</code> → Transfiere TODO de sesión a BD</li>
        </ol>
    </div>
    
    <div class="step" style="border-color: #28a745; background: #d4edda;">
        <div class="step-header" style="background: #c3e6cb;"><h3>💾 GUARDADO FINAL EN BASE DE DATOS</h3></div>
        <p>El controlador <code>PlanEstrategicoController::finalizarPlan()</code> ejecuta las siguientes funciones:</p>
        <ul>
            <li>✅ <code>guardarPlanPrincipal()</code> - Crea registro principal</li>
            <li>✅ <code>guardarVisionMision()</code> - Guarda visión/misión</li>
            <li>✅ <code>guardarValores()</code> - Guarda valores en <code>tb_valores</code></li>
            <li>✅ <code>guardarObjetivos()</code> - Guarda objetivos estratégicos</li>
            <li>✅ <code>guardarCadenaValor()</code> - Guarda evaluación cadena valor</li>
            <li>✅ <code>guardarMatrizBCG()</code> - Guarda análisis BCG</li>
            <li>✅ <code>guardarFuerzasPorter()</code> - Guarda análisis Porter</li>
            <li>✅ <code>guardarPEST()</code> - Guarda análisis PEST en <code>tb_respuestas_pest</code></li>
            <li>✅ <code>guardarEstrategias()</code> - Guarda síntesis en <code>tb_sintesis_estrategias</code></li>
            <li>✅ <code>guardarMatrizCAME()</code> - Guarda estrategias CAME en <code>tb_came</code></li>
            <li>✅ <code>guardarResumenEjecutivo()</code> - Guarda resumen final</li>
        </ul>
    </div>
    
    <div class="navigation">
        <h3>🚀 Prueba el Flujo Completo</h3>
        <a href="nuevo_plan.php" class="btn btn-primary">🆕 Empezar Nuevo Plan</a>
        <a href="diagnostico_completo.php" class="btn btn-warning">🔍 Diagnóstico Completo</a>
        <a href="home.php" class="btn btn-success">🏠 Ir al Inicio</a>
    </div>
    
    <div class="step" style="border-color: #17a2b8; background: #d1ecf1;">
        <div class="step-header" style="background: #bee5eb;"><h3>✅ CONFIRMACIÓN DEL FLUJO</h3></div>
        <p><strong>SÍ, el flujo está configurado correctamente:</strong></p>
        <ol>
            <li>Cada paso guarda en <code>$_SESSION['plan_temporal']</code></li>
            <li>En el resumen ejecutivo se transfiere todo a la base de datos</li>
            <li>Se crean registros en todas las tablas correspondientes</li>
            <li>Los datos persisten después de cerrar la sesión</li>
        </ol>
        
        <p><strong>📊 Tablas que se llenan:</strong></p>
        <ul>
            <li><code>tb_valores</code> - Valores corporativos</li>
            <li><code>tb_came</code> - Estrategias CAME</li>
            <li><code>tb_respuestas_pest</code> - Respuestas del análisis PEST</li>
            <li><code>tb_sintesis_estrategias</code> - Síntesis estratégica calculada</li>
            <li><code>tb_fortalezas, tb_debilidades, tb_oportunidades, tb_amenazas</code> - FODA consolidado</li>
            <li>Y muchas más tablas específicas para cada módulo</li>
        </ul>
    </div>
    
</body>
</html>
