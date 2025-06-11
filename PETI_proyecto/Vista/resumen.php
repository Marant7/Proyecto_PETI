<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "No hay usuario en sesión. Por favor, inicie sesión.";
    exit();
}

require_once __DIR__ . '/../Controllers/PlanEstrategicoController.php'; 
$planController = new PlanEstrategicoController();
$planData = $planController->cargarDatosPlanEstrategico();

// Asegurarse de que $planData tenga una estructura mínima si es null
if ($planData === null || !is_array($planData)) { 
    $planData = [ 
        'error_message' => "No se pudo cargar la información del plan.",
        'id_empresa' => null, // Importante tenerlo para el futuro guardado
        'nombre_empresa' => "N/A", 'responsable_nombres' => "N/A", 'responsable_codigo' => "N/A",
        'logo_placeholder' => "Logo no disponible", 'mision' => "Información no disponible.", 'vision' => "Información no disponible.",
        'objetivos' => [], 'foda' => ['fortalezas' => [], 'debilidades' => [], 'oportunidades' => [], 'amenazas' => []],
        'estrategia_identificada' => "", // Dejar vacío para el textarea
        'conclusiones' => "" // Dejar vacío para el textarea
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resumen Interactivo Plan Estratégico TI</title>
  <!-- Tus CSS existentes -->
  <link rel="stylesheet" href="../public/css/normalize.css">
  <link rel="stylesheet" href="../public/css/sweetalert2.css">
  <link rel="stylesheet" href="../public/css/material.min.css">
  <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
  <link rel="stylesheet" href="../public/css/jquery.mCustomScrollbar.css">
  <link rel="stylesheet" href="../public/css/main.css">
  
  <!-- CSS para la vista del resumen (el que ya tenías: resumen_ejecutivo_vista.css) -->
  <link rel="stylesheet" href="../public/css/resumen.css"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- Tus Scripts existentes -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="../public/js/jquery-1.11.2.min.js"><\/script>')</script>
  <script src="../public/js/material.min.js"></script>
  <script src="../public/js/sweetalert2.min.js"></script>
  <script src="../public/js/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="../public/js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
 <!-- navBar y navLateral sin cambios -->
 <div class="full-width navBar">
    <div class="full-width navBar-options">
        <i class="zmdi zmdi-more-vert btn-menu" id="btn-menu"></i>
        <div class="mdl-tooltip" for="btn-menu">Menu</div>
        <nav class="navBar-options-list">
            <ul class="list-unstyle">
                <li class="btn-Notification" id="notifications">
                    <i class="zmdi zmdi-notifications"></i>
                    <div class="mdl-tooltip" for="notifications">Notifications</div>
                </li>
                <li class="btn-exit" id="btn-exit">
                    <i class="zmdi zmdi-power"></i>
                    <div class="mdl-tooltip" for="btn-exit">LogOut</div>
                </li>
                <li class="text-condensedLight noLink">
                    <small><?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : 'Usuario'; ?></small>
                </li>
                <li class="noLink">
                    <figure>
                        <img src="../public/assets/img/avatar-male.png" alt="Avatar" class="img-responsive">
                    </figure>
                </li>
            </ul>
        </nav>
    </div>
</div>

<div class="main-layout">
    <div class="navLateral">  </div>
    <?php include 'sidebar.php'; ?>
    <div class="pageContent">
        <div class="content">
            <div class="container">
                    <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                        <div class="mdl-tabs__tab-bar">
                            <a href="#tabResumenEjecutivo" class="mdl-tabs__tab is-active">RESUMEN PLAN ESTRATÉGICO TI</a>
                        </div>
                        <div class="mdl-tabs__panel is-active" id="tabResumenEjecutivo">
                            <div class="mdl-grid">
                                <div class="mdl-cell mdl-cell--12-col">
                                    <div class="full-width panel mdl-shadow--2dp">
                                        <div class="full-width panel-tittle bg-primary text-center tittles">
                                            Visualización del Plan Estratégico de TI
                                        </div>
                                        <div class="full-width panel-content">
                                            <?php if(isset($planData['error_message']) && !empty($planData['error_message']) ): ?>
                                                <div class="alert alert-danger text-center" style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom:15px;">
                                                    <h4>Error al Cargar el Plan</h4>
                                                    <p><?php echo htmlspecialchars($planData['error_message']); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            <input type="hidden" id="id_empresa_plan" value="<?php echo htmlspecialchars($planData['id_empresa'] ?? ''); ?>">
                                            
                                            <div class="container-resumen-vista"> 
                                                <div class="header-bar-resumen-vista">
                                                    PLAN ESTRATÉGICO DE TI
                                                    <button id="exportPlanDataButton" class="action-button-resumen-vista" title="Exportar Plan a PDF" style="background-color: #dc3545;"> <!-- o el color que prefieras -->
                                                         <i class="fas fa-file-pdf"></i> Exportar
                                                    </button>
                                                </div>

                                                <div class="company-info-resumen-vista section-hoverable">
                                                    <div class="logo-placeholder-resumen-vista" title="Logo de la Empresa">
                                                        <?php echo htmlspecialchars($planData['logo_placeholder'] ?? 'Logo no disponible'); ?>
                                                    </div>
                                                    <p><span class="label-resumen-vista">Nombre de la Empresa:</span> <span class="value-resumen-vista"><?php echo htmlspecialchars($planData['nombre_empresa'] ?? 'N/A'); ?></span></p>
                                                    <p><span class="label-resumen-vista">Responsable:</span> <span class="value-resumen-vista"><?php echo htmlspecialchars($planData['responsable_nombres'] ?? 'N/A'); ?></span></p>
                                                    <?php if(!empty($planData['responsable_codigo']) && $planData['responsable_codigo'] !== 'N/A'): ?>
                                                    <p><span class="label-resumen-vista">Código:</span> <span class="value-resumen-vista"><?php echo htmlspecialchars($planData['responsable_codigo']); ?></span></p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="section-full-width-resumen-vista section-hoverable collapsible-section">
                                                    <div class="section-label-horizontal-resumen-vista collapsible-header"> MISIÓN <i class="fas fa-chevron-down toggle-icon"></i></div>
                                                    <div class="section-content-resumen-vista collapsible-content"><p class="text-content-resumen-vista"><?php echo nl2br(htmlspecialchars($planData['mision'] ?? 'Misión no definida.')); ?></p></div>
                                                </div>
                                                <div class="section-full-width-resumen-vista section-hoverable collapsible-section">
                                                    <div class="section-label-horizontal-resumen-vista collapsible-header"> VISIÓN <i class="fas fa-chevron-down toggle-icon"></i></div>
                                                    <div class="section-content-resumen-vista collapsible-content"><p class="text-content-resumen-vista"><?php echo nl2br(htmlspecialchars($planData['vision'] ?? 'Visión no definida.')); ?></p></div>
                                                </div>
                                                <div class="section-full-width-resumen-vista section-hoverable collapsible-section">
                                                    <div class="section-label-horizontal-resumen-vista collapsible-header"> OBJETIVOS ESTRATÉGICOS <i class="fas fa-chevron-down toggle-icon"></i></div>
                                                    <div class="table-container-resumen-vista collapsible-content">
                                                        <table class="objetivos-table-resumen-vista">
                                                            <thead><tr><th>Eje Misional</th><th>Objetivo General/Estratégico</th><th>Objetivo Específico</th></tr></thead>
                                                            <tbody>
                                                                <?php if (isset($planData['objetivos']) && !empty($planData['objetivos'])): ?>
                                                                    <?php foreach ($planData['objetivos'] as $objetivo): ?>
                                                                    <tr><td><?php echo nl2br(htmlspecialchars($objetivo['mision_relacionada'] ?? 'N/A')); ?></td><td><?php echo htmlspecialchars($objetivo['general'] ?? 'N/A'); ?></td><td><?php echo htmlspecialchars($objetivo['especifico'] ?? 'N/A'); ?></td></tr>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <tr><td colspan="3" class="text-center">No hay objetivos definidos.</td></tr>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="section-full-width-resumen-vista section-hoverable collapsible-section">
                                                    <div class="section-label-horizontal-resumen-vista collapsible-header"> ANÁLISIS FODA <i class="fas fa-chevron-down toggle-icon"></i></div>
                                                    <div class="foda-grid-resumen-vista collapsible-content">
                                                        <?php $fodaCategories = ['fortalezas' => 'FORTALEZAS', 'debilidades' => 'DEBILIDADES', 'oportunidades' => 'OPORTUNIDADES', 'amenazas' => 'AMENAZAS'];
                                                        foreach ($fodaCategories as $key => $title): ?>
                                                        <div class="foda-category-resumen-vista" id="foda-<?php echo $key; ?>-resumen-vista">
                                                            <div class="foda-header-resumen-vista"><h3><?php echo $title; ?></h3></div>
                                                            <ul class="foda-item-list-resumen-vista">
                                                                <?php if (isset($planData['foda'][$key]) && !empty($planData['foda'][$key])): ?>
                                                                    <?php foreach ($planData['foda'][$key] as $item): ?>
                                                                    <li title="<?php echo htmlspecialchars($item); ?>"><?php echo htmlspecialchars($item); ?></li>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <li class="text-muted">No hay ítems definidos.</li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <div class="section-full-width-resumen-vista collapsible-section">
                                                    <div class="section-label-horizontal-resumen-vista collapsible-header">
                                                        IDENTIFICACIÓN DE ESTRATEGIA
                                                        <i class="fas fa-chevron-down toggle-icon"></i>
                                                    </div>
                                                    <div class="section-content-resumen-vista collapsible-content">
                                                        <p class="description-text-resumen-vista">Estrategia identificada a partir de la Matriz FODA:</p>
                                                        <textarea id="estrategiaPlanInput" class="editable-text-area-plan" placeholder="Describa la estrategia aquí..."><?php echo htmlspecialchars($planData['estrategia_identificada'] ?? ''); ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="section-full-width-resumen-vista collapsible-section">
                                                    <div class="section-label-horizontal-resumen-vista collapsible-header">
                                                        CONCLUSIONES
                                                        <i class="fas fa-chevron-down toggle-icon"></i>
                                                    </div>
                                                    <div class="section-content-resumen-vista collapsible-content">
                                                        <p class="description-text-resumen-vista">Conclusiones más relevantes del Plan:</p>
                                                        <textarea id="conclusionesPlanInput" class="editable-text-area-plan" placeholder="Anote las conclusiones aquí..."><?php echo htmlspecialchars($planData['conclusiones'] ?? ''); ?></textarea>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>                                                           
        </div>                                                             
    </div>
</div>
  <script src="../public/js/resumen.js"></script> 
</body>
</html>