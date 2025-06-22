<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    header("Location: ../Vista/index.php");
    exit();
}

// Obtener el plan_id de la sesión o URL
$plan_id = $_SESSION['plan_id'] ?? $_GET['id_plan'] ?? null;

if (!$plan_id) {
    echo "<script>alert('No se encontró un plan activo.'); window.location.href = '../Vista/home.php';</script>";
    exit();
}

// Configurar conexión y modelo
require_once __DIR__ . '/../config/clsconexion.php';
require_once __DIR__ . '/../Models/PlanModel.php';

$db = (new clsConexion())->getConexion();
$model = new PlanModel($db);

// Obtener datos previos del plan
$datos_previos = $model->obtenerEstrategias($plan_id);
$cadena_valor_data = $model->obtenerCadenaValor($plan_id);
$bcg_data = $model->obtenerMatrizBCG($plan_id);
$fuerzas_porter_data = $model->obtenerFuerzasPorter($plan_id);
$pest_data = $model->obtenerPEST($plan_id);

// Manejar correctamente los datos BCG (pueden venir como JSON string)
$bcg_data = $bcg_data ? json_decode($bcg_data, true) : [];
// Los demás métodos ya devuelven arrays decodificados
$fuerzas_porter_data = $fuerzas_porter_data ?: [];
$pest_data = $pest_data ?: [];
$cadena_valor_data = $cadena_valor_data ?: [];

// Debug temporal - verificar datos de fuentes
error_log("=== DEBUG FUENTES DE DATOS ===");
error_log("BCG data: " . print_r($bcg_data, true));
error_log("Porter data: " . print_r($fuerzas_porter_data, true));
error_log("PEST data: " . print_r($pest_data, true));
error_log("Cadena Valor data: " . print_r($cadena_valor_data, true));
error_log("==========================");

// Consolidar datos FODA desde todas las fuentes
$fortalezas_consolidadas = [];
$debilidades_consolidadas = [];
$oportunidades_consolidadas = [];
$amenazas_consolidadas = [];

// 1. Agregar datos FODA de estrategias si existen
if ($datos_previos && isset($datos_previos['foda'])) {
    $foda_data = $datos_previos['foda'];
    $fortalezas_consolidadas = array_merge($fortalezas_consolidadas, $foda_data['fortalezas'] ?? []);
    $debilidades_consolidadas = array_merge($debilidades_consolidadas, $foda_data['debilidades'] ?? []);
    $oportunidades_consolidadas = array_merge($oportunidades_consolidadas, $foda_data['oportunidades'] ?? []);
    $amenazas_consolidadas = array_merge($amenazas_consolidadas, $foda_data['amenazas'] ?? []);
}

// 2. Obtener fortalezas y debilidades de la Cadena de Valor
if ($cadena_valor_data) {
    if (!empty($cadena_valor_data['fortalezas'])) {
        $fortalezas_consolidadas = array_merge($fortalezas_consolidadas, $cadena_valor_data['fortalezas']);
    }
    if (!empty($cadena_valor_data['debilidades'])) {
        $debilidades_consolidadas = array_merge($debilidades_consolidadas, $cadena_valor_data['debilidades']);
    }
}

// 3. Obtener fortalezas y debilidades de Matriz BCG
if ($bcg_data) {
    if (!empty($bcg_data['fortalezas'])) {
        $bcg_fortalezas = is_array($bcg_data['fortalezas']) 
            ? $bcg_data['fortalezas'] 
            : array_filter(explode("\n", $bcg_data['fortalezas']));
        $fortalezas_consolidadas = array_merge($fortalezas_consolidadas, $bcg_fortalezas);
    }
    
    if (!empty($bcg_data['debilidades'])) {
        $bcg_debilidades = is_array($bcg_data['debilidades']) 
            ? $bcg_data['debilidades'] 
            : array_filter(explode("\n", $bcg_data['debilidades']));
        $debilidades_consolidadas = array_merge($debilidades_consolidadas, $bcg_debilidades);
    }
}

// 4. Obtener oportunidades y amenazas del PEST
if ($pest_data) {
    if (!empty($pest_data['oportunidades'])) {
        $oportunidades_consolidadas = array_merge($oportunidades_consolidadas, $pest_data['oportunidades']);
    }
    if (!empty($pest_data['amenazas'])) {
        $amenazas_consolidadas = array_merge($amenazas_consolidadas, $pest_data['amenazas']);
    }
}

// 5. Obtener oportunidades y amenazas de Fuerzas Porter
if ($fuerzas_porter_data) {
    if (!empty($fuerzas_porter_data['oportunidades'])) {
        $porter_oportunidades = is_array($fuerzas_porter_data['oportunidades']) 
            ? $fuerzas_porter_data['oportunidades'] 
            : array_filter(explode("\n", $fuerzas_porter_data['oportunidades']));
        $oportunidades_consolidadas = array_merge($oportunidades_consolidadas, $porter_oportunidades);
    }
    
    if (!empty($fuerzas_porter_data['amenazas'])) {
        $porter_amenazas = is_array($fuerzas_porter_data['amenazas']) 
            ? $fuerzas_porter_data['amenazas'] 
            : array_filter(explode("\n", $fuerzas_porter_data['amenazas']));        $amenazas_consolidadas = array_merge($amenazas_consolidadas, $porter_amenazas);
    }
}

// Limpiar duplicados y vacíos, y crear estructura FODA consolidada final
$foda_data = [
    'fortalezas' => array_values(array_filter(array_unique($fortalezas_consolidadas))),
    'debilidades' => array_values(array_filter(array_unique($debilidades_consolidadas))),
    'oportunidades' => array_values(array_filter(array_unique($oportunidades_consolidadas))),
    'amenazas' => array_values(array_filter(array_unique($amenazas_consolidadas)))
];

// Debug temporal - eliminar después
error_log("=== DEBUG FODA CONSOLIDACIÓN ===");
error_log("Fortalezas: " . count($foda_data['fortalezas']) . " elementos");
error_log("Debilidades: " . count($foda_data['debilidades']) . " elementos");
error_log("Oportunidades: " . count($foda_data['oportunidades']) . " elementos");
error_log("Amenazas: " . count($foda_data['amenazas']) . " elementos");
error_log("Fortalezas data: " . print_r($foda_data['fortalezas'], true));
error_log("===========================");

// Obtener datos de evaluación previa si existen
$evaluacion_data = $datos_previos['evaluacion'] ?? null;

// Debug temporal - eliminar después de confirmar que funciona
error_log("Datos previos completos: " . print_r($datos_previos, true));
if ($evaluacion_data) {
    error_log("Datos de evaluación: " . print_r($evaluacion_data, true));
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETI - Identificación de Estrategias</title>
    
    <!-- Material Design CSS -->
    <link rel="stylesheet" href="../public/css/material.min.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../public/css/normalize.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/plan-estrategico.css">
    <link rel="stylesheet" href="../public/css/sweetalert2.css">
    
    <style>
        .evaluation-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .evaluation-table th, .evaluation-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        
        .evaluation-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        
        .evaluation-select {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        .foda-section {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
        }
        
        .foda-item-readonly {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .foda-item-readonly:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .strategy-recommendation {
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
            background-color: #F5F5F5;
        }
        
        .synthesis-summary {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #E8F5E8 0%, #F1F8E9 100%);
            border-radius: 15px;
            border-left: 6px solid #4CAF50;
            animation: fadeInUp 0.5s ease;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .sidebar-nav a.active {
            background-color: #4CAF50 !important;
            color: white !important;
        }
        
        .page-content {
            margin-left: 280px;
            padding: 20px;
        }
        
        @media (max-width: 768px) {
            .page-content {
                margin-left: 0;
                padding: 10px;
            }
            .synthesis-summary .row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="page-content">
        <section class="full-width header-well">
            <div class="full-width header-well-icon">
                <i class="zmdi zmdi-assignment-check"></i>
            </div>
            <div class="full-width header-well-text">
                <p class="text-condensedLight">
                    IDENTIFICACIÓN DE ESTRATEGIAS - ANÁLISIS FODA
                </p>
            </div>
        </section>
        
        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
            <div class="mdl-tabs__tab-bar">
                <a href="#foda-tab" class="mdl-tabs__tab is-active">
                    <i class="zmdi zmdi-view-list-alt"></i> Análisis FODA
                </a>
                <a href="#evaluacion-tab" class="mdl-tabs__tab">
                    <i class="zmdi zmdi-chart-donut"></i> Evaluación Estratégica
                </a>
                <a href="#resultados-tab" class="mdl-tabs__tab">
                    <i class="zmdi zmdi-assignment-check"></i> Síntesis de Resultados
                </a>
            </div>

            <!-- Tab 1: Análisis FODA -->
            <div class="mdl-tabs__panel is-active" id="foda-tab">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col">
                        <div class="full-width panel mdl-shadow--2dp">
                            <div class="full-width panel-tittle bg-primary text-center tittles">
                                ANÁLISIS FODA CONSOLIDADO
                            </div>
                            <div class="full-width panel-content">
                                <?php 
                                $fuentes_data = [];
                                if ($cadena_valor_data && (!empty($cadena_valor_data['fortalezas']) || !empty($cadena_valor_data['debilidades']))) {
                                    $fuentes_data[] = "Cadena de Valor";
                                }
                                if ($bcg_data && (!empty($bcg_data['fortalezas']) || !empty($bcg_data['debilidades']))) {
                                    $fuentes_data[] = "Matriz BCG";
                                }
                                if ($pest_data && (!empty($pest_data['oportunidades']) || !empty($pest_data['amenazas']))) {
                                    $fuentes_data[] = "Análisis PEST";
                                }
                                if ($fuerzas_porter_data && (!empty($fuerzas_porter_data['oportunidades']) || !empty($fuerzas_porter_data['amenazas']))) {
                                    $fuentes_data[] = "Fuerzas de Porter";
                                }
                                
                                if (!empty($fuentes_data)): ?>
                                <div class="alert alert-info" style="margin-bottom: 20px; padding: 15px; background-color: #d9edf7; border: 1px solid #bce8f1; border-radius: 4px; color: #31708f;">
                                    <strong><i class="zmdi zmdi-info"></i> Datos Consolidados:</strong> 
                                    Los datos FODA han sido consolidados automáticamente desde: <strong><?= implode(', ', $fuentes_data) ?></strong>. 
                                    Esta es la síntesis final de su análisis estratégico.
                                </div>
                                <?php endif; ?>

                                <div class="mdl-grid">
                                    <!-- Fortalezas -->
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <div class="foda-section" style="background-color: #E8F5E8; padding: 20px; border-radius: 10px;">
                                            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 15px;">
                                                <h4 style="color: #2E7D32; margin: 0;">
                                                    <i class="zmdi zmdi-thumb-up"></i> FORTALEZAS
                                                </h4>
                                            </div>
                                            <div id="fortalezas-container">
                                                <?php if (!empty($foda_data['fortalezas'])): ?>
                                                    <?php foreach ($foda_data['fortalezas'] as $index => $fortaleza): ?>
                                                        <div class="foda-item-readonly" style="margin-bottom: 10px; padding: 10px; background-color: #F1F8E9; border-left: 4px solid #4CAF50; border-radius: 5px;">
                                                            <span style="color: #2E7D32; font-weight: 500;">F<?= $index + 1 ?>:</span>
                                                            <span style="color: #333; margin-left: 8px;"><?= htmlspecialchars($fortaleza) ?></span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="alert alert-info" style="text-align: center; padding: 15px; margin: 10px 0;">
                                                        <em>No hay fortalezas definidas</em>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Debilidades -->
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <div class="foda-section" style="background-color: #FFF3E0; padding: 20px; border-radius: 10px;">
                                            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 15px;">
                                                <h4 style="color: #F57C00; margin: 0;">
                                                    <i class="zmdi zmdi-thumb-down"></i> DEBILIDADES
                                                </h4>
                                            </div>
                                            <div id="debilidades-container">
                                                <?php if (!empty($foda_data['debilidades'])): ?>
                                                    <?php foreach ($foda_data['debilidades'] as $index => $debilidad): ?>
                                                        <div class="foda-item-readonly" style="margin-bottom: 10px; padding: 10px; background-color: #FFF8E1; border-left: 4px solid #FF9800; border-radius: 5px;">
                                                            <span style="color: #F57C00; font-weight: 500;">D<?= $index + 1 ?>:</span>
                                                            <span style="color: #333; margin-left: 8px;"><?= htmlspecialchars($debilidad) ?></span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="alert alert-info" style="text-align: center; padding: 15px; margin: 10px 0;">
                                                        <em>No hay debilidades definidas</em>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Oportunidades -->
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <div class="foda-section" style="background-color: #E3F2FD; padding: 20px; border-radius: 10px;">
                                            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 15px;">
                                                <h4 style="color: #1976D2; margin: 0;">
                                                    <i class="zmdi zmdi-trending-up"></i> OPORTUNIDADES
                                                </h4>
                                            </div>
                                            <div id="oportunidades-container">
                                                <?php if (!empty($foda_data['oportunidades'])): ?>
                                                    <?php foreach ($foda_data['oportunidades'] as $index => $oportunidad): ?>
                                                        <div class="foda-item-readonly" style="margin-bottom: 10px; padding: 10px; background-color: #E3F2FD; border-left: 4px solid #2196F3; border-radius: 5px;">
                                                            <span style="color: #1976D2; font-weight: 500;">O<?= $index + 1 ?>:</span>
                                                            <span style="color: #333; margin-left: 8px;"><?= htmlspecialchars($oportunidad) ?></span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="alert alert-info" style="text-align: center; padding: 15px; margin: 10px 0;">
                                                        <em>No hay oportunidades definidas</em>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Amenazas -->
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <div class="foda-section" style="background-color: #FFEBEE; padding: 20px; border-radius: 10px;">
                                            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 15px;">
                                                <h4 style="color: #D32F2F; margin: 0;">
                                                    <i class="zmdi zmdi-trending-down"></i> AMENAZAS
                                                </h4>
                                            </div>
                                            <div id="amenazas-container">
                                                <?php if (!empty($foda_data['amenazas'])): ?>
                                                    <?php foreach ($foda_data['amenazas'] as $index => $amenaza): ?>
                                                        <div class="foda-item-readonly" style="margin-bottom: 10px; padding: 10px; background-color: #FFEBEE; border-left: 4px solid #F44336; border-radius: 5px;">
                                                            <span style="color: #D32F2F; font-weight: 500;">A<?= $index + 1 ?>:</span>
                                                            <span style="color: #333; margin-left: 8px;"><?= htmlspecialchars($amenaza) ?></span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="alert alert-info" style="text-align: center; padding: 15px; margin: 10px 0;">
                                                        <em>No hay amenazas definidas</em>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mensaje de consolidación -->
                                <div class="text-center" style="margin-top: 20px; padding: 15px; background-color: #E8F5E8; border-radius: 10px;">
                                    <i class="zmdi zmdi-check-circle" style="color: #4CAF50; font-size: 24px; margin-right: 10px;"></i>
                                    <strong style="color: #2E7D32;">Análisis FODA Consolidado</strong>
                                    <p style="margin: 10px 0 0 0; color: #555;">
                                        Los datos han sido consolidados desde todos los módulos anteriores. 
                                        Proceda a la evaluación estratégica para completar el análisis.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            <!-- Tab 2: Evaluación Estratégica -->
            <div class="mdl-tabs__panel" id="evaluacion-tab">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col">
                        <form id="evaluacionForm">
                            <input type="hidden" id="id_plan" value="<?= $plan_id ?>">
                            
                            <!-- Mensaje informativo -->
                            <div class="alert alert-info" style="margin-bottom: 20px; padding: 15px; background-color: #d9edf7; border: 1px solid #bce8f1; border-radius: 4px; color: #31708f;">
                                <strong><i class="zmdi zmdi-info"></i> Evaluación Estratégica:</strong> 
                                Complete las matrices de evaluación según sus criterios estratégicos. Puede guardar parcialmente y continuar después.
                            </div>
                            
                            <!-- Fortalezas vs Oportunidades -->
                            <?php if (!empty($foda_data['fortalezas']) && !empty($foda_data['oportunidades'])): ?>
                            <div class="full-width panel mdl-shadow--2dp" style="margin-bottom: 30px;">
                                <div class="full-width panel-tittle bg-success text-center tittles">
                                    FORTALEZAS vs OPORTUNIDADES (Estrategia Ofensiva)
                                </div>
                                <div class="full-width panel-content">
                                    <p><em>Las fortalezas aprovechan las oportunidades del entorno.</em></p>
                                    <p><strong>Escala:</strong> 0=En total desacuerdo, 1=No está de acuerdo, 2=Está de acuerdo, 3=Bastante de acuerdo, 4=En total acuerdo</p>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered evaluation-table">
                                            <thead>
                                                <tr style="background-color: #FFE0B2;">
                                                    <th rowspan="2" style="background-color: #A5D6A7;">FORTALEZAS</th>
                                                    <th colspan="<?= count($foda_data['oportunidades']) ?>" style="text-align: center;">OPORTUNIDADES</th>
                                                </tr>
                                                <tr style="background-color: #FFE0B2;">
                                                    <?php for ($i = 0; $i < count($foda_data['oportunidades']); $i++): ?>
                                                        <th>O<?= $i + 1 ?></th>
                                                    <?php endfor; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for ($f = 0; $f < count($foda_data['fortalezas']); $f++): ?>
                                                <tr>
                                                    <td style="background-color: #C8E6C9;">F<?= $f + 1 ?></td>
                                                    <?php for ($o = 0; $o < count($foda_data['oportunidades']); $o++): ?>
                                                        <td>
                                                            <select name="fo_f<?= $f + 1 ?>_o<?= $o + 1 ?>" class="form-control evaluation-select fo-select" style="width: 100%; padding: 5px;">
                                                                <option value="0">0</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                            </select>
                                                        </td>
                                                    <?php endfor; ?>
                                                </tr>
                                                <?php endfor; ?>
                                                <tr style="background-color: #FFEB3B;">
                                                    <td><strong>Total</strong></td>
                                                    <?php for ($o = 0; $o < count($foda_data['oportunidades']); $o++): ?>
                                                        <td id="fo_total_o<?= $o + 1 ?>">0</td>
                                                    <?php endfor; ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="full-width panel mdl-shadow--2dp" style="margin-bottom: 30px;">
                                <div class="full-width panel-tittle bg-secondary text-center tittles">
                                    FORTALEZAS vs OPORTUNIDADES (Estrategia Ofensiva)
                                </div>
                                <div class="full-width panel-content">
                                    <div class="alert alert-warning">
                                        <strong>Sin datos:</strong> Complete primero los análisis de Fortalezas y Oportunidades en los módulos anteriores.
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Fortalezas vs Amenazas -->
                            <?php if (!empty($foda_data['fortalezas']) && !empty($foda_data['amenazas'])): ?>
                            <div class="full-width panel mdl-shadow--2dp" style="margin-bottom: 30px;">
                                <div class="full-width panel-tittle bg-info text-center tittles">
                                    FORTALEZAS vs AMENAZAS (Estrategia Defensiva)
                                </div>
                                <div class="full-width panel-content">
                                    <p><em>Las fortalezas evaden el efecto negativo de las amenazas.</em></p>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered evaluation-table">
                                            <thead>
                                                <tr style="background-color: #FFE0B2;">
                                                    <th rowspan="2" style="background-color: #A5D6A7;">FORTALEZAS</th>
                                                    <th colspan="<?= count($foda_data['amenazas']) ?>" style="text-align: center;">AMENAZAS</th>
                                                </tr>
                                                <tr style="background-color: #FFE0B2;">
                                                    <?php for ($i = 0; $i < count($foda_data['amenazas']); $i++): ?>
                                                        <th>A<?= $i + 1 ?></th>
                                                    <?php endfor; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for ($f = 0; $f < count($foda_data['fortalezas']); $f++): ?>
                                                <tr>
                                                    <td style="background-color: #C8E6C9;">F<?= $f + 1 ?></td>
                                                    <?php for ($a = 0; $a < count($foda_data['amenazas']); $a++): ?>
                                                        <td>
                                                            <select name="fa_f<?= $f + 1 ?>_a<?= $a + 1 ?>" class="form-control evaluation-select fa-select" style="width: 100%; padding: 5px;">
                                                                <option value="0">0</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                            </select>
                                                        </td>
                                                    <?php endfor; ?>
                                                </tr>
                                                <?php endfor; ?>
                                                <tr style="background-color: #FFEB3B;">
                                                    <td><strong>Total</strong></td>
                                                    <?php for ($a = 0; $a < count($foda_data['amenazas']); $a++): ?>
                                                        <td id="fa_total_a<?= $a + 1 ?>">0</td>
                                                    <?php endfor; ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="full-width panel mdl-shadow--2dp" style="margin-bottom: 30px;">
                                <div class="full-width panel-tittle bg-secondary text-center tittles">
                                    FORTALEZAS vs AMENAZAS (Estrategia Defensiva)
                                </div>
                                <div class="full-width panel-content">
                                    <div class="alert alert-warning">
                                        <strong>Sin datos:</strong> Complete primero los análisis de Fortalezas y Amenazas en los módulos anteriores.
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Debilidades vs Oportunidades -->
                            <?php if (!empty($foda_data['debilidades']) && !empty($foda_data['oportunidades'])): ?>
                            <div class="full-width panel mdl-shadow--2dp" style="margin-bottom: 30px;">
                                <div class="full-width panel-tittle bg-warning text-center tittles">
                                    DEBILIDADES vs OPORTUNIDADES (Estrategia de Supervivencia)
                                </div>
                                <div class="full-width panel-content">
                                    <p><em>Las oportunidades ayudan a superar las debilidades.</em></p>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered evaluation-table">
                                            <thead>
                                                <tr style="background-color: #FFE0B2;">
                                                    <th rowspan="2" style="background-color: #FFCC80;">DEBILIDADES</th>
                                                    <th colspan="<?= count($foda_data['oportunidades']) ?>" style="text-align: center;">OPORTUNIDADES</th>
                                                </tr>
                                                <tr style="background-color: #FFE0B2;">
                                                    <?php for ($i = 0; $i < count($foda_data['oportunidades']); $i++): ?>
                                                        <th>O<?= $i + 1 ?></th>
                                                    <?php endfor; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for ($d = 0; $d < count($foda_data['debilidades']); $d++): ?>
                                                <tr>
                                                    <td style="background-color: #FFE0B2;">D<?= $d + 1 ?></td>
                                                    <?php for ($o = 0; $o < count($foda_data['oportunidades']); $o++): ?>
                                                        <td>
                                                            <select name="do_d<?= $d + 1 ?>_o<?= $o + 1 ?>" class="form-control evaluation-select do-select" style="width: 100%; padding: 5px;">
                                                                <option value="0">0</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                            </select>
                                                        </td>
                                                    <?php endfor; ?>
                                                </tr>
                                                <?php endfor; ?>
                                                <tr style="background-color: #FFEB3B;">
                                                    <td><strong>Total</strong></td>
                                                    <?php for ($o = 0; $o < count($foda_data['oportunidades']); $o++): ?>
                                                        <td id="do_total_o<?= $o + 1 ?>">0</td>
                                                    <?php endfor; ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="full-width panel mdl-shadow--2dp" style="margin-bottom: 30px;">
                                <div class="full-width panel-tittle bg-secondary text-center tittles">
                                    DEBILIDADES vs OPORTUNIDADES (Estrategia de Supervivencia)
                                </div>
                                <div class="full-width panel-content">
                                    <div class="alert alert-warning">
                                        <strong>Sin datos:</strong> Complete primero los análisis de Debilidades y Oportunidades en los módulos anteriores.
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Debilidades vs Amenazas -->
                            <?php if (!empty($foda_data['debilidades']) && !empty($foda_data['amenazas'])): ?>
                            <div class="full-width panel mdl-shadow--2dp" style="margin-bottom: 30px;">
                                <div class="full-width panel-tittle bg-danger text-center tittles">
                                    DEBILIDADES vs AMENAZAS (Estrategia de Reorientación)
                                </div>
                                <div class="full-width panel-content">
                                    <p><em>Las debilidades exponen vulnerabilidades ante las amenazas.</em></p>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered evaluation-table">
                                            <thead>
                                                <tr style="background-color: #FFE0B2;">
                                                    <th rowspan="2" style="background-color: #FFCC80;">DEBILIDADES</th>
                                                    <th colspan="<?= count($foda_data['amenazas']) ?>" style="text-align: center;">AMENAZAS</th>
                                                </tr>
                                                <tr style="background-color: #FFE0B2;">
                                                    <?php for ($i = 0; $i < count($foda_data['amenazas']); $i++): ?>
                                                        <th>A<?= $i + 1 ?></th>
                                                    <?php endfor; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for ($d = 0; $d < count($foda_data['debilidades']); $d++): ?>
                                                <tr>
                                                    <td style="background-color: #FFE0B2;">D<?= $d + 1 ?></td>
                                                    <?php for ($a = 0; $a < count($foda_data['amenazas']); $a++): ?>
                                                        <td>
                                                            <select name="da_d<?= $d + 1 ?>_a<?= $a + 1 ?>" class="form-control evaluation-select da-select" style="width: 100%; padding: 5px;">
                                                                <option value="0">0</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                            </select>
                                                        </td>
                                                    <?php endfor; ?>
                                                </tr>
                                                <?php endfor; ?>
                                                <tr style="background-color: #FFEB3B;">
                                                    <td><strong>Total</strong></td>
                                                    <?php for ($a = 0; $a < count($foda_data['amenazas']); $a++): ?>
                                                        <td id="da_total_a<?= $a + 1 ?>">0</td>
                                                    <?php endfor; ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="full-width panel mdl-shadow--2dp" style="margin-bottom: 30px;">
                                <div class="full-width panel-tittle bg-secondary text-center tittles">
                                    DEBILIDADES vs AMENAZAS (Estrategia de Reorientación)
                                </div>
                                <div class="full-width panel-content">
                                    <div class="alert alert-warning">
                                        <strong>Sin datos:</strong> Complete primero los análisis de Debilidades y Amenazas en los módulos anteriores.
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Síntesis de Resultados -->
            <div class="mdl-tabs__panel" id="resultados-tab">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col">
                        <div class="full-width panel mdl-shadow--2dp">
                            <div class="full-width panel-tittle bg-primary text-center tittles">
                                SÍNTESIS DE RESULTADOS
                            </div>
                            <div class="full-width panel-content">
                                <div class="table-responsive">
                                    <table class="table table-bordered synthesis-table">
                                        <thead style="background-color: #B0BEC5;">
                                            <tr>
                                                <th>Relaciones</th>
                                                <th>Tipología de estrategia</th>
                                                <th>Puntuación</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                            <tr style="background-color: #C8E6C9;">
                                                <td><strong>FO</strong></td>
                                                <td>Estrategia Ofensiva</td>
                                                <td style="background-color: #FFEB3B; text-align: center; font-weight: bold;" id="total_fo">
                                                    0
                                                </td>
                                                <td id="desc_fo">
                                                    Estrategia conservadora
                                                </td>
                                            </tr>
                                            <tr style="background-color: #BBDEFB;">
                                                <td><strong>FA</strong></td>
                                                <td>Estrategia Defensiva</td>
                                                <td style="background-color: #FFEB3B; text-align: center; font-weight: bold;" id="total_fa">
                                                    0
                                                </td>
                                                <td id="desc_fa">
                                                    Necesita fortalecer defensas
                                                </td>
                                            </tr>
                                            <tr style="background-color: #FFE0B2;">
                                                <td><strong>DO</strong></td>
                                                <td>Estrategia de Supervivencia</td>
                                                <td style="background-color: #FFEB3B; text-align: center; font-weight: bold;" id="total_do">
                                                    0
                                                </td>
                                                <td id="desc_do">
                                                    Situación estable
                                                </td>
                                            </tr>
                                            <tr style="background-color: #FFCDD2;">
                                                <td><strong>DA</strong></td>
                                                <td>Estrategia de Reorientación</td>
                                                <td style="background-color: #FFEB3B; text-align: center; font-weight: bold;" id="total_da">
                                                    0
                                                </td>
                                                <td id="desc_da">
                                                    Bien posicionada para oportunidades
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Estrategia recomendada -->                                <div class="strategy-recommendation" style="margin-top: 30px; padding: 20px; border-radius: 10px; background-color: #F5F5F5;">
                                    <h4 style="color: #2E7D32; margin-bottom: 15px;">
                                        <i class="zmdi zmdi-star"></i> Estrategia Recomendada
                                    </h4>
                                    <div id="estrategia-recomendada">
                                        <div class="alert alert-info">
                                            <strong>Complete la evaluación estratégica</strong><br>
                                            La estrategia recomendada se mostrará cuando complete las evaluaciones.
                                        </div>
                                    </div>
                                </div><!-- Síntesis de Resultados Ejecutiva -->
                                <div class="synthesis-summary" style="margin-top: 30px; padding: 25px; background: linear-gradient(135deg, #E8F5E8 0%, #F1F8E9 100%); border-radius: 15px; border-left: 6px solid #4CAF50;">
                                    <h4 style="color: #2E7D32; margin-bottom: 20px; text-align: center;">
                                        <i class="zmdi zmdi-assignment-check"></i> SÍNTESIS EJECUTIVA DEL ANÁLISIS ESTRATÉGICO
                                    </h4>
                                    
                                    <div class="row" style="display: flex; flex-wrap: wrap; margin: -10px;">
                                        <!-- Resumen FODA -->
                                        <div style="flex: 1; min-width: 250px; margin: 10px; padding: 15px; background: white; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            <h5 style="color: #2E7D32; margin-bottom: 15px; border-bottom: 2px solid #E8F5E8; padding-bottom: 5px;">
                                                📊 Composición FODA
                                            </h5>
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                                <span><strong>Fortalezas:</strong></span>
                                                <span style="background: #4CAF50; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                                                    <?= count($foda_data['fortalezas']) ?> elementos
                                                </span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                                <span><strong>Debilidades:</strong></span>
                                                <span style="background: #FF9800; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                                                    <?= count($foda_data['debilidades']) ?> elementos
                                                </span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                                <span><strong>Oportunidades:</strong></span>
                                                <span style="background: #2196F3; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                                                    <?= count($foda_data['oportunidades']) ?> elementos
                                                </span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <span><strong>Amenazas:</strong></span>
                                                <span style="background: #F44336; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                                                    <?= count($foda_data['amenazas']) ?> elementos
                                                </span>
                                            </div>
                                        </div>                                        <!-- Puntuaciones Estratégicas -->
                                        <div style="flex: 1; min-width: 250px; margin: 10px; padding: 15px; background: white; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            <h5 style="color: #2E7D32; margin-bottom: 15px; border-bottom: 2px solid #E8F5E8; padding-bottom: 5px;">
                                                🎯 Evaluación Estratégica
                                            </h5>
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                                <span>FO (Ofensiva):</span>
                                                <span style="font-weight: bold; color: #4CAF50;" id="synthesis_fo">0 pts</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                                <span>FA (Defensiva):</span>
                                                <span style="font-weight: bold; color: #2196F3;" id="synthesis_fa">0 pts</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                                <span>DO (Supervivencia):</span>
                                                <span style="font-weight: bold; color: #FF9800;" id="synthesis_do">0 pts</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <span>DA (Reorientación):</span>
                                                <span style="font-weight: bold; color: #F44336;" id="synthesis_da">0 pts</span>
                                            </div>
                                        </div>
                                    </div>                                    <!-- Conclusión Principal -->
                                    <div style="margin-top: 20px; padding: 15px; background: #E8F5E8; border-radius: 10px; border: 1px solid #C8E6C9;">
                                        <h5 style="color: #2E7D32; margin-bottom: 10px; text-align: center;">
                                            💡 CONCLUSIÓN ESTRATÉGICA PRINCIPAL
                                        </h5>
                                        <p id="synthesis_conclusion" style="text-align: center; margin: 0; font-size: 16px; color: #1B5E20; font-weight: 500;">
                                            Complete la evaluación estratégica para ver la conclusión del análisis.
                                        </p>
                                    </div>
                                </div>                                <!-- Botón para guardar -->
                                <div class="text-center" style="margin-top: 30px;">
                                    <div style="background: linear-gradient(135deg, #E8F5E8 0%, #F1F8E9 100%); border: 2px solid #4CAF50; padding: 25px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15);">
                                        <h4 style="color: #2E7D32; margin: 0 0 10px 0; font-size: 20px;">
                                            ✅ ¡Análisis Estratégico Completado!
                                        </h4>
                                        <p style="margin: 0; color: #1B5E20; font-size: 16px; line-height: 1.5;">
                                            Complete la evaluación estratégica y guarde los resultados.
                                        </p>
                                    </div>
                                    <button onclick="guardarEvaluacion()" 
                                            id="guardar-estrategias-btn"
                                            class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" 
                                            style="background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%); 
                                                   color: white; 
                                                   padding: 15px 45px; 
                                                   font-size: 18px; 
                                                   font-weight: bold; 
                                                   text-transform: uppercase; 
                                                   border: none; 
                                                   border-radius: 8px; 
                                                   box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3); 
                                                   transition: all 0.3s ease; 
                                                   position: relative; 
                                                   overflow: hidden;
                                                   min-width: 200px;
                                                   letter-spacing: 1px;">
                                        <i class="zmdi zmdi-floppy" style="margin-right: 15px; font-size: 20px;"></i> 
                                        GUARDAR
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript -->
    <script src="../public/js/material.min.js"></script>    <script>        // Función para cargar datos previos si existen
        function cargarDatosPrevios() {
            <?php if ($datos_previos && isset($datos_previos['evaluacion'])): ?>            try {                const datosPreviosRaw = <?= json_encode($datos_previos['evaluacion']) ?>;
                console.log('Datos previos raw:', datosPreviosRaw);
                console.log('Tipo de datosPreviosRaw:', typeof datosPreviosRaw);
                
                // Si los datos son string, parsearlos como JSON
                let datosPrevios;
                if (typeof datosPreviosRaw === 'string') {
                    datosPrevios = JSON.parse(datosPreviosRaw);
                } else {
                    datosPrevios = datosPreviosRaw;
                }
                
                console.log('Datos previos parseados:', datosPrevios);
                console.log('Tipo después del parse:', typeof datosPrevios);
                console.log('¿Tiene evaluaciones?:', datosPrevios && datosPrevios.evaluaciones);
                console.log('Keys de datosPrevios:', datosPrevios ? Object.keys(datosPrevios) : 'null');
                
                // Verificar si los datos tienen evaluaciones
                if (datosPrevios && datosPrevios.evaluaciones) {
                    // Cargar valores en los selects de evaluación
                    Object.keys(datosPrevios.evaluaciones).forEach(nombre => {
                        const selectElement = document.querySelector(`select[name="${nombre}"]`);
                        if (selectElement) {
                            selectElement.value = datosPrevios.evaluaciones[nombre];
                            console.log(`Cargado ${nombre}: ${datosPrevios.evaluaciones[nombre]}`);
                        }
                    });
                      // Cargar totales si existen
                    if (datosPrevios.totales) {
                        const totalFo = document.getElementById('total_fo');
                        const totalFa = document.getElementById('total_fa');
                        const totalDo = document.getElementById('total_do');
                        const totalDa = document.getElementById('total_da');
                        
                        if (totalFo) totalFo.textContent = datosPrevios.totales.fo || '0';
                        if (totalFa) totalFa.textContent = datosPrevios.totales.fa || '0';
                        if (totalDo) totalDo.textContent = datosPrevios.totales.do || '0';
                        if (totalDa) totalDa.textContent = datosPrevios.totales.da || '0';
                        
                        // También actualizar la síntesis ejecutiva
                        const synthesisFo = document.getElementById('synthesis_fo');
                        const synthesisFa = document.getElementById('synthesis_fa');
                        const synthesisDo = document.getElementById('synthesis_do');
                        const synthesisDa = document.getElementById('synthesis_da');
                        
                        if (synthesisFo) synthesisFo.textContent = (datosPrevios.totales.fo || '0') + ' pts';
                        if (synthesisFa) synthesisFa.textContent = (datosPrevios.totales.fa || '0') + ' pts';
                        if (synthesisDo) synthesisDo.textContent = (datosPrevios.totales.do || '0') + ' pts';
                        if (synthesisDa) synthesisDa.textContent = (datosPrevios.totales.da || '0') + ' pts';
                    }
                    
                    // Cargar síntesis si existe
                    if (datosPrevios.sintesis) {
                        const synthesisElement = document.getElementById('synthesis_conclusion');
                        if (synthesisElement) {
                            synthesisElement.innerHTML = datosPrevios.sintesis;
                        }
                    }
                    
                    // Cargar recomendación si existe
                    if (datosPrevios.recomendacion) {
                        const recomendacionElement = document.getElementById('estrategia-recomendada');
                        if (recomendacionElement) {
                            recomendacionElement.innerHTML = datosPrevios.recomendacion;
                        }
                    }
                    
                    // Marcar que los datos fueron cargados para evitar recálculo automático
                    window.datosPreCarados = true;
                    
                    // No recalcular automáticamente, mantener los datos guardados
                    console.log('Datos previos cargados sin recálculo automático');                    
                    console.log('Datos previos cargados correctamente');
                } else {
                    console.log('No hay datos de evaluación previos para cargar');
                }
            } catch (error) {
                console.error('Error al cargar datos previos:', error);
            }
            <?php endif; ?>
        }
        
        // Cálculo automático de totales en las tablas de evaluación
        document.addEventListener('DOMContentLoaded', function() {
            const evaluationSelects = document.querySelectorAll('.evaluation-select');
            
            // Obtener cantidades desde PHP
            const numFortalezas = <?= count($foda_data['fortalezas']) ?>;
            const numDebilidades = <?= count($foda_data['debilidades']) ?>;
            const numOportunidades = <?= count($foda_data['oportunidades']) ?>;
            const numAmenazas = <?= count($foda_data['amenazas']) ?>;
              evaluationSelects.forEach(select => {
                select.addEventListener('change', function() {
                    // Reactivar cálculo automático cuando el usuario cambie valores
                    window.datosPreCarados = false;
                    calculateTotals();
                });
            });
            
            // Cargar datos previos si existen
            cargarDatosPrevios();
              // Calcular al cargar la página
            calculateTotals();
              function calculateTotals() {
                // Si ya se cargaron datos previos, no recalcular automáticamente
                if (window.datosPreCarados) {
                    console.log('Saltando recálculo automático - datos previos ya cargados');
                    return;
                }
                
                console.log('Calculando totales...');
                // Calcular totales por columna (FO)
                for (let o = 1; o <= numOportunidades; o++) {
                    let total = 0;
                    for (let f = 1; f <= numFortalezas; f++) {
                        const select = document.querySelector(`select[name="fo_f${f}_o${o}"]`);
                        if (select) {
                            total += parseInt(select.value) || 0;
                        }
                    }
                    const totalCell = document.getElementById(`fo_total_o${o}`);
                    if (totalCell) {
                        totalCell.textContent = total;
                    }
                }
                
                // Calcular totales por columna (FA)
                for (let a = 1; a <= numAmenazas; a++) {
                    let total = 0;
                    for (let f = 1; f <= numFortalezas; f++) {
                        const select = document.querySelector(`select[name="fa_f${f}_a${a}"]`);
                        if (select) {
                            total += parseInt(select.value) || 0;
                        }
                    }
                    const totalCell = document.getElementById(`fa_total_a${a}`);
                    if (totalCell) {
                        totalCell.textContent = total;
                    }
                }
                
                // Calcular totales por columna (DO)
                for (let o = 1; o <= numOportunidades; o++) {
                    let total = 0;
                    for (let d = 1; d <= numDebilidades; d++) {                        const select = document.querySelector(`select[name="do_d${d}_o${o}"]`);
                        if (select) {
                            total += parseInt(select.value) || 0;
                        }
                    }
                    const totalCell = document.getElementById(`do_total_o${o}`);
                    if (totalCell) {
                        totalCell.textContent = total;
                    }
                }
                
                // Calcular totales por columna (DA)
                for (let a = 1; a <= numAmenazas; a++) {
                    let total = 0;
                    for (let d = 1; d <= numDebilidades; d++) {
                        const select = document.querySelector(`select[name="da_d${d}_a${a}"]`);
                        if (select) {
                            total += parseInt(select.value) || 0;
                        }
                    }
                    const totalCell = document.getElementById(`da_total_a${a}`);
                    if (totalCell) {
                        totalCell.textContent = total;
                    }
                }
                
                // CALCULAR TOTALES GENERALES PARA LA SÍNTESIS
                let totalFO = 0;
                let totalFA = 0;
                let totalDO = 0;
                let totalDA = 0;
                
                // Sumar todos los valores FO
                for (let f = 1; f <= numFortalezas; f++) {
                    for (let o = 1; o <= numOportunidades; o++) {
                        const select = document.querySelector(`select[name="fo_f${f}_o${o}"]`);
                        if (select) {
                            totalFO += parseInt(select.value) || 0;
                        }
                    }
                }
                
                // Sumar todos los valores FA
                for (let f = 1; f <= numFortalezas; f++) {
                    for (let a = 1; a <= numAmenazas; a++) {
                        const select = document.querySelector(`select[name="fa_f${f}_a${a}"]`);
                        if (select) {
                            totalFA += parseInt(select.value) || 0;
                        }
                    }
                }
                
                // Sumar todos los valores DO
                for (let d = 1; d <= numDebilidades; d++) {
                    for (let o = 1; o <= numOportunidades; o++) {
                        const select = document.querySelector(`select[name="do_d${d}_o${o}"]`);
                        if (select) {
                            totalDO += parseInt(select.value) || 0;
                        }
                    }
                }
                
                // Sumar todos los valores DA
                for (let d = 1; d <= numDebilidades; d++) {
                    for (let a = 1; a <= numAmenazas; a++) {
                        const select = document.querySelector(`select[name="da_d${d}_a${a}"]`);
                        if (select) {
                            totalDA += parseInt(select.value) || 0;
                        }
                    }
                }
                  // Actualizar tabla de síntesis
                updateSynthesisTable(totalFO, totalFA, totalDO, totalDA);
            }
            
            // Función para forzar recálculo (útil cuando el usuario cambia valores)
            function forceCalculateTotals() {
                window.datosPreCarados = false;
                calculateTotals();
            }
              function updateSynthesisTable(fo, fa, do_, da) {
                // Actualizar valores en tabla de síntesis
                document.getElementById('total_fo').textContent = fo;
                document.getElementById('total_fa').textContent = fa;
                document.getElementById('total_do').textContent = do_;
                document.getElementById('total_da').textContent = da;
                
                // Actualizar valores en síntesis ejecutiva
                document.getElementById('synthesis_fo').textContent = fo + ' pts';
                document.getElementById('synthesis_fa').textContent = fa + ' pts';
                document.getElementById('synthesis_do').textContent = do_ + ' pts';
                document.getElementById('synthesis_da').textContent = da + ' pts';
                
                // Actualizar descripciones
                updateDescription('desc_fo', fo, 'fo');
                updateDescription('desc_fa', fa, 'fa');
                updateDescription('desc_do', do_, 'do');
                updateDescription('desc_da', da, 'da');
                
                // Actualizar estrategia recomendada
                updateRecommendedStrategy(fo, fa, do_, da);
                
                // Actualizar conclusión principal de síntesis ejecutiva
                updateSynthesisConclusion(fo, fa, do_, da);
            }
            
            function updateDescription(elementId, value, type) {
                const element = document.getElementById(elementId);
                if (!element) return;
                
                let description = '';
                switch(type) {
                    case 'fo':
                        if (value > 20) description = 'Deberá adoptar estrategias de crecimiento';
                        else if (value > 10) description = 'Estrategia moderada de crecimiento';
                        else description = 'Estrategia conservadora';
                        break;
                    case 'fa':
                        if (value > 20) description = 'La empresa está preparada para enfrentarse a las amenazas';
                        else if (value > 10) description = 'Preparación moderada ante amenazas';
                        else description = 'Necesita fortalecer defensas';
                        break;
                    case 'do':
                        if (value > 20) description = 'Se enfrenta a amenazas externas sin las fortalezas necesarias para luchar con la competencia';
                        else if (value > 10) description = 'Situación de supervivencia moderada';
                        else description = 'Situación estable';
                        break;
                    case 'da':
                        if (value > 20) description = 'La empresa no puede aprovechar las oportunidades porque carece de preparación adecuada';
                        else if (value > 10) description = 'Necesita reorientación moderada';
                        else description = 'Bien posicionada para oportunidades';
                        break;
                }
                element.textContent = description;
            }
            
            function updateRecommendedStrategy(fo, fa, do_, da) {
                const container = document.getElementById('estrategia-recomendada');
                if (!container) return;
                
                const totales = {fo: fo, fa: fa, do: do_, da: da};
                const maxValue = Math.max(fo, fa, do_, da);
                
                if (maxValue === 0) {
                    container.innerHTML = `
                        <div class="alert alert-info">
                            <strong>Complete la evaluación estratégica</strong><br>
                            La estrategia recomendada se mostrará cuando complete las evaluaciones.
                        </div>
                    `;
                    return;
                }
                
                let estrategia = '';
                let alertClass = '';
                
                if (fo === maxValue) {
                    estrategia = '<strong>Estrategia Ofensiva (FO)</strong><br>Su empresa debe aprovechar sus fortalezas para capitalizar las oportunidades del mercado. Enfoque en crecimiento y expansión.';
                    alertClass = 'alert-success';
                } else if (fa === maxValue) {
                    estrategia = '<strong>Estrategia Defensiva (FA)</strong><br>Utilice sus fortalezas para defenderse de las amenazas externas. Enfoque en protección y consolidación.';
                    alertClass = 'alert-info';
                } else if (do_ === maxValue) {
                    estrategia = '<strong>Estrategia de Supervivencia (DO)</strong><br>Debe superar sus debilidades aprovechando las oportunidades disponibles. Enfoque en mejora y adaptación.';
                    alertClass = 'alert-warning';
                } else if (da === maxValue) {
                    estrategia = '<strong>Estrategia de Reorientación (DA)</strong><br>Situación crítica que requiere una reorientación completa. Enfoque en reestructuración y transformación.';
                    alertClass = 'alert-danger';
                }
                  container.innerHTML = `<div class="alert ${alertClass}">${estrategia}</div>`;
            }
            
            function updateSynthesisConclusion(fo, fa, do_, da) {
                const conclusionElement = document.getElementById('synthesis_conclusion');
                if (!conclusionElement) return;
                
                const maxValue = Math.max(fo, fa, do_, da);
                
                if (maxValue === 0) {
                    conclusionElement.textContent = 'Complete la evaluación estratégica para ver la conclusión del análisis.';
                    return;
                }
                
                let conclusion = '';
                
                if (fo === maxValue) {
                    conclusion = `Su empresa tiene un <strong>perfil OFENSIVO</strong> (FO: ${maxValue} pts). Se recomienda aprovechar las fortalezas para capitalizar oportunidades de crecimiento.`;
                } else if (fa === maxValue) {
                    conclusion = `Su empresa tiene un <strong>perfil DEFENSIVO</strong> (FA: ${maxValue} pts). Se recomienda usar las fortalezas para defenderse de amenazas externas.`;
                } else if (do_ === maxValue) {
                    conclusion = `Su empresa tiene un <strong>perfil de SUPERVIVENCIA</strong> (DO: ${maxValue} pts). Se recomienda superar debilidades aprovechando oportunidades disponibles.`;
                } else if (da === maxValue) {
                    conclusion = `Su empresa requiere <strong>REORIENTACIÓN ESTRATÉGICA</strong> (DA: ${maxValue} pts). Se recomienda una reestructuración para afrontar la situación crítica.`;
                }
                
                conclusionElement.innerHTML = conclusion;
            }
        });        // Función para guardar evaluación
        function guardarEvaluacion() {
            const btnGuardar = document.getElementById('guardar-estrategias-btn');
            const originalText = btnGuardar.innerHTML;
            btnGuardar.innerHTML = '<i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Guardando...';
            btnGuardar.disabled = true;
            
            // Preparar datos para enviar
            const formData = new FormData();
            formData.append('id_plan', <?php echo $plan_id; ?>);
            
            // Recopilar todas las evaluaciones de matrices
            const evaluaciones = {};
            
            // Recopilar FO
            document.querySelectorAll('select[name^="fo_"]').forEach(select => {
                if (select.value && select.value !== '0') {
                    evaluaciones[select.name] = select.value;
                }
            });
            
            // Recopilar FA
            document.querySelectorAll('select[name^="fa_"]').forEach(select => {
                if (select.value && select.value !== '0') {
                    evaluaciones[select.name] = select.value;
                }
            });
            
            // Recopilar DO
            document.querySelectorAll('select[name^="do_"]').forEach(select => {
                if (select.value && select.value !== '0') {
                    evaluaciones[select.name] = select.value;
                }
            });
            
            // Recopilar DA
            document.querySelectorAll('select[name^="da_"]').forEach(select => {
                if (select.value && select.value !== '0') {
                    evaluaciones[select.name] = select.value;
                }
            });
            
            // Recopilar totales
            const totales = {
                fo: document.getElementById('total_fo')?.textContent || '0',
                fa: document.getElementById('total_fa')?.textContent || '0',
                do: document.getElementById('total_do')?.textContent || '0',
                da: document.getElementById('total_da')?.textContent || '0'
            };
            
            // Recopilar síntesis y recomendación
            const sintesis = document.getElementById('synthesis_conclusion')?.innerHTML || '';
            const recomendacion = document.getElementById('estrategia-recomendada')?.innerHTML || '';
              // Preparar el objeto final
            const estrategiasData = {
                evaluaciones: evaluaciones,
                totales: totales,
                sintesis: sintesis,
                recomendacion: recomendacion
            };
            
            // Enviar como evaluacion_data (formato que espera el backend)
            formData.append('evaluacion_data', JSON.stringify(estrategiasData));
            // Enviar foda_data vacío para cumplir con la validación del backend
            formData.append('foda_data', JSON.stringify({}));
              // Realizar la petición AJAX
            fetch('../index.php?controller=plan&action=guardarEstrategias', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btnGuardar.innerHTML = '<i class="zmdi zmdi-check"></i> ¡Guardado!';
                    btnGuardar.style.background = 'linear-gradient(135deg, #4CAF50 0%, #45a049 100%)';
                    
                    // Mostrar notificación de éxito
                    const alertHtml = `
                        <div class="alert alert-success" style="margin-bottom: 20px; animation: fadeInUp 0.5s ease;">
                            <strong>¡Éxito!</strong> La evaluación estratégica ha sido guardada correctamente.
                        </div>
                    `;
                    
                    btnGuardar.insertAdjacentHTML('beforebegin', alertHtml);
                    
                    setTimeout(() => {
                        btnGuardar.innerHTML = originalText;
                        btnGuardar.disabled = false;
                        btnGuardar.style.background = '';
                        
                        const alert = document.querySelector('.alert-success');
                        if (alert) alert.remove();
                    }, 3000);
                } else {
                    btnGuardar.innerHTML = originalText;
                    btnGuardar.disabled = false;
                    btnGuardar.style.background = '';
                    
                    const alertHtml = `
                        <div class="alert alert-danger" style="margin-bottom: 20px; animation: fadeInUp 0.5s ease;">
                            <strong>Error:</strong> ${data.message || 'No se pudo guardar la evaluación'}
                        </div>
                    `;
                    
                    btnGuardar.insertAdjacentHTML('beforebegin', alertHtml);
                    
                    setTimeout(() => {
                        const alert = document.querySelector('.alert-danger');
                        if (alert) alert.remove();
                    }, 5000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                btnGuardar.innerHTML = originalText;
                btnGuardar.disabled = false;
                btnGuardar.style.background = '';
                
                const alertHtml = `
                    <div class="alert alert-warning" style="margin-bottom: 20px; animation: fadeInUp 0.5s ease;">
                        <strong>Advertencia:</strong> Hubo un problema al guardar. Intente nuevamente.
                    </div>
                `;
                
                btnGuardar.insertAdjacentHTML('beforebegin', alertHtml);
                
                setTimeout(() => {
                    const alert = document.querySelector('.alert-warning');
                    if (alert) alert.remove();
                }, 5000);
            });
        }
    </script>

    <style>
        .evaluation-table {
            font-size: 14px;
        }
        
        .evaluation-table th {
            text-align: center;
            font-weight: bold;
            padding: 8px;
        }
        
        .evaluation-table td {
            text-align: center;
            padding: 5px;
        }
        
        .synthesis-table th {
            background-color: #37474F;
            color: white;
            text-align: center;
            padding: 10px;
        }
        
        .synthesis-table td {
            padding: 10px;
            text-align: center;
        }
        
        .foda-item-readonly {
            transition: all 0.3s ease;
        }
        
        .foda-item-readonly:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .synthesis-summary {
            animation: fadeInUp 0.5s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        
        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        
        .alert-info {
            color: #31708f;
            background-color: #d9edf7;
            border-color: #bce8f1;
        }
        
        .alert-warning {
            color: #8a6d3b;
            background-color: #fcf8e3;
            border-color: #faebcc;
        }
        
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }        /* Responsive design para la síntesis */
        @media (max-width: 768px) {
            .synthesis-summary .row {
                flex-direction: column;
            }
            
            .synthesis-summary .row > div {
                min-width: auto;
                margin: 10px 0;
            }
        }

        /* Estilos para el botón principal de continuar */
        #continuar-came-btn {
            cursor: pointer;
            transform: translateY(0);
        }

        #continuar-came-btn:hover {
            background: linear-gradient(135deg, #45a049 0%, #5cbf60 100%) !important;
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4) !important;
            transform: translateY(-2px);
        }

        #continuar-came-btn:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3) !important;
        }

        #continuar-came-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        #continuar-came-btn:hover::before {
            left: 100%;
        }

        /* Responsive para el botón */
        @media (max-width: 768px) {
            #continuar-came-btn {
                padding: 15px 35px !important;
                font-size: 16px !important;
                min-width: 250px !important;
            }
        }

        @media (max-width: 480px) {
            #continuar-came-btn {
                padding: 12px 25px !important;
                font-size: 14px !important;
                min-width: 200px !important;
            }
        }
    </style>
</body>
</html>
