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

// Cargar datos del resumen ejecutivo
require_once '../Controllers/PlanEstrategicoController.php';
$controller = new PlanEstrategicoController();
$resumen = $controller->obtenerResumenEjecutivo($id_plan);

if (!$resumen) {
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Ejecutivo: <?php echo htmlspecialchars($resumen['nombre_plan']); ?></title>
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <style>
        /* Reset y configuración base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Georgia, serif;
            line-height: 1.6;
            color: #2c3e50;
            background: #f8f9fa;
        }
        
        /* Contenedor principal del informe */
        .informe-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            min-height: 297mm;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        /* Barra de acciones (no se imprime) */
        .actions-toolbar {
            background: #2c3e50;
            padding: 15px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 8px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-secondary { 
            background: #6c757d; 
            color: white; 
        }
        .btn-success { 
            background: #28a745; 
            color: white; 
        }
        .btn-info { 
            background: #17a2b8; 
            color: white; 
        }
        .btn-primary { 
            background: #007bff; 
            color: white; 
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
          /* Encabezado del informe */
        .informe-header {
            text-align: center;
            padding: 50px 40px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #3498db 100%);
            color: white;
            margin-bottom: 0;
            position: relative;
            overflow: hidden;
        }
        
        .informe-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="smallGrid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="20" fill="url(%23smallGrid)"/></svg>') repeat;
            opacity: 0.3;
        }
        
        .informe-titulo {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 4px;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .informe-subtitulo {
            font-size: 22px;
            color: rgba(255,255,255,0.95);
            margin-bottom: 30px;
            font-style: italic;
            position: relative;
            z-index: 2;
            font-weight: 300;
        }
        
        .informe-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-top: 35px;
            position: relative;
            z-index: 2;
        }
        
        .meta-card {
            background: rgba(255,255,255,0.15);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .meta-card:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        .meta-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
            opacity: 0.9;
            font-weight: 600;
        }
          .meta-value {
            font-size: 18px;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        /* Contenido del informe */
        .informe-content {
            padding: 40px 50px 50px;
        }
        
        /* Secciones */
        .seccion {
            margin-bottom: 45px;
            page-break-inside: avoid;
        }
        
        .seccion-titulo {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            padding: 15px 0;
            border-bottom: 3px solid #3498db;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .seccion-contenido {
            font-size: 13px;
            line-height: 1.8;
            text-align: justify;
            color: #34495e;
        }
        
        /* Subsecciones */
        .subseccion {
            margin-bottom: 30px;
        }
        
        .subseccion-titulo {
            font-size: 15px;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 15px;
            border-left: 5px solid #3498db;
            padding-left: 15px;
            background: #ecf0f1;
            padding: 10px 15px;
        }
        
        /* Grid para valores */
        .valores-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        
        .valor-item {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #3498db;
            border-radius: 0 6px 6px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .valor-nombre {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .valor-descripcion {
            font-size: 12px;
            color: #7f8c8d;
            font-style: italic;
        }
        
        /* Matriz FODA */
        .foda-matriz {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin: 25px 0;
        }
        
        .foda-cuadrante {
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .foda-fortalezas {
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
            border-left: 5px solid #28a745;
        }
        
        .foda-debilidades {
            background: linear-gradient(135deg, #fde8e8 0%, #f8d7da 100%);
            border-left: 5px solid #dc3545;
        }
        
        .foda-oportunidades {
            background: linear-gradient(135deg, #e8f4f8 0%, #cce7f0 100%);
            border-left: 5px solid #17a2b8;
        }
        
        .foda-amenazas {
            background: linear-gradient(135deg, #fff3e0 0%, #ffeaa7 100%);
            border-left: 5px solid #fd7e14;
        }
        
        .foda-titulo {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .foda-item {
            margin: 8px 0;
            padding: 8px 0;
            font-size: 12px;
            border-bottom: 1px dotted #bdc3c7;
            line-height: 1.5;
        }
        
        .foda-item:last-child {
            border-bottom: none;
        }
          /* Objetivos simplificados */
        .objetivos-lista {
            margin: 20px 0;
        }
        
        .objetivo-item {
            margin: 12px 0;
            padding: 15px 20px;
            background: #f8f9fa;
            border-left: 4px solid #17a2b8;
            border-radius: 0 6px 6px 0;
            font-size: 13px;
            line-height: 1.6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Estrategias CAME */
        .came-estrategia {
            margin: 25px 0;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .came-corregir {
            background: linear-gradient(135deg, #fde8e8 0%, #f8d7da 100%);
            border-left: 5px solid #dc3545;
        }
        
        .came-afrontar {
            background: linear-gradient(135deg, #fff3e0 0%, #ffeaa7 100%);
            border-left: 5px solid #fd7e14;
        }
        
        .came-mantener {
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
            border-left: 5px solid #28a745;
        }
        
        .came-explotar {
            background: linear-gradient(135deg, #e8f4f8 0%, #cce7f0 100%);
            border-left: 5px solid #17a2b8;
        }
        
        .came-titulo {
            font-weight: bold;
            margin-bottom: 12px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .came-texto {
            font-size: 13px;
            line-height: 1.6;
            text-align: justify;
        }
        
        /* Información profesional */
        .info-profesional {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin: 20px 0;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            padding: 20px;
            background: white;
            border-radius: 12px;
            border: 1px solid #e8ecef;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .info-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }
        
        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .info-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 18px;
            flex-shrink: 0;
        }
        
        .info-icon i {
            color: white;
            font-size: 20px;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
            color: #7f8c8d;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            line-height: 1.2;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-completado {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
        }
        
        /* Caja de contenido destacada */
        .content-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 5px solid #3498db;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Pie de documento */
        .informe-footer {
            text-align: center;
            padding: 30px;
            border-top: 2px solid #2c3e50;
            margin-top: 40px;
            color: #7f8c8d;
            font-size: 12px;
        }
        
        /* Estados vacíos */
        .estado-vacio {
            text-align: center;
            padding: 50px 30px;
            color: #7f8c8d;
            font-style: italic;
            background: #f8f9fa;
            border: 2px dashed #bdc3c7;
            border-radius: 8px;
            margin: 30px 0;
        }
        
        .estado-vacio i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #bdc3c7;
        }
        
        /* Estilos para impresión */
        @media print {
            .actions-toolbar {
                display: none !important;
            }
            
            .informe-container {
                box-shadow: none;
                max-width: none;
                margin: 0;
            }
            
            .seccion {
                page-break-inside: avoid;
            }
            
            .seccion-titulo {
                page-break-after: avoid;
            }
            
            .foda-matriz {
                page-break-inside: avoid;
            }
            
            body {
                font-size: 12pt;
                background: white;
            }
            
            .informe-header {
                background: #2c3e50 !important;
                color: white !important;
            }
        }        /* Responsivo */
        @media (max-width: 768px) {
            .informe-container {
                margin: 0;
                box-shadow: none;
            }
            
            .informe-content {
                padding: 20px;
            }
            
            .informe-header {
                padding: 30px 20px;
            }
            
            .informe-header > div:first-child {
                flex-direction: column !important;
                text-align: center;
            }
            
            .informe-header > div:first-child > div:last-child {
                margin-top: 20px;
                width: 100px;
                height: 60px;
            }
            
            .foda-matriz {
                grid-template-columns: 1fr;
            }
            
            .valores-grid {
                grid-template-columns: 1fr;
            }
            
            .informe-meta {
                grid-template-columns: 1fr;
            }
            
            .info-profesional {
                grid-template-columns: 1fr;
            }
            
            .info-item {
                padding: 15px;
            }
            
            .info-icon {
                width: 40px;
                height: 40px;
                margin-right: 15px;
            }
            
            .info-icon i {
                font-size: 18px;
            }
        }
    </style></head>
<body>
    <div class="informe-container">
        
        <!-- Barra de acciones (no se imprime) -->
        <div class="actions-toolbar">
            <a href="home.php" class="btn btn-secondary">
                <i class="zmdi zmdi-home"></i> Dashboard
            </a>
            <a href="ver_plan.php?id=<?php echo $id_plan; ?>" class="btn btn-info">
                <i class="zmdi zmdi-eye"></i> Ver Plan Completo
            </a>
            <button onclick="window.print()" class="btn btn-success">
                <i class="zmdi zmdi-print"></i> Imprimir
            </button>            <a href="exportar_pdf.php?id=<?php echo $id_plan; ?>" class="btn btn-primary">
                <i class="zmdi zmdi-download"></i> Exportar PDF
            </a>
            <button onclick="mostrarUploadLogo()" class="btn" style="background: #6f42c1;">
                <i class="zmdi zmdi-image"></i> Agregar Logo
            </button>
        </div>

        <!-- Modal para subir logo -->
        <div id="modal-logo" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; max-width: 400px; width: 90%;">
                <h3 style="margin-bottom: 20px; color: #2c3e50;">
                    <i class="zmdi zmdi-image"></i> Agregar Logo de la Empresa
                </h3>
                <form id="form-logo" enctype="multipart/form-data">
                    <input type="hidden" name="id_plan" value="<?php echo $id_plan; ?>">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Seleccionar imagen:</label>
                        <input type="file" name="logo" accept="image/*" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <small style="color: #666; font-size: 12px;">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</small>
                    </div>
                    <div style="text-align: right;">
                        <button type="button" onclick="cerrarModalLogo()" style="padding: 8px 16px; margin-right: 10px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Cancelar
                        </button>
                        <button type="submit" style="padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            <i class="zmdi zmdi-upload"></i> Subir Logo
                        </button>
                    </div>
                </form>
            </div>
        </div><!-- Encabezado del informe -->
        <div class="informe-header">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
                <div style="flex: 1;">
                    <h1 class="informe-titulo">Plan Estratégico</h1>
                    <h2 class="informe-subtitulo"><?php echo htmlspecialchars($resumen['nombre_plan']); ?></h2>
                </div>
                <div id="logo-area" style="width: 120px; height: 80px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <?php if (isset($resumen['logo_empresa']) && !empty($resumen['logo_empresa'])): ?>
                        <img src="../<?php echo htmlspecialchars($resumen['logo_empresa']); ?>" 
                             alt="Logo de la empresa" 
                             style="max-width: 120px; max-height: 80px; object-fit: contain;">
                    <?php else: ?>
                        <div class="logo-placeholder" style="background: rgba(255,255,255,0.2); border: 2px dashed rgba(255,255,255,0.5); border-radius: 8px; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.7); font-size: 10px; text-align: center;">
                            <div>
                                <i class="zmdi zmdi-image" style="display: block; font-size: 20px; margin-bottom: 5px;"></i>
                                Sin Logo
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="informe-meta">
                <div class="meta-card">
                    <div class="meta-label">Empresa</div>
                    <div class="meta-value"><?php echo htmlspecialchars($resumen['empresa']); ?></div>
                </div>
                <div class="meta-card">
                    <div class="meta-label">Fecha de Elaboración</div>
                    <div class="meta-value"><?php echo date('d/m/Y', strtotime($resumen['fecha_creacion'])); ?></div>
                </div>
                <div class="meta-card">
                    <div class="meta-label">Tipo de Documento</div>
                    <div class="meta-value">Informe Ejecutivo</div>
                </div>
            </div>
        </div>

        <!-- Contenido del informe -->
        <div class="informe-content">                <!-- 1. INFORMACIÓN BÁSICA -->
                <div class="seccion">
                    <h2 class="seccion-titulo">
                        <i class="zmdi zmdi-info"></i> Información General
                    </h2>
                    <div class="seccion-contenido">
                        <div class="info-profesional">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="zmdi zmdi-file-text"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Nombre del Plan</div>
                                    <div class="info-value"><?php echo htmlspecialchars($resumen['nombre_plan']); ?></div>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="zmdi zmdi-business"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Empresa</div>
                                    <div class="info-value"><?php echo htmlspecialchars($resumen['empresa']); ?></div>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="zmdi zmdi-calendar"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Fecha de Elaboración</div>
                                    <div class="info-value"><?php echo date('d/m/Y', strtotime($resumen['fecha_creacion'])); ?></div>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="zmdi zmdi-check-circle"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Estado</div>
                                    <div class="info-value">
                                        <span class="status-badge status-completado">Completado</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. EMPRENDEDORES Y PROMOTORES -->
                <?php if (isset($resumen['emprendedores_promotores']) && !empty($resumen['emprendedores_promotores'])): ?>
                <div class="seccion">
                    <h2 class="seccion-titulo">
                        <i class="zmdi zmdi-accounts"></i> Emprendedores y Promotores
                    </h2>
                    <div class="seccion-contenido">
                        <div class="content-box">
                            <?php echo nl2br(htmlspecialchars($resumen['emprendedores_promotores'])); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 3. VISIÓN Y MISIÓN -->
                <?php if (isset($resumen['vision']) || isset($resumen['mision'])): ?>
                <div class="seccion">
                    <h2 class="seccion-titulo">
                        <i class="zmdi zmdi-eye"></i> Visión y Misión Organizacional
                    </h2>
                    <div class="seccion-contenido">
                        <?php if (isset($resumen['vision']) && !empty($resumen['vision'])): ?>
                        <div class="subseccion">
                            <h3 class="subseccion-titulo">Visión</h3>
                            <div class="content-box">
                                <?php echo htmlspecialchars($resumen['vision']); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($resumen['mision']) && !empty($resumen['mision'])): ?>
                        <div class="subseccion">
                            <h3 class="subseccion-titulo">Misión</h3>
                            <div class="content-box">
                                <?php echo htmlspecialchars($resumen['mision']); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 4. VALORES ORGANIZACIONALES -->
                <?php if (isset($resumen['valores']) && !empty($resumen['valores'])): ?>
                <div class="seccion">
                    <h2 class="seccion-titulo">
                        <i class="zmdi zmdi-favorite"></i> Valores Organizacionales
                    </h2>
                    <div class="seccion-contenido">
                        <div class="valores-grid">
                            <?php foreach ($resumen['valores'] as $index => $valor): ?>
                                <div class="valor-item">
                                    <div class="valor-nombre"><?php echo htmlspecialchars($valor['valor']); ?></div>
                                    <?php if (!empty($valor['descripcion'])): ?>
                                        <div class="valor-descripcion"><?php echo htmlspecialchars($valor['descripcion']); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 5. UNIDAD ESTRATÉGICA -->
                <?php if (isset($resumen['unidad_estrategica']) && !empty($resumen['unidad_estrategica'])): ?>
                <div class="seccion">
                    <h2 class="seccion-titulo">
                        <i class="zmdi zmdi-business"></i> Unidad Estratégica de Negocio
                    </h2>
                    <div class="seccion-contenido">
                        <div class="content-box">
                            <?php echo nl2br(htmlspecialchars($resumen['unidad_estrategica'])); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 6. OBJETIVOS ESTRATÉGICOS -->
                <?php if (isset($resumen['objetivos_estrategicos']) && !empty($resumen['objetivos_estrategicos'])): ?>
                <div class="seccion">
                    <h2 class="seccion-titulo">
                        <i class="zmdi zmdi-flag"></i> Objetivos Estratégicos
                    </h2>
                    <div class="seccion-contenido">
                        <div class="objetivos-lista">
                            <?php 
                            $objetivos_por_categoria = [];
                            foreach ($resumen['objetivos_estrategicos'] as $objetivo) {
                                $categoria = $objetivo['categoria'] ?? 'General';
                                $objetivos_por_categoria[$categoria][] = $objetivo;
                            }
                            
                            foreach ($objetivos_por_categoria as $categoria => $objetivos): ?>
                                <div class="subseccion">
                                    <h3 class="subseccion-titulo"><?php echo ucfirst(htmlspecialchars($categoria)); ?></h3>                                    <?php foreach ($objetivos as $index => $objetivo): ?>
                                        <div class="objetivo-item">
                                            <?php echo htmlspecialchars($objetivo['objetivo']); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 7. ANÁLISIS FODA -->
                <?php if (isset($resumen['fortalezas']) || isset($resumen['debilidades']) || isset($resumen['oportunidades_foda']) || isset($resumen['amenazas'])): ?>
                <div class="seccion">
                    <h2 class="seccion-titulo">
                        <i class="zmdi zmdi-chart"></i> Análisis FODA
                    </h2>
                    <div class="seccion-contenido">
                        <div class="foda-matriz">
                            <!-- Fortalezas -->
                            <div class="foda-cuadrante foda-fortalezas">
                                <div class="foda-titulo">
                                    <i class="zmdi zmdi-thumb-up"></i> FORTALEZAS
                                </div>
                                <?php if (isset($resumen['fortalezas']) && !empty($resumen['fortalezas'])): ?>
                                    <?php foreach ($resumen['fortalezas'] as $fortaleza): ?>
                                        <div class="foda-item">• <?php echo htmlspecialchars($fortaleza); ?></div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="foda-item" style="font-style: italic; color: #999;">No se han definido fortalezas</div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Debilidades -->
                            <div class="foda-cuadrante foda-debilidades">
                                <div class="foda-titulo">
                                    <i class="zmdi zmdi-thumb-down"></i> DEBILIDADES
                                </div>
                                <?php if (isset($resumen['debilidades']) && !empty($resumen['debilidades'])): ?>
                                    <?php foreach ($resumen['debilidades'] as $debilidad): ?>
                                        <div class="foda-item">• <?php echo htmlspecialchars($debilidad); ?></div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="foda-item" style="font-style: italic; color: #999;">No se han definido debilidades</div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Oportunidades -->
                            <div class="foda-cuadrante foda-oportunidades">
                                <div class="foda-titulo">
                                    <i class="zmdi zmdi-trending-up"></i> OPORTUNIDADES
                                </div>
                                <?php if (isset($resumen['oportunidades_foda']) && !empty($resumen['oportunidades_foda'])): ?>
                                    <?php foreach ($resumen['oportunidades_foda'] as $oportunidad): ?>
                                        <div class="foda-item">• <?php echo htmlspecialchars($oportunidad); ?></div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="foda-item" style="font-style: italic; color: #999;">No se han definido oportunidades</div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Amenazas -->
                            <div class="foda-cuadrante foda-amenazas">
                                <div class="foda-titulo">
                                    <i class="zmdi zmdi-trending-down"></i> AMENAZAS
                                </div>
                                <?php if (isset($resumen['amenazas']) && !empty($resumen['amenazas'])): ?>
                                    <?php foreach ($resumen['amenazas'] as $amenaza): ?>
                                        <div class="foda-item">• <?php echo htmlspecialchars($amenaza); ?></div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="foda-item" style="font-style: italic; color: #999;">No se han definido amenazas</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 8. ACCIONES COMPETITIVAS (CAME) -->
                <?php if (isset($resumen['identificacion_estrategica']) && !empty($resumen['identificacion_estrategica'])): ?>
                <div class="seccion">
                    <h2 class="seccion-titulo">
                        <i class="zmdi zmdi-trending-up"></i> Acciones Competitivas
                    </h2>
                    <div class="seccion-contenido">
                        <p style="margin-bottom: 25px; color: #7f8c8d; font-style: italic;">
                            Estrategias definidas mediante el análisis CAME (Corregir, Afrontar, Mantener, Explotar):
                        </p>
                        
                        <?php if (!empty($resumen['identificacion_estrategica']['corregir'])): ?>
                        <div class="came-estrategia came-corregir">
                            <div class="came-titulo">
                                <i class="zmdi zmdi-close-circle"></i> Corregir (Debilidades)
                            </div>
                            <div class="came-texto">
                                <?php echo nl2br(htmlspecialchars($resumen['identificacion_estrategica']['corregir'])); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($resumen['identificacion_estrategica']['afrontar'])): ?>
                        <div class="came-estrategia came-afrontar">
                            <div class="came-titulo">
                                <i class="zmdi zmdi-shield-security"></i> Afrontar (Amenazas)
                            </div>
                            <div class="came-texto">
                                <?php echo nl2br(htmlspecialchars($resumen['identificacion_estrategica']['afrontar'])); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($resumen['identificacion_estrategica']['mantener'])): ?>
                        <div class="came-estrategia came-mantener">
                            <div class="came-titulo">
                                <i class="zmdi zmdi-check-circle"></i> Mantener (Fortalezas)
                            </div>
                            <div class="came-texto">
                                <?php echo nl2br(htmlspecialchars($resumen['identificacion_estrategica']['mantener'])); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($resumen['identificacion_estrategica']['explotar'])): ?>
                        <div class="came-estrategia came-explotar">
                            <div class="came-titulo">
                                <i class="zmdi zmdi-trending-up"></i> Explotar (Oportunidades)
                            </div>
                            <div class="came-texto">
                                <?php echo nl2br(htmlspecialchars($resumen['identificacion_estrategica']['explotar'])); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 9. IDENTIFICACIÓN ESTRATÉGICA -->
                <?php if (isset($resumen['identificacion_estrategica_usuario']) && !empty($resumen['identificacion_estrategica_usuario'])): ?>
                <div class="seccion">
                    <h2 class="seccion-titulo">
                        <i class="zmdi zmdi-lightbulb"></i> Identificación Estratégica
                    </h2>
                    <div class="seccion-contenido">
                        <div class="content-box">
                            <?php echo nl2br(htmlspecialchars($resumen['identificacion_estrategica_usuario'])); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 10. CONCLUSIONES -->
                <?php if (isset($resumen['conclusiones_usuario']) && !empty($resumen['conclusiones_usuario'])): ?>
                <div class="seccion">
                    <h2 class="seccion-titulo">
                        <i class="zmdi zmdi-assignment-check"></i> Conclusiones
                    </h2>
                    <div class="seccion-contenido">
                        <div class="content-box">
                            <?php echo nl2br(htmlspecialchars($resumen['conclusiones_usuario'])); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Estado vacío si no hay datos -->
                <?php if (empty($resumen['emprendedores_promotores']) && 
                          empty($resumen['vision']) && 
                          empty($resumen['mision']) && 
                          empty($resumen['valores']) && 
                          empty($resumen['unidad_estrategica']) && 
                          empty($resumen['objetivos_estrategicos']) && 
                          empty($resumen['fortalezas']) && 
                          empty($resumen['debilidades']) && 
                          empty($resumen['oportunidades_foda']) && 
                          empty($resumen['amenazas']) && 
                          empty($resumen['identificacion_estrategica']) && 
                          empty($resumen['identificacion_estrategica_usuario']) && 
                          empty($resumen['conclusiones_usuario'])): ?>
                <div class="seccion">
                    <div class="estado-vacio">
                        <i class="zmdi zmdi-info-outline"></i>
                        <h3>Plan Estratégico en Desarrollo</h3>
                        <p>Este plan estratégico está en proceso de elaboración. Una vez completado el wizard estratégico, aquí se mostrará el resumen ejecutivo completo con todas las secciones definidas.</p>
                        <p style="margin-top: 20px; font-size: 12px; color: #999;">
                            <strong>Nota:</strong> Este resumen muestra únicamente la información ingresada manualmente durante el proceso del wizard estratégico.
                        </p>
                    </div>
                </div>
                <?php endif; ?>
        </div>

        <!-- Pie del informe -->
        <div class="informe-footer">
            <div style="border-top: 1px solid #bdc3c7; padding-top: 20px;">
                <p><strong><?php echo htmlspecialchars($resumen['empresa']); ?></strong></p>
                <p>Informe generado el <?php echo date('d/m/Y H:i'); ?></p>
                <p style="margin-top: 10px; font-size: 11px; color: #999;">
                    Sistema de Planificación Estratégica
                </p>
            </div>
        </div>
    </div>    <script>
        // Efectos de carga suave
        document.addEventListener('DOMContentLoaded', function() {
            const contenido = document.querySelector('.informe-content');
            contenido.style.opacity = '0';
            contenido.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                contenido.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                contenido.style.opacity = '1';
                contenido.style.transform = 'translateY(0)';
            }, 300);
        });

        // Funciones para el modal de logo
        function mostrarUploadLogo() {
            document.getElementById('modal-logo').style.display = 'block';
        }

        function cerrarModalLogo() {
            document.getElementById('modal-logo').style.display = 'none';
        }

        // Manejar la subida del logo
        document.getElementById('form-logo').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="zmdi zmdi-spinner zmdi-spin"></i> Subiendo...';
            button.disabled = true;
            
            fetch('upload_logo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar el logo en la página
                    const logoArea = document.getElementById('logo-area');
                    logoArea.innerHTML = `<img src="../${data.logo_path}" alt="Logo de la empresa" style="max-width: 120px; max-height: 80px; object-fit: contain;">`;
                    
                    cerrarModalLogo();
                    
                    // Mostrar mensaje de éxito
                    const mensaje = document.createElement('div');
                    mensaje.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px 20px; border-radius: 6px; z-index: 1001; font-weight: 500;';
                    mensaje.innerHTML = '<i class="zmdi zmdi-check"></i> Logo subido correctamente';
                    document.body.appendChild(mensaje);
                    
                    setTimeout(() => mensaje.remove(), 3000);
                } else {
                    alert('Error al subir el logo: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al subir el logo. Inténtelo nuevamente.');
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });

        // Cerrar modal al hacer clic fuera
        document.getElementById('modal-logo').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalLogo();
            }
        });
    </script>
</body>
</html>
