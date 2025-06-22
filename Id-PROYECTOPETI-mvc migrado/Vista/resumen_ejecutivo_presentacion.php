<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    header("Location: ../Vista/index.php");
    exit();
}

// Obtener el plan_id de la URL
$plan_id = $_GET['id_plan'] ?? null;

if (!$plan_id) {
    echo "<script>alert('No se encontró un plan activo.'); window.location.href = '../Vista/home.php';</script>";
    exit();
}

// Configurar conexión y modelo
require_once __DIR__ . '/../config/clsconexion.php';
require_once __DIR__ . '/../Models/PlanModel.php';

$db = (new clsConexion())->getConexion();
$model = new PlanModel($db);

// Obtener todos los datos del plan desde la base de datos
$datos_vision_mision = $model->obtenerVisionMision($plan_id);
$datos_valores = $model->obtenerValores($plan_id);
$datos_objetivos = $model->obtenerObjetivos($plan_id);
$datos_cadena_valor = $model->obtenerCadenaValor($plan_id);
$datos_bcg = $model->obtenerMatrizBCG($plan_id);
$datos_fuerzas_porter = $model->obtenerFuerzasPorter($plan_id);
$datos_pest = $model->obtenerPEST($plan_id);
$datos_estrategias = $model->obtenerEstrategias($plan_id);
$datos_matriz_came = $model->obtenerMatrizCame($plan_id);
$datos_resumen_ejecutivo = $model->obtenerResumenEjecutivo($plan_id);

// Manejar datos BCG (puede venir como JSON string)
$datos_bcg = $datos_bcg ? json_decode($datos_bcg, true) : [];

// Datos automáticos - usar datos del controlador si están disponibles
$nombre_empresa = $nombre_empresa ?? 'Mi Empresa'; // Viene del controlador
$fecha_elaboracion = $fecha_elaboracion ?? date('d/m/Y'); // Viene del controlador
$mision = $datos_vision_mision['mision'] ?? 'No definida';
$vision = $datos_vision_mision['vision'] ?? 'No definida';
$valores = $datos_valores ?? [];
$uen_descripcion = $datos_objetivos['uen_descripcion'] ?? 'No especificada';

// Procesar objetivos correctamente
$objetivos_generales = [];
$objetivos_especificos = [];

if (isset($datos_objetivos['objetivos_generales'])) {
    $obj_gen = $datos_objetivos['objetivos_generales'];
    if (is_array($obj_gen)) {
        $objetivos_generales = array_filter($obj_gen);
    } elseif (is_string($obj_gen) && !empty(trim($obj_gen))) {
        $objetivos_generales = [trim($obj_gen)];
    }
}

if (isset($datos_objetivos['objetivos_especificos'])) {
    $obj_esp = $datos_objetivos['objetivos_especificos'];
    if (is_array($obj_esp)) {
        // Los objetivos específicos pueden estar agrupados por objetivo general
        foreach ($obj_esp as $grupo) {
            if (is_array($grupo)) {
                $objetivos_especificos = array_merge($objetivos_especificos, array_filter($grupo));
            } elseif (is_string($grupo) && !empty(trim($grupo))) {
                $objetivos_especificos[] = trim($grupo);
            }
        }
    } elseif (is_string($obj_esp) && !empty(trim($obj_esp))) {
        $objetivos_especificos = [trim($obj_esp)];
    }
}

// Consolidar datos FODA desde la base de datos
$fortalezas = [];
$debilidades = [];
$oportunidades = [];
$amenazas = [];

// 1. Datos FODA de estrategias (si existen)
if ($datos_estrategias && isset($datos_estrategias['foda'])) {
    $foda_data = $datos_estrategias['foda'];
    $fortalezas = array_merge($fortalezas, $foda_data['fortalezas'] ?? []);
    $debilidades = array_merge($debilidades, $foda_data['debilidades'] ?? []);
    $oportunidades = array_merge($oportunidades, $foda_data['oportunidades'] ?? []);
    $amenazas = array_merge($amenazas, $foda_data['amenazas'] ?? []);
}

// 2. Cargar FODA desde Cadena de Valor
if ($datos_cadena_valor) {
    if (!empty($datos_cadena_valor['fortalezas'])) {
        $fortalezas = array_merge($fortalezas, $datos_cadena_valor['fortalezas']);
    }
    if (!empty($datos_cadena_valor['debilidades'])) {
        $debilidades = array_merge($debilidades, $datos_cadena_valor['debilidades']);
    }
}

// 3. Cargar FODA desde Matriz BCG
if ($datos_bcg) {
    if (!empty($datos_bcg['fortalezas'])) {
        $bcg_fortalezas = is_array($datos_bcg['fortalezas']) 
            ? $datos_bcg['fortalezas'] 
            : array_filter(explode("\n", $datos_bcg['fortalezas']));
        $fortalezas = array_merge($fortalezas, $bcg_fortalezas);
    }
    if (!empty($datos_bcg['debilidades'])) {
        $bcg_debilidades = is_array($datos_bcg['debilidades']) 
            ? $datos_bcg['debilidades'] 
            : array_filter(explode("\n", $datos_bcg['debilidades']));
        $debilidades = array_merge($debilidades, $bcg_debilidades);
    }
}

// 4. Cargar FODA desde PEST
if ($datos_pest) {
    if (!empty($datos_pest['oportunidades'])) {
        $oportunidades = array_merge($oportunidades, $datos_pest['oportunidades']);
    }
    if (!empty($datos_pest['amenazas'])) {
        $amenazas = array_merge($amenazas, $datos_pest['amenazas']);
    }
}

// 5. Cargar FODA desde Fuerzas Porter
if ($datos_fuerzas_porter) {
    if (!empty($datos_fuerzas_porter['oportunidades'])) {
        $porter_oportunidades = is_array($datos_fuerzas_porter['oportunidades']) 
            ? $datos_fuerzas_porter['oportunidades'] 
            : array_filter(explode("\n", $datos_fuerzas_porter['oportunidades']));
        $oportunidades = array_merge($oportunidades, $porter_oportunidades);
    }
    if (!empty($datos_fuerzas_porter['amenazas'])) {
        $porter_amenazas = is_array($datos_fuerzas_porter['amenazas']) 
            ? $datos_fuerzas_porter['amenazas'] 
            : array_filter(explode("\n", $datos_fuerzas_porter['amenazas']));
        $amenazas = array_merge($amenazas, $porter_amenazas);
    }
}

// Eliminar duplicados
$fortalezas = array_values(array_filter(array_unique($fortalezas)));
$debilidades = array_values(array_filter(array_unique($debilidades)));
$oportunidades = array_values(array_filter(array_unique($oportunidades)));
$amenazas = array_values(array_filter(array_unique($amenazas)));

// Acciones competitivas (estrategias de CAME)
$acciones_competitivas = [];
if (!empty($datos_matriz_came)) {
    // Buscar estrategias con patron de nombre específico (corregir_0, afrontar_1, etc.)
    foreach ($datos_matriz_came as $key => $value) {
        if (preg_match('/^(corregir|afrontar|mantener|explotar)_\d+$/', $key) && 
            is_string($value) && !empty(trim($value))) {
            $acciones_competitivas[] = trim($value);
        }
    }
    
    // También buscar en las categorías CAME tradicionales si no hay con el patrón anterior
    if (empty($acciones_competitivas)) {
        $categorias_came = ['corregir', 'afrontar', 'mantener', 'explotar'];
        foreach ($categorias_came as $categoria) {
            if (!empty($datos_matriz_came[$categoria])) {
                if (is_array($datos_matriz_came[$categoria])) {
                    foreach ($datos_matriz_came[$categoria] as $estrategia) {
                        if (is_string($estrategia) && !empty(trim($estrategia))) {
                            $acciones_competitivas[] = trim($estrategia);
                        }
                    }
                } elseif (is_string($datos_matriz_came[$categoria]) && !empty(trim($datos_matriz_came[$categoria]))) {
                    $acciones_competitivas[] = trim($datos_matriz_came[$categoria]);
                }
            }
        }
    }
}

// Cargar datos previos del resumen ejecutivo si existen
$resumen_previo = $datos_resumen_ejecutivo;
$emprendedores_promotores = $resumen_previo['emprendedores_promotores'] ?? '';
$identificacion_estrategica = $resumen_previo['identificacion_estrategica'] ?? '';
$conclusiones = $resumen_previo['conclusiones'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plan Estratégico de TI - Resumen Ejecutivo</title>
    
    <style>
        /* Estilos para presentación formal */
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
        
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
            color: #2c3e50;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        
        .documento-formal {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 40px;
        }
        
        .portada {
            text-align: center;
            padding: 60px 0;
            border-bottom: 3px solid #34495e;
            margin-bottom: 40px;
        }
        
        .portada h1 {
            font-size: 2.8em;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .portada h2 {
            font-size: 2em;
            color: #7f8c8d;
            margin-bottom: 30px;
            font-weight: 400;
        }
        
        .portada .empresa-info {
            font-size: 1.3em;
            color: #34495e;
            margin: 20px 0;
        }
        
        .portada .fecha-info {
            font-size: 1.1em;
            color: #7f8c8d;
            margin-top: 40px;
        }
        
        .seccion {
            margin-bottom: 35px;
            page-break-inside: avoid;
        }
        
        .seccion h3 {
            color: #2c3e50;
            font-size: 1.5em;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ecf0f1;
            font-weight: 600;
        }
        
        .seccion h4 {
            color: #34495e;
            font-size: 1.2em;
            margin-bottom: 10px;
            margin-top: 20px;
            font-weight: 500;
        }
        
        .contenido {
            text-align: justify;
            margin-bottom: 15px;
            font-size: 1.05em;
        }
        
        .lista-formal {
            padding-left: 0;
            list-style: none;
            margin: 15px 0;
        }
        
        .lista-formal li {
            padding: 8px 0;
            border-bottom: 1px solid #ecf0f1;
            position: relative;
            padding-left: 30px;
        }
        
        .lista-formal li:before {
            content: "▪";
            color: #3498db;
            font-weight: bold;
            position: absolute;
            left: 10px;
        }
        
        .cuadricula-foda {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin: 20px 0;
        }
        
        .cuadro-foda {
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            background-color: #fafafa;
        }
        
        .cuadro-foda h4 {
            margin-top: 0;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            color: white;
            font-weight: 600;
        }
        
        .fortalezas h4 { background-color: #27ae60; }
        .debilidades h4 { background-color: #e67e22; }
        .oportunidades h4 { background-color: #3498db; }
        .amenazas h4 { background-color: #e74c3c; }
        
        .btn-volver {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .btn-volver:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-imprimir {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #27ae60;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .btn-imprimir:hover {
            background: #229954;
            transform: translateY(-2px);
        }
        
        .texto-justificado {
            text-align: justify;
            line-height: 1.8;
            margin: 15px 0;
        }
        
        .destacado {
            background-color: #ecf0f1;
            padding: 15px;
            border-left: 4px solid #3498db;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
    </style>
</head>
<body>
    <!-- Botones de navegación (no se imprimen) -->
    <button class="btn-volver no-print" onclick="window.history.back()">
        ← Volver
    </button>
    <button class="btn-imprimir no-print" onclick="window.print()">
        🖨️ Imprimir
    </button>
    
    <div class="documento-formal">
        <!-- Portada -->
        <div class="portada">
            <h1>Plan Estratégico de Tecnologías de Información</h1>
            <h2>Resumen Ejecutivo</h2>
            <div class="empresa-info">
                <strong><?php echo htmlspecialchars($nombre_empresa); ?></strong>
            </div>
            <div class="fecha-info">
                Elaborado el: <?php echo $fecha_elaboracion; ?>
            </div>
        </div>
        
        <!-- Emprendedores/Promotores -->
        <?php if (!empty(trim($emprendedores_promotores))): ?>
        <div class="seccion">
            <h3>1. Emprendedores y Promotores</h3>
            <div class="contenido texto-justificado">
                <?php echo nl2br(htmlspecialchars($emprendedores_promotores)); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Información Corporativa -->
        <div class="seccion">
            <h3>2. Información Corporativa</h3>
            
            <h4>Misión</h4>
            <div class="contenido texto-justificado">
                <?php echo htmlspecialchars($mision); ?>
            </div>
            
            <h4>Visión</h4>
            <div class="contenido texto-justificado">
                <?php echo htmlspecialchars($vision); ?>
            </div>
            
            <?php if (!empty($valores)): ?>
            <h4>Valores Corporativos</h4>
            <ul class="lista-formal">
                <?php foreach ($valores as $valor): ?>
                    <li><?php echo htmlspecialchars($valor); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        
        <!-- Unidades Estratégicas -->
        <div class="seccion">
            <h3>3. Unidades Estratégicas de Negocio</h3>
            <div class="contenido texto-justificado">
                <?php echo htmlspecialchars($uen_descripcion); ?>
            </div>
        </div>
        
        <!-- Objetivos Estratégicos -->
        <div class="seccion">
            <h3>4. Objetivos Estratégicos</h3>
            
            <?php if (!empty($objetivos_generales)): ?>
            <h4>Objetivos Generales</h4>
            <ul class="lista-formal">
                <?php foreach ($objetivos_generales as $objetivo): ?>
                    <?php if (is_string($objetivo) && !empty(trim($objetivo))): ?>
                        <li><?php echo htmlspecialchars(trim($objetivo)); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            
            <?php if (!empty($objetivos_especificos)): ?>
            <h4>Objetivos Específicos</h4>
            <ul class="lista-formal">
                <?php foreach ($objetivos_especificos as $objetivo): ?>
                    <?php if (is_string($objetivo) && !empty(trim($objetivo))): ?>
                        <li><?php echo htmlspecialchars(trim($objetivo)); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        
        <!-- Análisis FODA -->
        <div class="seccion page-break">
            <h3>5. Análisis FODA</h3>
            <div class="cuadricula-foda">
                <div class="cuadro-foda fortalezas">
                    <h4>Fortalezas</h4>
                    <?php if (!empty($fortalezas)): ?>
                        <ul class="lista-formal">
                            <?php foreach ($fortalezas as $fortaleza): ?>
                                <li><?php echo htmlspecialchars($fortaleza); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="text-align: center; color: #7f8c8d;">No identificadas</p>
                    <?php endif; ?>
                </div>
                
                <div class="cuadro-foda debilidades">
                    <h4>Debilidades</h4>
                    <?php if (!empty($debilidades)): ?>
                        <ul class="lista-formal">
                            <?php foreach ($debilidades as $debilidad): ?>
                                <li><?php echo htmlspecialchars($debilidad); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="text-align: center; color: #7f8c8d;">No identificadas</p>
                    <?php endif; ?>
                </div>
                
                <div class="cuadro-foda oportunidades">
                    <h4>Oportunidades</h4>
                    <?php if (!empty($oportunidades)): ?>
                        <ul class="lista-formal">
                            <?php foreach ($oportunidades as $oportunidad): ?>
                                <li><?php echo htmlspecialchars($oportunidad); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="text-align: center; color: #7f8c8d;">No identificadas</p>
                    <?php endif; ?>
                </div>
                
                <div class="cuadro-foda amenazas">
                    <h4>Amenazas</h4>
                    <?php if (!empty($amenazas)): ?>
                        <ul class="lista-formal">
                            <?php foreach ($amenazas as $amenaza): ?>
                                <li><?php echo htmlspecialchars($amenaza); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="text-align: center; color: #7f8c8d;">No identificadas</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Identificación Estratégica -->
        <?php if (!empty(trim($identificacion_estrategica))): ?>
        <div class="seccion">
            <h3>6. Identificación Estratégica</h3>
            <div class="contenido texto-justificado">
                <?php echo nl2br(htmlspecialchars($identificacion_estrategica)); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Acciones Competitivas -->
        <?php if (!empty($acciones_competitivas)): ?>
        <div class="seccion">
            <h3>7. Estrategias Competitivas</h3>
            <div class="destacado">
                <p><strong>Estrategias derivadas del análisis CAME (Corregir, Afrontar, Mantener, Explotar):</strong></p>
            </div>
            <ul class="lista-formal">
                <?php foreach (array_unique($acciones_competitivas) as $accion): ?>
                    <li><?php echo htmlspecialchars($accion); ?></li>
                <?php endforeach; ?>
            </ul>
            <div style="text-align: center; margin-top: 20px; color: #7f8c8d;">
                <small><strong>Total de estrategias identificadas:</strong> <?php echo count(array_unique($acciones_competitivas)); ?></small>
            </div>
        </div>
        <?php endif; ?>
          <!-- Conclusiones -->
        <?php if (!empty(trim($conclusiones))): ?>
        <div class="seccion">
            <h3>8. Conclusiones y Recomendaciones</h3>
            <div class="contenido texto-justificado">
                <?php echo nl2br(htmlspecialchars($conclusiones)); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Botones de Exportación PDF -->
        <div style="margin: 40px 0; padding: 20px; background-color: #f8f9fa; border-radius: 8px; text-align: center; border: 1px solid #dee2e6;">
            <h4 style="color: #495057; margin-bottom: 20px; font-size: 1.1em;">
                <i class="zmdi zmdi-file-text" style="margin-right: 8px;"></i>
                Exportar Documento
            </h4>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <!-- Botón Ver PDF -->
                <button type="button" onclick="verPDF()" 
                        style="background-color: #3498db; color: white; padding: 12px 25px; font-size: 14px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 4px rgba(52,152,219,0.3); transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;">
                    <i class="zmdi zmdi-eye"></i> Visualizar PDF
                </button>
                
                <!-- Botón Descargar PDF -->
                <button type="button" onclick="descargarPDF()" 
                        style="background-color: #e74c3c; color: white; padding: 12px 25px; font-size: 14px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 4px rgba(231,76,60,0.3); transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;">
                    <i class="zmdi zmdi-download"></i> Descargar PDF
                </button>
                
                <!-- Botón Imprimir -->
                <button type="button" onclick="window.print()" 
                        style="background-color: #2ecc71; color: white; padding: 12px 25px; font-size: 14px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 4px rgba(46,204,113,0.3); transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;">
                    <i class="zmdi zmdi-print"></i> Imprimir Página
                </button>
            </div>
            <div style="margin-top: 15px;">
                <small style="color: #6c757d; font-style: italic;">
                    El PDF incluye toda la información del resumen ejecutivo con formato profesional
                </small>
            </div>
        </div>
        
        <!-- Pie de documento -->
        <div style="margin-top: 60px; padding-top: 30px; border-top: 2px solid #ecf0f1; text-align: center; color: #7f8c8d;">
            <p><strong>Plan Estratégico de Tecnologías de Información</strong></p>            <p><?php echo htmlspecialchars($nombre_empresa); ?> - <?php echo $fecha_elaboracion; ?></p>
            <p style="font-size: 0.9em;">Este documento contiene información estratégica confidencial</p>
        </div>
    </div>

    <!-- Scripts para exportación PDF -->
    <script>
        /**
         * Ver PDF en el navegador
         */
        function verPDF() {
            const planId = '<?php echo $plan_id; ?>';
            const url = `../Controllers/PlanController.php?action=verResumenPDF&id_plan=${planId}`;
            
            // Abrir PDF en nueva ventana
            window.open(url, '_blank', 'width=1000,height=800,scrollbars=yes,resizable=yes,menubar=yes,toolbar=yes');
        }
        
        /**
         * Descargar PDF
         */
        function descargarPDF() {
            const planId = '<?php echo $plan_id; ?>';
            const url = `../Controllers/PlanController.php?action=exportarResumenPDF&id_plan=${planId}`;
            
            // Mostrar mensaje de descarga en progreso
            const originalText = event.target.innerHTML;
            event.target.innerHTML = '<i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Generando PDF...';
            event.target.disabled = true;
            
            // Crear link temporal para descarga
            const link = document.createElement('a');
            link.href = url;
            link.download = '';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Restaurar botón después de un momento
            setTimeout(() => {
                event.target.innerHTML = originalText;
                event.target.disabled = false;
            }, 2000);
        }
        
        /**
         * Mejorar estilo de impresión
         */
        window.addEventListener('beforeprint', function() {
            // Ocultar botones al imprimir
            const botonesExportacion = document.querySelector('div[style*="background-color: #f8f9fa"]');
            if (botonesExportacion) {
                botonesExportacion.style.display = 'none';
            }
        });
        
        window.addEventListener('afterprint', function() {
            // Mostrar botones después de imprimir
            const botonesExportacion = document.querySelector('div[style*="background-color: #f8f9fa"]');
            if (botonesExportacion) {
                botonesExportacion.style.display = 'block';
            }
        });
        
        /**
         * Efectos hover para botones
         */
        document.addEventListener('DOMContentLoaded', function() {
            const botones = document.querySelectorAll('button[onclick*="PDF"], button[onclick*="print"]');
            
            botones.forEach(boton => {
                boton.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = this.style.boxShadow.replace('0 2px 4px', '0 4px 8px');
                });
                
                boton.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = this.style.boxShadow.replace('0 4px 8px', '0 2px 4px');
                });
            });
        });
    </script>

    <!-- Estilos para impresión -->
    <style media="print">
        @page {
            margin: 1.5cm;
            size: A4;
        }
        
        body {
            font-size: 12px !important;
            line-height: 1.4 !important;
        }
        
        .container {
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .seccion {
            page-break-inside: avoid;
            margin-bottom: 20px !important;
        }
        
        .seccion h3 {
            page-break-after: avoid;
        }
        
        /* Ocultar botones en impresión */
        button,
        div[style*="background-color: #f8f9fa"] {
            display: none !important;
        }
    </style>
</body>
</html>
