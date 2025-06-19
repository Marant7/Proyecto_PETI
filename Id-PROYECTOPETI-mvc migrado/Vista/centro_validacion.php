<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Validación - Sistema de Planes Estratégicos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 1.2em;
            opacity: 0.9;
        }
        .content {
            padding: 40px;
        }
        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        .tool-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }
        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .tool-icon {
            font-size: 3em;
            margin-bottom: 15px;
        }
        .tool-title {
            font-size: 1.4em;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .tool-description {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 5px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,123,255,0.3);
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40,167,69,0.3);
        }
        .btn-info {
            background: linear-gradient(135deg, #17a2b8, #117a8b);
            color: white;
        }
        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23,162,184,0.3);
        }
        .btn-warning {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #212529;
        }
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,193,7,0.3);
        }
        .system-status {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .status-title {
            font-size: 1.3em;
            font-weight: 600;
            color: #1976d2;
            margin-bottom: 15px;
        }
        .quick-links {
            background: #f3e5f5;
            border: 1px solid #ce93d8;
            border-radius: 12px;
            padding: 25px;
        }
        .links-title {
            font-size: 1.3em;
            font-weight: 600;
            color: #7b1fa2;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Centro de Validación</h1>
            <p>Sistema de Planes Estratégicos - Herramientas de Testing y Diagnóstico</p>
        </div>
        
        <div class="content">
            <div class="system-status">
                <div class="status-title">📊 Estado del Sistema</div>
                <p>Bienvenido al centro de validación del sistema de planes estratégicos. Aquí encontrarás todas las herramientas necesarias para verificar que el sistema funciona correctamente.</p>
                <p><strong>Recomendación:</strong> Ejecuta las herramientas en el orden sugerido para una validación completa.</p>
            </div>
            
            <div class="tools-grid">
                <div class="tool-card">
                    <div class="tool-icon">👥</div>
                    <div class="tool-title">Setup de Usuarios</div>
                    <div class="tool-description">
                        Verifica y crea usuarios de prueba para el sistema. Ejecuta esto primero si no tienes usuarios configurados.
                    </div>
                    <a href="setup_usuarios.php" class="btn btn-warning">Gestionar Usuarios</a>
                </div>
                
                <div class="tool-card">
                    <div class="tool-icon">🔍</div>
                    <div class="tool-title">Validación Integral</div>
                    <div class="tool-description">
                        Ejecuta una validación completa del sistema verificando base de datos, sesiones, controladores y más.
                    </div>
                    <a href="validacion_integral.php" class="btn btn-primary">Ejecutar Validación</a>
                </div>
                
                <div class="tool-card">
                    <div class="tool-icon">🧪</div>
                    <div class="tool-title">Test Funcional</div>
                    <div class="tool-description">
                        Realiza tests automatizados de todas las funcionalidades del sistema y crea datos de prueba si es necesario.
                    </div>
                    <a href="test_funcional.php" class="btn btn-success">Ejecutar Tests</a>
                </div>
                
                <div class="tool-card">
                    <div class="tool-icon">🏠</div>
                    <div class="tool-title">Dashboard Principal</div>
                    <div class="tool-description">
                        Accede al dashboard principal donde se muestran los planes estratégicos del usuario autenticado.
                    </div>
                    <a href="home.php" class="btn btn-info">Ir al Dashboard</a>
                </div>
                
                <div class="tool-card">
                    <div class="tool-icon">➕</div>
                    <div class="tool-title">Crear Nuevo Plan</div>
                    <div class="tool-description">
                        Inicia el wizard para crear un nuevo plan estratégico desde cero y probar todo el flujo de creación.
                    </div>
                    <a href="wizard_paso1.php" class="btn btn-success">Nuevo Plan</a>
                </div>
                
                <div class="tool-card">
                    <div class="tool-icon">🗄️</div>
                    <div class="tool-title">Estado de BD</div>
                    <div class="tool-description">
                        Verifica el estado de la base de datos, tablas existentes y datos almacenados en el sistema.
                    </div>
                    <a href="estado_bd.php" class="btn btn-info">Ver Estado BD</a>
                </div>
            </div>
            
            <div class="quick-links">
                <div class="links-title">🔗 Enlaces Rápidos de Diagnóstico</div>
                <p>Scripts adicionales para diagnóstico específico:</p>
                <a href="diagnostico_sesion.php" class="btn btn-primary">Diagnóstico de Sesión</a>
                <a href="verificacion_final.php" class="btn btn-info">Verificación Final</a>
                <a href="test_dashboard.php" class="btn btn-success">Test de Dashboard</a>
                <a href="debug_guardado_completo.php" class="btn btn-warning">Debug de Guardado</a>
            </div>
            
            <div class="system-status">
                <div class="status-title">📋 Instrucciones de Uso</div>
                <ol>
                    <li><strong>Setup de Usuarios:</strong> Ejecuta primero para asegurar que hay usuarios en el sistema</li>
                    <li><strong>Validación Integral:</strong> Verifica que todos los componentes del sistema funcionan</li>
                    <li><strong>Test Funcional:</strong> Ejecuta tests automatizados y crea datos de prueba</li>
                    <li><strong>Dashboard:</strong> Verifica visualmente que los planes se muestran correctamente</li>
                    <li><strong>Nuevo Plan:</strong> Prueba crear un plan completo usando el wizard</li>
                </ol>
                <p><strong>Nota:</strong> Si algún test falla, consulta el archivo README_VALIDACION.md para soluciones específicas.</p>
            </div>
        </div>
    </div>
</body>
</html>
