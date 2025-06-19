<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$id_plan = $_GET['id'] ?? null;
if (!$id_plan) {
    header('Location: home.php');
    exit();
}

// Cargar datos del plan
require_once '../Controllers/PlanEstrategicoController.php';
$controller = new PlanEstrategicoController();
$plan = $controller->obtenerDetallePlan($id_plan);

if (!$plan) {
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Plan - <?php echo htmlspecialchars($plan['nombre_plan']); ?></title>
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <style>
        .plan-viewer {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Roboto', Arial, sans-serif;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .section-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .section-title {
            margin: 0;
            color: #333;
            font-size: 1.3em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-content {
            padding: 20px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #333;
            line-height: 1.5;
        }
        
        .list-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .list-item {
            padding: 12px 15px;
            background: #e9ecef;
            border-radius: 6px;
            border-left: 4px solid #007bff;
        }
        
        .foda-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .foda-section {
            border-radius: 8px;
            padding: 15px;
        }
        
        .fortalezas { background: #d4edda; border-left: 4px solid #28a745; }
        .debilidades { background: #f8d7da; border-left: 4px solid #dc3545; }
        .oportunidades { background: #d1ecf1; border-left: 4px solid #17a2b8; }
        .amenazas { background: #fff3cd; border-left: 4px solid #ffc107; }
        
        .came-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .came-item {
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid;
        }
        
        .corregir { background: #f8d7da; border-left-color: #dc3545; }
        .afrontar { background: #fff3cd; border-left-color: #ffc107; }
        .mantener { background: #d4edda; border-left-color: #28a745; }
        .explotar { background: #d1ecf1; border-left-color: #17a2b8; }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .actions-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .empty-message {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="plan-viewer">
        <!-- Header -->
        <div class="header">
            <h1><i class="zmdi zmdi-eye"></i> <?php echo htmlspecialchars($plan['nombre_plan']); ?></h1>
            <p><?php echo htmlspecialchars($plan['empresa']); ?></p>
            <p>Creado el <?php echo date('d/m/Y', strtotime($plan['fecha_creacion'])); ?></p>
        </div>

        <!-- Acciones -->
        <div class="actions-bar">
            <a href="home.php" class="btn btn-secondary">
                <i class="zmdi zmdi-arrow-left"></i> Volver al Dashboard
            </a>
            <a href="resumen_plan.php?id=<?php echo $plan['id_plan']; ?>" class="btn btn-primary">
                <i class="zmdi zmdi-file-text"></i> Ver Resumen Ejecutivo
            </a>
        </div>

        <!-- Información General -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="zmdi zmdi-info"></i> Información General
                </h2>
            </div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nombre del Plan</div>
                        <div class="info-value"><?php echo htmlspecialchars($plan['nombre_plan']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Empresa</div>
                        <div class="info-value"><?php echo htmlspecialchars($plan['empresa']); ?></div>
                    </div>
                </div>
                <?php if (!empty($plan['descripcion'])): ?>
                <div class="info-item">
                    <div class="info-label">Descripción</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan['descripcion'])); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Visión y Misión -->
        <?php if ($plan['vision_mision']): ?>
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="zmdi zmdi-eye"></i> Visión y Misión
                </h2>
            </div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Visión</div>
                        <div class="info-value"><?php echo nl2br(htmlspecialchars($plan['vision_mision']['vision'])); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Misión</div>
                        <div class="info-value"><?php echo nl2br(htmlspecialchars($plan['vision_mision']['mision'])); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Valores -->
        <?php if (!empty($plan['valores'])): ?>
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="zmdi zmdi-favorite"></i> Valores Corporativos
                </h2>
            </div>
            <div class="section-content">
                <div class="list-grid">
                    <?php foreach ($plan['valores'] as $valor): ?>
                    <div class="list-item">
                        <strong><?php echo htmlspecialchars($valor['valor']); ?></strong><br>
                        <small><?php echo htmlspecialchars($valor['descripcion']); ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Objetivos Estratégicos -->
        <?php if (!empty($plan['objetivos'])): ?>
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="zmdi zmdi-target"></i> Objetivos Estratégicos
                </h2>
            </div>
            <div class="section-content">
                <?php 
                $objetivos_generales = array_filter($plan['objetivos'], function($obj) { return $obj['categoria'] === 'general'; });
                $objetivos_especificos = array_filter($plan['objetivos'], function($obj) { return $obj['categoria'] === 'especifico'; });
                ?>
                
                <?php if (!empty($objetivos_generales)): ?>
                <h4>Objetivos Generales</h4>
                <div class="list-grid">
                    <?php foreach ($objetivos_generales as $objetivo): ?>
                    <div class="list-item">
                        <?php echo htmlspecialchars($objetivo['objetivo']); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($objetivos_especificos)): ?>
                <h4 style="margin-top: 20px;">Objetivos Específicos</h4>
                <div class="list-grid">
                    <?php foreach ($objetivos_especificos as $objetivo): ?>
                    <div class="list-item">
                        <?php echo htmlspecialchars($objetivo['objetivo']); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Análisis PEST -->
        <?php if ($plan['pest']): ?>
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="zmdi zmdi-globe"></i> Análisis PEST
                </h2>
            </div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Factor Político</div>
                        <div class="info-value"><?php echo nl2br(htmlspecialchars($plan['pest']['factor_politico'])); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Factor Económico</div>
                        <div class="info-value"><?php echo nl2br(htmlspecialchars($plan['pest']['factor_economico'])); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Factor Social</div>
                        <div class="info-value"><?php echo nl2br(htmlspecialchars($plan['pest']['factor_social'])); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Factor Tecnológico</div>
                        <div class="info-value"><?php echo nl2br(htmlspecialchars($plan['pest']['factor_tecnologico'])); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Análisis FODA -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="zmdi zmdi-chart"></i> Análisis FODA
                </h2>
            </div>
            <div class="section-content">
                <div class="foda-grid">
                    <div class="foda-section fortalezas">
                        <h4><i class="zmdi zmdi-trending-up"></i> Fortalezas</h4>
                        <?php if (!empty($plan['fortalezas'])): ?>
                            <?php foreach ($plan['fortalezas'] as $item): ?>
                            <div class="list-item" style="margin-bottom: 10px; background: white;">
                                <?php echo htmlspecialchars($item['descripcion']); ?>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-message">No hay fortalezas registradas</div>
                        <?php endif; ?>
                    </div>

                    <div class="foda-section debilidades">
                        <h4><i class="zmdi zmdi-trending-down"></i> Debilidades</h4>
                        <?php if (!empty($plan['debilidades'])): ?>
                            <?php foreach ($plan['debilidades'] as $item): ?>
                            <div class="list-item" style="margin-bottom: 10px; background: white;">
                                <?php echo htmlspecialchars($item['descripcion']); ?>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-message">No hay debilidades registradas</div>
                        <?php endif; ?>
                    </div>

                    <div class="foda-section oportunidades">
                        <h4><i class="zmdi zmdi-plus"></i> Oportunidades</h4>
                        <?php if (!empty($plan['oportunidades'])): ?>
                            <?php foreach ($plan['oportunidades'] as $item): ?>
                            <div class="list-item" style="margin-bottom: 10px; background: white;">
                                <?php echo htmlspecialchars($item['descripcion']); ?>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-message">No hay oportunidades registradas</div>
                        <?php endif; ?>
                    </div>

                    <div class="foda-section amenazas">
                        <h4><i class="zmdi zmdi-alert-triangle"></i> Amenazas</h4>
                        <?php if (!empty($plan['amenazas'])): ?>
                            <?php foreach ($plan['amenazas'] as $item): ?>
                            <div class="list-item" style="margin-bottom: 10px; background: white;">
                                <?php echo htmlspecialchars($item['descripcion']); ?>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-message">No hay amenazas registradas</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matriz CAME -->
        <?php if ($plan['matriz_came']): ?>
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="zmdi zmdi-matrix"></i> Matriz CAME
                </h2>
            </div>
            <div class="section-content">
                <div class="came-grid">
                    <div class="came-item corregir">
                        <h4><i class="zmdi zmdi-wrench"></i> Corregir (Debilidades)</h4>
                        <div><?php echo nl2br(htmlspecialchars($plan['matriz_came']['corregir'] ?? 'No especificado')); ?></div>
                    </div>

                    <div class="came-item afrontar">
                        <h4><i class="zmdi zmdi-shield-security"></i> Afrontar (Amenazas)</h4>
                        <div><?php echo nl2br(htmlspecialchars($plan['matriz_came']['afrontar'] ?? 'No especificado')); ?></div>
                    </div>

                    <div class="came-item mantener">
                        <h4><i class="zmdi zmdi-check"></i> Mantener (Fortalezas)</h4>
                        <div><?php echo nl2br(htmlspecialchars($plan['matriz_came']['mantener'] ?? 'No especificado')); ?></div>
                    </div>

                    <div class="came-item explotar">
                        <h4><i class="zmdi zmdi-trending-up"></i> Explotar (Oportunidades)</h4>
                        <div><?php echo nl2br(htmlspecialchars($plan['matriz_came']['explotar'] ?? 'No especificado')); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
