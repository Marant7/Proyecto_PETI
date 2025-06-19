<?php
session_start();

// Simular usuario logueado para pruebas
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id_usuario' => 1,
        'nombre' => 'Usuario de Prueba',
        'email' => 'test@test.com',
        'usuario' => 'admin'
    ];
    echo "<div style='background-color: #fffbdd; padding: 10px; border: 1px solid #e6db55; border-radius: 5px; margin-bottom: 20px;'>";
    echo "<strong>⚠️ Nota:</strong> Se ha simulado un usuario logueado para realizar las pruebas.";
    echo "</div>";
}

require_once '../Controllers/PlanEstrategicoController.php';

$controller = new PlanEstrategicoController();
$user = $_SESSION['user'];

// Obtener ID de usuario
$user_id = $user['id_usuario'] ?? $user['id'] ?? null;
$planes = $controller->obtenerPlanesUsuario($user_id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Dashboard - Planes Estratégicos</title>
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <style>
        .test-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
        }
        
        .test-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .test-header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
        }
        
        .user-info {
            margin-top: 10px;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card .icon {
            font-size: 3em;
            margin-bottom: 15px;
            color: #667eea;
        }
        
        .stat-card .number {
            font-size: 2.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-card .label {
            color: #666;
            font-size: 1.1em;
        }
        
        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }
        
        .planes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .plan-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 5px solid #667eea;
        }
        
        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .plan-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .plan-title {
            font-size: 1.4em;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        
        .plan-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-completado {
            background: #d4edda;
            color: #155724;
        }
        
        .status-en_progreso {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-borrador {
            background: #f8d7da;
            color: #721c24;
        }
        
        .plan-company {
            color: #667eea;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .plan-description {
            color: #666;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        
        .plan-meta {
            font-size: 0.9em;
            color: #888;
            margin-bottom: 20px;
        }
        
        .plan-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 15px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .plan-stat {
            text-align: center;
        }
        
        .plan-stat .number {
            font-weight: bold;
            color: #667eea;
            font-size: 1.2em;
        }
        
        .plan-stat .label {
            font-size: 0.8em;
            color: #666;
        }
        
        .plan-actions {
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }
        
        .btn-small {
            padding: 8px 16px;
            font-size: 0.9em;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-view {
            background: #667eea;
            color: white;
            flex: 1;
            text-align: center;
        }
        
        .btn-view:hover {
            background: #5a6fd8;
            transform: translateY(-1px);
        }
        
        .btn-summary {
            background: #28a745;
            color: white;
            flex: 1;
            text-align: center;
        }
        
        .btn-summary:hover {
            background: #218838;
            transform: translateY(-1px);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .empty-state .icon {
            font-size: 5em;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: #666;
            margin-bottom: 15px;
        }
        
        .empty-state p {
            color: #888;
            margin-bottom: 30px;
        }
        
        .test-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .test-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .test-links a {
            color: white;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 20px;
            transition: background 0.3s ease;
        }
        
        .test-links a:hover {
            background: rgba(255,255,255,0.3);
        }
    </style>
</head>
<body>
    <div class="test-container">
        <!-- Header -->
        <div class="test-header">
            <h1><i class="zmdi zmdi-view-dashboard"></i> Test Dashboard</h1>
            <div class="user-info">
                <i class="zmdi zmdi-account"></i> Bienvenido, <?= htmlspecialchars($user['nombre']) ?>
                <br>
                <small>Usuario ID: <?= $user_id ?></small>
            </div>
        </div>

        <!-- Información de Prueba -->
        <div class="test-info">
            <h3><i class="zmdi zmdi-info"></i> Información de Prueba</h3>
            <p>Esta es una página de prueba para verificar el correcto funcionamiento del dashboard de planes estratégicos.</p>
            <div class="test-links">
                <a href="diagnostico_sesion.php"><i class="zmdi zmdi-search"></i> Diagnóstico Sesión</a>
                <a href="verificacion_final.php"><i class="zmdi zmdi-check-all"></i> Verificación Final</a>
                <a href="home.php"><i class="zmdi zmdi-home"></i> Dashboard Real</a>
                <a href="wizard_estrategico.php"><i class="zmdi zmdi-plus"></i> Crear Plan</a>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon"><i class="zmdi zmdi-file-text"></i></div>
                <div class="number"><?= count($planes) ?></div>
                <div class="label">Planes Totales</div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="zmdi zmdi-check-circle"></i></div>
                <div class="number"><?= count(array_filter($planes, function($p) { return $p['estado'] === 'completado'; })) ?></div>
                <div class="label">Completados</div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="zmdi zmdi-time"></i></div>
                <div class="number"><?= count(array_filter($planes, function($p) { return $p['estado'] === 'en_progreso'; })) ?></div>
                <div class="label">En Progreso</div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="zmdi zmdi-edit"></i></div>
                <div class="number"><?= count(array_filter($planes, function($p) { return $p['estado'] === 'borrador'; })) ?></div>
                <div class="label">Borradores</div>
            </div>
        </div>

        <!-- Barra de Acciones -->
        <div class="actions-bar">
            <h2 style="margin: 0; color: #333;">Mis Planes Estratégicos</h2>
            <a href="wizard_estrategico.php" class="btn btn-primary">
                <i class="zmdi zmdi-plus"></i> Crear Nuevo Plan
            </a>
        </div>

        <!-- Grid de Planes -->
        <?php if (count($planes) > 0): ?>
            <div class="planes-grid">
                <?php foreach ($planes as $plan): ?>
                    <div class="plan-card">
                        <div class="plan-header">
                            <h3 class="plan-title"><?= htmlspecialchars($plan['nombre_plan']) ?></h3>
                            <span class="plan-status status-<?= $plan['estado'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $plan['estado'])) ?>
                            </span>
                        </div>
                        
                        <div class="plan-company">
                            <i class="zmdi zmdi-city"></i> <?= htmlspecialchars($plan['empresa']) ?>
                        </div>
                        
                        <?php if ($plan['descripcion']): ?>
                            <div class="plan-description">
                                <?= htmlspecialchars(substr($plan['descripcion'], 0, 150)) ?>
                                <?= strlen($plan['descripcion']) > 150 ? '...' : '' ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="plan-meta">
                            <i class="zmdi zmdi-calendar"></i> 
                            Creado: <?= date('d/m/Y', strtotime($plan['fecha_creacion'])) ?>
                        </div>
                        
                        <div class="plan-stats">
                            <div class="plan-stat">
                                <div class="number"><?= $plan['total_valores'] ?? 0 ?></div>
                                <div class="label">Valores</div>
                            </div>
                            <div class="plan-stat">
                                <div class="number"><?= ($plan['total_fortalezas'] ?? 0) + ($plan['total_debilidades'] ?? 0) + ($plan['total_oportunidades'] ?? 0) + ($plan['total_amenazas'] ?? 0) ?></div>
                                <div class="label">FODA</div>
                            </div>
                        </div>
                        
                        <div class="plan-actions">
                            <a href="ver_plan.php?id=<?= $plan['id_plan'] ?>" class="btn-small btn-view">
                                <i class="zmdi zmdi-eye"></i> Ver Plan
                            </a>
                            <a href="resumen_plan.php?id=<?= $plan['id_plan'] ?>" class="btn-small btn-summary">
                                <i class="zmdi zmdi-file-text"></i> Resumen
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="icon"><i class="zmdi zmdi-file-text"></i></div>
                <h3>No tienes planes estratégicos aún</h3>
                <p>Comienza creando tu primer plan estratégico usando nuestro wizard interactivo.</p>
                <a href="wizard_estrategico.php" class="btn btn-primary">
                    <i class="zmdi zmdi-plus"></i> Crear Mi Primer Plan
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
