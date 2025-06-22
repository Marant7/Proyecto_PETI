<?php
$current_page = basename($_SERVER['PHP_SELF']);
$plan_id_param = isset($plan_id) && $plan_id ? "?id_plan=" . htmlspecialchars($plan_id) : "";

// Detectar si estamos en el controlador o en la vista para ajustar las rutas
$is_from_controller = strpos($_SERVER['REQUEST_URI'], 'Controllers/') !== false;
$home_path = $is_from_controller ? '../Vista/home.php' : 'home.php';
?>
<nav class="sidebar">
    <h3>Nuevo Plan</h3>    <a href="../Controllers/PlanController.php?action=editarVisionMision&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'vision_mision.php') ? 'active' : ''; ?>">Visión y Misión</a>
    <a href="../Controllers/PlanController.php?action=editarValores&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'valores.php') ? 'active' : ''; ?>">Valores</a>
    <a href="../Controllers/PlanController.php?action=editarObjetivos&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'objetivos_estrategicos.php') ? 'active' : ''; ?>">Objetivos Estratégicos</a>
    <a href="../Controllers/PlanController.php?action=editarCadenaValor&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'cadena_valor.php') ? 'active' : ''; ?>">Cadena de Valor</a>
    <a href="../Controllers/PlanController.php?action=editarMatrizBCG&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'matriz_bcg.php') ? 'active' : ''; ?>">Matriz BCG</a>
    <a href="../Controllers/PlanController.php?action=editarFuerzasPorter&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'fuerzas_porter.php') ? 'active' : ''; ?>">Fuerzas Porter</a>    <a href="../Controllers/PlanController.php?action=editarPEST&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'pest_nuevo.php') ? 'active' : ''; ?>">Análisis PEST</a>    <a href="../Controllers/PlanController.php?action=editarEstrategias&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'identificacion_de_estrategias.php') ? 'active' : ''; ?>">Identificación de Estrategias</a>
    <a href="../Controllers/PlanController.php?action=editarMatrizCame&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'matriz_came_ejemplo.php') ? 'active' : ''; ?>">Matriz CAME</a>
    <a href="../Controllers/PlanController.php?action=editarResumenEjecutivo&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'resumen_ejecutivo.php') ? 'active' : ''; ?>">Resumen Ejecutivo</a>
    <a href="<?php echo $home_path; ?>" class="sidebar-link" style="margin-top:40px; background:#28a745;">Volver al Home</a>
</nav>
<style>
    .sidebar {
        width: 220px;
        background: #007bff;
        color: #fff;
        padding: 30px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: fixed;
        height: 100%;
    }

    .sidebar h3 {
        margin-bottom: 30px;
        font-weight: 400;
    }

    .sidebar-link {
        color: #fff;
        text-decoration: none;
        padding: 12px 24px;
        border-radius: 6px;
        margin-bottom: 10px;
        width: 80%;
        text-align: center;
    }

    .sidebar-link.active {
        background: #0056b3;
    }

    .sidebar-link.disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
</style>
