<?php
$current_page = basename($_SERVER['PHP_SELF']);
$plan_id_param = isset($plan_id) && $plan_id ? "?id_plan=" . htmlspecialchars($plan_id) : "";

// Detectar si estamos en el controlador o en la vista para ajustar las rutas
$is_from_controller = strpos($_SERVER['REQUEST_URI'], 'Controllers/') !== false;
$home_path = $is_from_controller ? '../Vista/home.php' : 'home.php';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<nav class="sidebar">
    <h3><i class="fa-solid fa-clipboard-list"></i> Nuevo Plan</h3>
    <a href="../Controllers/PlanController.php?action=editarVisionMision&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'vision_mision.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-eye"></i> Visión y Misión
    </a>
    <a href="../Controllers/PlanController.php?action=editarValores&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'valores.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-gem"></i> Valores
    </a>
    <a href="../Controllers/PlanController.php?action=editarObjetivos&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'objetivos_estrategicos.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-bullseye"></i> Objetivos Estratégicos
    </a>
    <a href="../Controllers/PlanController.php?action=editarCadenaValor&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'cadena_valor.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-link"></i> Cadena de Valor
    </a>
    <a href="../Controllers/PlanController.php?action=editarMatrizBCG&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'matriz_bcg.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-table-cells-large"></i> Matriz BCG
    </a>
    <a href="../Controllers/PlanController.php?action=editarFuerzasPorter&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'fuerzas_porter.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-shield-halved"></i> Fuerzas Porter
    </a>
    <a href="../Controllers/PlanController.php?action=editarPEST&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'pest_nuevo.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-microscope"></i> Análisis PEST
    </a>
    <a href="../Controllers/PlanController.php?action=editarEstrategias&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'identificacion_de_estrategias.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-lightbulb"></i> Identificación de Estrategias
    </a>
    <a href="../Controllers/PlanController.php?action=editarMatrizCame&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'matriz_came_ejemplo.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-layer-group"></i> Matriz CAME
    </a>
    <a href="../Controllers/PlanController.php?action=editarResumenEjecutivo&id_plan=<?php echo isset($plan_id) ? htmlspecialchars($plan_id) : ''; ?>" class="sidebar-link <?php echo ($current_page == 'resumen_ejecutivo.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-file-lines"></i> Resumen Ejecutivo
    </a>
    <a href="<?php echo $home_path; ?>" class="sidebar-link home-link">
        <i class="fa-solid fa-house"></i> Volver al Home
    </a>
</nav>
<style>
/* Estilos del Sidebar Mejorados */
.sidebar {
    width: 250px;
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: #fff;
    padding: 20px;
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    box-shadow: 4px 0 15px rgba(0,0,0,0.1);
    z-index: 1000;
    overflow-y: auto;
}

.sidebar h3 {
    color: #ecf0f1;
    margin-bottom: 30px;
    font-size: 18px;
    font-weight: 600;
    text-align: center;
    padding-bottom: 15px;
    border-bottom: 2px solid #3498db;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.sidebar-link {
    color: #bdc3c7;
    text-decoration: none;
    padding: 12px 16px;
    margin-bottom: 8px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.sidebar-link i {
    min-width: 18px;
    font-size: 16px;
    opacity: 0.8;
    transition: all 0.3s ease;
}

/* Efecto hover */
.sidebar-link:hover {
    background: rgba(52, 152, 219, 0.1);
    color: #3498db;
    transform: translateX(5px);
    box-shadow: 0 3px 10px rgba(52, 152, 219, 0.2);
}

.sidebar-link:hover i {
    opacity: 1;
    color: #3498db;
    transform: scale(1.1);
}

/* Link activo */
.sidebar-link.active {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: #ffffff;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
    transform: translateX(3px);
}

.sidebar-link.active i {
    color: #ffffff;
    opacity: 1;
    transform: scale(1.1);
}

.sidebar-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background: #ecf0f1;
    border-radius: 0 4px 4px 0;
}

/* Botón de home especial */
.sidebar-link.home-link {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #34495e;
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
    color: #ffffff;
    font-weight: 600;
    justify-content: center;
    text-align: center;
}

.sidebar-link.home-link:hover {
    background: linear-gradient(135deg, #229954 0%, #1e8449 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
}

.sidebar-link.home-link i {
    color: #ffffff;
}

/* Scrollbar personalizada */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: #34495e;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #3498db;
    border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #2980b9;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        width: 200px;
    }
    
    .sidebar h3 {
        font-size: 16px;
    }
    
    .sidebar-link {
        font-size: 13px;
        padding: 10px 12px;
    }
    
    .sidebar-link i {
        font-size: 14px;
    }
}

@media (max-width: 600px) {
    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
        flex-direction: row;
        overflow-x: auto;
        overflow-y: hidden;
        padding: 10px;
    }
    
    .sidebar h3 {
        display: none;
    }
    
    .sidebar-link {
        flex-shrink: 0;
        margin-right: 10px;
        margin-bottom: 0;
        white-space: nowrap;
    }
    
    .sidebar-link.home-link {
        margin-top: 0;
        border-top: none;
        padding-top: 12px;
    }
}

/* Animaciones adicionales */
@keyframes slideIn {
    from {
        transform: translateX(-100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.sidebar {
    animation: slideIn 0.5s ease-out;
}

/* Efecto de pulso para el link activo */
@keyframes pulse {
    0% {
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
    }
    50% {
        box-shadow: 0 4px 20px rgba(52, 152, 219, 0.5);
    }
    100% {
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
    }
}

.sidebar-link.active {
    animation: pulse 2s infinite;
}
</style>
