<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PETI - Plan Finalizado</title>
    
    <!-- Material Design CSS -->
    <link rel="stylesheet" href="../public/css/material.min.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../public/css/normalize.css">
    <link rel="stylesheet" href="../public/css/main.css">
    
    <style>
        .success-container {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border-radius: 10px;
            margin: 40px 0;
        }
        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .success-title {
            font-size: 2.5em;
            margin-bottom: 20px;
            font-weight: 300;
        }
        .success-message {
            font-size: 1.3em;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        .action-buttons {
            margin-top: 40px;
        }
        .btn-action {
            background-color: white;
            color: #4CAF50;
            padding: 15px 30px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .btn-action:hover {
            background-color: #f5f5f5;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transform: translateY(-2px);
        }
        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .info-box h4 {
            color: #2196F3;
            margin-top: 0;
        }
        .info-box ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .info-box li {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <section class="full-width pageContent">
        <div class="full-width divider-menu-h"></div>
        
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <div class="full-width panel mdl-shadow--2dp">
                    
                    <!-- Mensaje de éxito -->
                    <div class="success-container">
                        <div class="success-icon">
                            <i class="zmdi zmdi-check-circle"></i>
                        </div>
                        <h1 class="success-title">¡Plan Estratégico Completado!</h1>
                        <p class="success-message">
                            Su Plan Estratégico de Tecnologías de Información ha sido guardado exitosamente en la base de datos.
                        </p>
                        
                        <div class="action-buttons">
                            <a href="../index.php?controller=ResumenFinal&action=mostrarResumen" class="btn-action">
                                <i class="zmdi zmdi-assignment"></i> Ver Resumen Final
                            </a>
                            <a href="../index.php" class="btn-action">
                                <i class="zmdi zmdi-home"></i> Ir al Inicio
                            </a>
                            <a href="#" onclick="window.print()" class="btn-action">
                                <i class="zmdi zmdi-print"></i> Imprimir
                            </a>
                        </div>
                    </div>
                    
                    <!-- Información adicional -->
                    <div class="full-width panel-content">
                        <div class="info-box">
                            <h4><i class="zmdi zmdi-info"></i> Módulos Completados</h4>
                            <ul>
                                <li>✅ Visión y Misión</li>
                                <li>✅ Valores Organizacionales</li>
                                <li>✅ Objetivos Estratégicos</li>
                                <li>✅ Análisis de Cadena de Valor</li>
                                <li>✅ Matriz BCG</li>
                                <li>✅ Análisis de Fuerzas de Porter</li>
                                <li>✅ Análisis PEST</li>
                                <li>✅ Identificación de Estrategias (FODA)</li>
                                <li>✅ Matriz CAME</li>
                                <li>✅ Resumen Ejecutivo</li>
                            </ul>
                        </div>
                        
                        <div class="info-box">
                            <h4><i class="zmdi zmdi-storage"></i> Datos Almacenados</h4>
                            <p>Todos los datos han sido guardados permanentemente en la base de datos y están disponibles para consultas futuras.</p>
                        </div>
                        
                        <div class="info-box">
                            <h4><i class="zmdi zmdi-calendar"></i> Próximos Pasos</h4>
                            <ul>
                                <li>Revisar el resumen ejecutivo generado</li>
                                <li>Implementar las estrategias identificadas</li>
                                <li>Monitorear el progreso de los objetivos</li>
                                <li>Actualizar el plan periódicamente</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Limpiar cualquier dato temporal del navegador
        if (typeof(Storage) !== "undefined") {
            localStorage.removeItem('pest_resultados');
            localStorage.removeItem('temp_data');
        }
        
        // Mensaje de bienvenida después de 2 segundos
        setTimeout(function() {
            console.log('Plan Estratégico completado exitosamente');
        }, 2000);
    </script>
</body>
</html>
