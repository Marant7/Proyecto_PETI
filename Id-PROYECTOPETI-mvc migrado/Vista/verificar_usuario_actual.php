<?php
session_start();

// Script para verificar el usuario actual y crear plan de prueba
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación Usuario Actual</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }
        .btn-primary { background-color: #007bff; }
        .btn-success { background-color: #28a745; }
        .btn-info { background-color: #17a2b8; }
        pre { background-color: #f8f9fa; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Verificación Usuario Actual</h1>
        
        <?php
        // Verificar sesión actual
        if (isset($_SESSION['user'])) {
            echo '<div class="success">';
            echo '<h3>✅ Sesión Activa Detectada</h3>';
            echo '<strong>Datos del usuario:</strong><br>';
            echo '<pre>' . print_r($_SESSION['user'], true) . '</pre>';
            echo '</div>';
            
            $user = $_SESSION['user'];
            $user_id = $user['id_usuario'] ?? $user['id'] ?? null;
            
            if ($user_id) {
                echo '<div class="info">';
                echo "<h3>👤 Usuario: {$user['nombre_usuario']}</h3>";
                echo "<p><strong>ID:</strong> $user_id</p>";
                echo "<p><strong>Email:</strong> {$user['email_usuario']}</p>";
                echo '</div>';
                
                // Verificar planes existentes
                try {
                    require_once '../config/clsconexion.php';
                    require_once '../Controllers/PlanEstrategicoController.php';
                    
                    $controller = new PlanEstrategicoController();
                    $planes = $controller->obtenerPlanesUsuario($user_id);
                    
                    echo '<div class="info">';
                    echo '<h3>📋 Planes Existentes</h3>';
                    echo '<p><strong>Total de planes:</strong> ' . count($planes) . '</p>';
                    
                    if (count($planes) > 0) {
                        echo '<h4>Lista de planes:</h4>';
                        foreach ($planes as $plan) {
                            echo "<p>• ID: {$plan['id']}, Empresa: {$plan['nombre_empresa']}, Fecha: {$plan['fecha_creacion']}</p>";
                        }
                    } else {
                        echo '<p>No se encontraron planes para este usuario.</p>';
                        
                        // Ofrecer crear plan de prueba
                        if (isset($_POST['crear_plan_prueba'])) {
                            echo '<div class="success">';
                            echo '<h4>🚀 Creando Plan de Prueba...</h4>';
                            
                            try {
                                // Crear datos de prueba completos
                                $datos_plan = [
                                    'nombre_empresa' => 'TechCorp Solutions',
                                    'mision' => 'Proporcionar soluciones tecnológicas innovadoras que transformen la manera en que las empresas operan.',
                                    'vision' => 'Ser la empresa líder en transformación digital en América Latina para el año 2030.',
                                    'valores' => 'Innovación, Integridad, Excelencia, Colaboración, Compromiso Social'
                                ];
                                
                                // Datos PEST
                                $datos_pest = [
                                    'politicos' => 'Regulaciones favorables para la transformación digital, incentivos gubernamentales para startups tecnológicas.',
                                    'economicos' => 'Crecimiento del mercado tecnológico, inversión en digitalización post-pandemia.',
                                    'sociales' => 'Mayor adopción de tecnología por parte de empresas tradicionales, demanda de soluciones remotas.',
                                    'tecnologicos' => 'Avances en IA, cloud computing, y automatización. Necesidad de ciberseguridad.'
                                ];
                                
                                // Datos FODA
                                $fortalezas = ['Equipo especializado', 'Tecnología de vanguardia', 'Experiencia en el mercado'];
                                $debilidades = ['Recursos limitados', 'Dependencia de tecnología externa'];
                                $oportunidades = ['Mercado en crecimiento', 'Digitalización acelerada', 'Nuevos nichos de mercado'];
                                $amenazas = ['Competencia internacional', 'Cambios tecnológicos rápidos', 'Crisis económicas'];
                                
                                // Crear sesión temporal
                                $_SESSION['plan_temporal'] = [
                                    'id_usuario' => $user_id,
                                    'fecha_inicio' => date('Y-m-d H:i:s'),
                                    'paso_actual' => 5,
                                    'datos' => [
                                        'paso1' => $datos_plan,
                                        'paso2' => $datos_pest,
                                        'paso3' => [
                                            'fortalezas' => $fortalezas,
                                            'debilidades' => $debilidades,
                                            'oportunidades' => $oportunidades,
                                            'amenazas' => $amenazas
                                        ],
                                        'paso4' => [
                                            'estrategias_fo' => ['Aprovechar el crecimiento del mercado con nuestro equipo especializado'],
                                            'estrategias_fa' => ['Usar nuestra experiencia para diferenciarnos de la competencia internacional'],
                                            'estrategias_do' => ['Conseguir inversión para aprovechar las oportunidades de mercado'],
                                            'estrategias_da' => ['Desarrollar partnerships para mitigar riesgos tecnológicos']
                                        ],
                                        'paso5' => [
                                            'objetivos_estrategicos' => 'Incrementar la participación de mercado en un 30% en los próximos 2 años',
                                            'estrategias_principales' => 'Enfoque en soluciones de IA y automatización para PYMEs',
                                            'indicadores_clave' => 'ROI, satisfacción del cliente, crecimiento de ventas',
                                            'cronograma' => '2025-2027: Expansión gradual con hitos trimestrales'
                                        ]
                                    ]
                                ];
                                
                                // Guardar plan completo
                                $resultado = $controller->guardarPlanCompleto();
                                
                                if (isset($resultado['success']) && $resultado['success']) {
                                    echo '<p>✅ Plan de prueba creado exitosamente!</p>';
                                    echo '<p><strong>ID del plan:</strong> ' . $resultado['plan_id'] . '</p>';
                                    echo '<a href="home.php" class="btn btn-success">Ver en Dashboard</a>';
                                } else {
                                    echo '<p>❌ Error al crear el plan: ' . ($resultado['message'] ?? 'Error desconocido') . '</p>';
                                }
                                
                            } catch (Exception $e) {
                                echo '<p>❌ Error al crear plan de prueba: ' . $e->getMessage() . '</p>';
                            }
                            echo '</div>';
                        } else {
                            echo '<form method="post">';
                            echo '<button type="submit" name="crear_plan_prueba" class="btn btn-success">🚀 Crear Plan de Prueba</button>';
                            echo '</form>';
                        }
                    }
                    echo '</div>';
                    
                } catch (Exception $e) {
                    echo '<div class="error">';
                    echo '<h3>❌ Error al verificar planes</h3>';
                    echo '<p>' . $e->getMessage() . '</p>';
                    echo '</div>';
                }
                
            } else {
                echo '<div class="error">';
                echo '<h3>❌ No se pudo obtener el ID del usuario</h3>';
                echo '<p>Revisar estructura de datos de sesión.</p>';
                echo '</div>';
            }
            
        } else {
            echo '<div class="error">';
            echo '<h3>❌ No hay sesión activa</h3>';
            echo '<p><a href="login.php" class="btn btn-primary">Iniciar Sesión</a></p>';
            echo '</div>';
        }
        ?>
        
        <div class="info">
            <h3>🔗 Navegación</h3>
            <a href="home.php" class="btn btn-primary">🏠 Dashboard</a>
            <a href="wizard_paso1.php" class="btn btn-success">➕ Nuevo Plan Manual</a>
            <a href="validacion_integral.php" class="btn btn-info">🔍 Validación Completa</a>
            <a href="centro_validacion.php" class="btn btn-info">🎛️ Centro de Control</a>
        </div>
    </div>
</body>
</html>
