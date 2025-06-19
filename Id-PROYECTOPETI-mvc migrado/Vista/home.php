<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
$user = $_SESSION['user'];

// Cargar planes del usuario
require_once '../Controllers/PlanEstrategicoController.php';
$controller = new PlanEstrategicoController();

// Obtener ID de usuario de manera más robusta
$user_id = $user['id_usuario'] ?? $user['id'] ?? null;
if (!$user_id) {
    error_log("Error: No se pudo obtener el ID del usuario de la sesión");
    $planes = [];
} else {
    $planes = $controller->obtenerPlanesUsuario($user_id);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Planes Estratégicos</title>
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
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
        
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
        }
        
        .user-info {
            margin-top: 10px;
            opacity: 0.9;
        }
        
        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn {
            padding: 12px 24px;
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
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }
        
        .btn-info {
            background-color: #17a2b8;
            color: white;
        }
        
        .btn-info:hover {
            background-color: #138496;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #218838;
        }
        
        .planes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .plan-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .plan-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 20px;
        }
        
        .plan-title {
            font-size: 1.4em;
            font-weight: 600;
            margin: 0 0 5px 0;
        }
        
        .plan-empresa {
            opacity: 0.9;
            font-size: 1.1em;
        }
        
        .plan-body {
            padding: 20px;
        }
        
        .plan-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .meta-item {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .meta-value {
            font-size: 1.5em;
            font-weight: bold;
            color: #007bff;
        }
        
        .meta-label {
            font-size: 0.8em;
            color: #666;
            margin-top: 5px;
        }
        
        .plan-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .plan-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 1.2em;
            font-weight: bold;
            color: #28a745;
        }
        
        .stat-label {
            font-size: 0.8em;
            color: #666;
        }
        
        .plan-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9em;
            flex: 1;
            text-align: center;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .empty-icon {
            font-size: 4em;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .estado-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .estado-completado {
            background-color: #d4edda;
            color: #155724;
        }
        
        .estado-borrador {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .fecha-texto {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <h1><i class="zmdi zmdi-assignment"></i> Dashboard de Planes Estratégicos</h1>
            <div class="user-info">
                <p>Bienvenido, <strong><?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></strong></p>
                <p>Usuario: <?php echo htmlspecialchars($user['usuario']); ?></p>
            </div>
        </div>

        <!-- Barra de acciones -->
        <div class="actions-bar">
            <div>
                <h2 style="margin: 0; color: #333;">Mis Planes Estratégicos</h2>
                <p style="margin: 5px 0 0 0; color: #666;">Total: <?php echo count($planes); ?> planes</p>
            </div>
            <div>
                <a href="nuevo_plan.php" class="btn btn-primary">
                    <i class="zmdi zmdi-plus"></i> Nuevo Plan
                </a>
                <a href="logout.php" class="btn btn-danger">
                    <i class="zmdi zmdi-power"></i> Cerrar Sesión
                </a>
            </div>
        </div>

        <!-- Grid de planes -->
        <?php if (empty($planes)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="zmdi zmdi-assignment"></i>
            </div>
            <h3>No tienes planes estratégicos aún</h3>
            <p>Crea tu primer plan estratégico para comenzar</p>
            <a href="nuevo_plan.php" class="btn btn-primary">
                <i class="zmdi zmdi-plus"></i> Crear Primer Plan
            </a>
        </div>
        <?php else: ?>
        <div class="planes-grid">
            <?php foreach ($planes as $plan): ?>
            <div class="plan-card">
                <div class="plan-header">
                    <h3 class="plan-title"><?php echo htmlspecialchars($plan['nombre_plan']); ?></h3>
                    <p class="plan-empresa"><?php echo htmlspecialchars($plan['empresa']); ?></p>
                </div>
                
                <div class="plan-body">
                    <div class="plan-meta">
                        <div class="meta-item">
                            <div class="meta-value"><?php echo date('d/m/Y', strtotime($plan['fecha_creacion'])); ?></div>
                            <div class="meta-label">Fecha de Creación</div>
                        </div>
                        <div class="meta-item">
                            <span class="estado-badge estado-<?php echo $plan['estado']; ?>">
                                <?php echo ucfirst($plan['estado']); ?>
                            </span>
                        </div>
                    </div>

                    <?php if (!empty($plan['descripcion'])): ?>
                    <div class="plan-description">
                        <?php echo htmlspecialchars(substr($plan['descripcion'], 0, 120)); ?>
                        <?php if (strlen($plan['descripcion']) > 120): ?>...<?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="plan-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $plan['total_valores']; ?></div>
                            <div class="stat-label">Valores</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $plan['total_fortalezas']; ?></div>
                            <div class="stat-label">Fortalezas</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $plan['total_oportunidades']; ?></div>
                            <div class="stat-label">Oportunidades</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $plan['total_amenazas']; ?></div>
                            <div class="stat-label">Amenazas</div>
                        </div>
                    </div>

                    <div class="plan-actions">
                        <a href="ver_plan.php?id=<?php echo $plan['id_plan']; ?>" class="btn btn-info btn-sm">
                            <i class="zmdi zmdi-eye"></i> Ver Plan
                        </a>
                        <a href="resumen_plan.php?id=<?php echo $plan['id_plan']; ?>" class="btn btn-success btn-sm">
                            <i class="zmdi zmdi-file-text"></i> Resumen
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Animación suave al cargar las cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.plan-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>
