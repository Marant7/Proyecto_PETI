<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Planes Estratégicos</title>
    <link rel="stylesheet" href="/public/css/main.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-primary { background: #4CAF50; color: white; text-decoration: none; padding: 12px 24px; border-radius: 4px; font-weight: bold; }
        .btn-primary:hover { background: #45a049; }
        .planes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }
        .plan-card { background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; transition: transform 0.2s, box-shadow 0.2s; }
        .plan-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .plan-title { font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px; }
        .plan-empresa { color: #666; font-size: 14px; margin-bottom: 10px; }
        .plan-descripcion { color: #777; font-size: 13px; margin-bottom: 15px; line-height: 1.4; }
        .plan-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .plan-estado { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .estado-completado { background: #e8f5e8; color: #2e7d32; }
        .estado-borrador { background: #fff3e0; color: #f57c00; }
        .plan-fecha { font-size: 12px; color: #999; }
        .plan-acciones { display: flex; gap: 10px; }
        .btn-ver { background: #2196F3; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-size: 12px; }
        .btn-editar { background: #FF9800; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-size: 12px; }
        .btn-eliminar { background: #F44336; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-size: 12px; }
        .no-planes { text-align: center; padding: 60px; color: #666; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-number { font-size: 32px; font-weight: bold; margin-bottom: 5px; }
        .stat-label { font-size: 14px; opacity: 0.9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Mis Planes Estratégicos</h1>
            <a href="../index.php?controller=PlanEstrategico&action=iniciarPlan" class="btn-primary">
                ➕ Crear Nuevo Plan
            </a>
        </div>
        
        <?php if (!empty($planes)): ?>
            <!-- Estadísticas -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($planes); ?></div>
                    <div class="stat-label">Planes Totales</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count(array_filter($planes, function($p) { return $p['estado'] === 'completado'; })); ?></div>
                    <div class="stat-label">Completados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count(array_filter($planes, function($p) { return $p['estado'] === 'borrador'; })); ?></div>
                    <div class="stat-label">Borradores</div>
                </div>
            </div>
            
            <!-- Lista de planes -->
            <div class="planes-grid">
                <?php foreach ($planes as $plan): ?>
                    <div class="plan-card">
                        <div class="plan-title"><?php echo htmlspecialchars($plan['nombre_plan']); ?></div>
                        <div class="plan-empresa">🏢 <?php echo htmlspecialchars($plan['empresa']); ?></div>
                        
                        <?php if (!empty($plan['descripcion'])): ?>
                            <div class="plan-descripcion">
                                <?php echo htmlspecialchars(substr($plan['descripcion'], 0, 120)) . (strlen($plan['descripcion']) > 120 ? '...' : ''); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="plan-meta">
                            <span class="plan-estado estado-<?php echo $plan['estado']; ?>">
                                <?php echo ucfirst($plan['estado']); ?>
                            </span>
                            <span class="plan-fecha">
                                <?php echo date('d/m/Y', strtotime($plan['fecha_modificacion'])); ?>
                            </span>
                        </div>
                        
                        <div class="plan-acciones">
                            <a href="../index.php?controller=VisualizarPlan&action=mostrarPlan&id=<?php echo $plan['id_plan']; ?>" class="btn-ver">
                                👁️ Ver
                            </a>
                            <a href="../index.php?controller=PlanEstrategico&action=editarPlan&id=<?php echo $plan['id_plan']; ?>" class="btn-editar">
                                ✏️ Editar
                            </a>
                            <a href="#" onclick="eliminarPlan(<?php echo $plan['id_plan']; ?>)" class="btn-eliminar">
                                🗑️ Eliminar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-planes">
                <h3>🚀 ¡Comienza tu primer plan estratégico!</h3>
                <p>Aún no tienes planes estratégicos creados.</p>
                <p>Haz clic en "Crear Nuevo Plan" para comenzar.</p>
                <br>
                <a href="../index.php?controller=PlanEstrategico&action=iniciarPlan" class="btn-primary">
                    ➕ Crear Mi Primer Plan
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function eliminarPlan(idPlan) {
            if (confirm('¿Estás seguro de que deseas eliminar este plan estratégico?\n\nEsta acción no se puede deshacer.')) {
                fetch(`../index.php?controller=PlanEstrategico&action=eliminarPlan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id_plan=${idPlan}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Plan eliminado exitosamente');
                        location.reload();
                    } else {
                        alert('Error al eliminar el plan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de conexión al eliminar el plan');
                });
            }
        }
    </script>
</body>
</html>
