<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Obtener datos del plan desde la sesión temporal o base de datos
$plan_temporal = $_SESSION['plan_temporal'] ?? null;
$paso_actual = $_GET['paso'] ?? 1;

// Si no hay plan temporal, intentar cargar un plan existente
if (!$plan_temporal && isset($_GET['id'])) {
    require_once '../Controllers/PlanEstrategicoController.php';
    $controller = new PlanEstrategicoController();
    $plan = $controller->obtenerDetallePlan($_GET['id']);
    
    if ($plan) {
        // Convertir datos del plan a formato de sesión temporal para edición
        $plan_temporal = [
            'id_plan' => $plan['id_plan'],
            'datos' => [
                'informacion_general' => [
                    'nombre_plan' => $plan['nombre_plan'] ?? '',
                    'empresa' => $plan['empresa'] ?? '',
                    'descripcion' => $plan['descripcion'] ?? ''
                ],
                'vision_mision' => $plan['vision_mision'] ?? [],
                'valores' => $plan['valores'] ?? [],
                'objetivos' => $plan['objetivos'] ?? [],
                'cadena_valor' => $plan['cadena_valor'] ?? [],
                'matriz_bcg' => $plan['matriz_bcg'] ?? [],
                'fuerzas_porter' => $plan['fuerzas_porter'] ?? [],
                'pest' => $plan['pest'] ?? [],
                'estrategias' => $plan['estrategias'] ?? [],
                'matriz_came' => $plan['matriz_came'] ?? [],
                'resumen_ejecutivo' => $plan['resumen_ejecutivo'] ?? []
            ]
        ];
    }
}

// Configuración de pasos del wizard
$pasos_wizard = [
    1 => ['titulo' => 'Información General', 'icono' => 'fas fa-info-circle', 'archivo' => 'nuevo_plan.php'],
    2 => ['titulo' => 'Visión y Misión', 'icono' => 'fas fa-eye', 'archivo' => 'vision_mision.php'],
    3 => ['titulo' => 'Valores', 'icono' => 'fas fa-heart', 'archivo' => 'valores.php'],
    4 => ['titulo' => 'Objetivos Estratégicos', 'icono' => 'fas fa-bullseye', 'archivo' => 'objetivos_estrategicos.php'],
    5 => ['titulo' => 'Cadena de Valor', 'icono' => 'fas fa-link', 'archivo' => 'cadena_valor.php'],
    6 => ['titulo' => 'Matriz BCG', 'icono' => 'fas fa-th', 'archivo' => 'matriz_bcg.php'],
    7 => ['titulo' => 'Fuerzas de Porter', 'icono' => 'fas fa-shield-alt', 'archivo' => 'fuerzas_porter.php'],
    8 => ['titulo' => 'Análisis PEST', 'icono' => 'fas fa-chart-line', 'archivo' => 'pest_nuevo.php'],
    9 => ['titulo' => 'Identificación de Estrategias', 'icono' => 'fas fa-lightbulb', 'archivo' => 'identificacion_de_estrategias.php'],
    10 => ['titulo' => 'Matriz CAME', 'icono' => 'fas fa-table', 'archivo' => 'matriz_came_ejemplo.php'],
    11 => ['titulo' => 'Resumen Ejecutivo', 'icono' => 'fas fa-file-alt', 'archivo' => 'resumen_ejecutivo.php']
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Plan Estratégico - Wizard</title>
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Wizard Sidebar */
        .wizard-sidebar {
            width: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .wizard-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .wizard-header h2 {
            font-size: 1.2em;
            margin-bottom: 5px;
        }

        .wizard-header p {
            font-size: 0.9em;
            opacity: 0.8;
        }

        .wizard-steps {
            padding: 10px 0;
        }

        .wizard-step {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            text-decoration: none;
            color: white;
        }

        .wizard-step:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            text-decoration: none;
        }

        .wizard-step.active {
            background-color: rgba(255,255,255,0.2);
            border-left-color: #ffd700;
        }

        .wizard-step.completed {
            background-color: rgba(255,255,255,0.1);
        }

        .wizard-step .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 0.9em;
        }

        .wizard-step.active .step-icon {
            background-color: #ffd700;
            color: #333;
        }

        .wizard-step.completed .step-icon {
            background-color: #28a745;
            color: white;
        }

        .step-content {
            flex: 1;
        }

        .step-title {
            font-weight: 600;
            font-size: 0.95em;
            margin-bottom: 2px;
        }

        .step-number {
            font-size: 0.8em;
            opacity: 0.7;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 300px;
            background: white;
        }

        .content-header {
            background: white;
            padding: 20px 30px;
            border-bottom: 1px solid #eee;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .content-header h1 {
            color: #333;
            margin-bottom: 5px;
        }

        .content-header .breadcrumb {
            color: #666;
            font-size: 0.9em;
        }

        .content-body {
            padding: 30px;
            max-width: 1000px;
        }

        /* Section Styles */
        .section-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px 25px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 1.3em;
            color: #333;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-content {
            padding: 25px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        /* Data Display */
        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .data-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .data-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            font-size: 0.9em;
        }

        .data-value {
            color: #333;
            line-height: 1.5;
        }

        .list-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }

        .list-item {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            border-left: 4px solid #28a745;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .list-item:last-child {
            margin-bottom: 0;
        }

        /* Edit Mode */
        .edit-mode {
            border: 2px dashed #667eea;
            background: #f8f9ff;
        }

        .edit-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        /* Navigation */
        .step-navigation {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .wizard-sidebar {
                width: 250px;
                transform: translateX(-250px);
                transition: transform 0.3s ease;
            }

            .wizard-sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background: #667eea;
                color: white;
                border: none;
                padding: 10px;
                border-radius: 50%;
                cursor: pointer;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Status indicators */
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-completed { background: #28a745; }
        .status-current { background: #ffd700; }
        .status-pending { background: #6c757d; }

        /* Toast notifications */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 1002;
            transform: translateX(300px);
            transition: transform 0.3s ease;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast.error {
            background: #dc3545;
        }

        .toast.warning {
            background: #ffc107;
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Mobile menu toggle -->
    <button class="mobile-menu-toggle d-md-none" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="wrapper">
        <!-- Wizard Sidebar -->
        <aside class="wizard-sidebar" id="wizardSidebar">
            <div class="wizard-header">
                <h2><i class="fas fa-clipboard-list"></i> Plan Estratégico</h2>
                <p><?php echo htmlspecialchars($plan_temporal['datos']['informacion_general']['empresa'] ?? 'Mi Empresa'); ?></p>
            </div>
            
            <nav class="wizard-steps">                <?php foreach ($pasos_wizard as $numero => $paso): ?>
                    <a href="?paso=<?php echo $numero; ?><?php echo isset($_GET['id']) ? '&id=' . $_GET['id'] : ''; ?>" 
                       class="wizard-step <?php echo ($numero == $paso_actual) ? 'active' : ''; ?> <?php echo isset($plan_temporal['datos']) && isPasoCompleto($numero, $plan_temporal['datos']) ? 'completed' : ''; ?>">
                        <div class="step-icon">
                            <?php if (isset($plan_temporal['datos']) && isPasoCompleto($numero, $plan_temporal['datos'])): ?>
                                <i class="fas fa-check"></i>
                            <?php elseif ($numero == $paso_actual): ?>
                                <i class="<?php echo $paso['icono']; ?>"></i>
                            <?php else: ?>
                                <?php echo $numero; ?>
                            <?php endif; ?>
                        </div>
                        <div class="step-content">
                            <div class="step-title"><?php echo $paso['titulo']; ?></div>
                            <div class="step-number">Paso <?php echo $numero; ?> de <?php echo count($pasos_wizard); ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <h1>
                    <i class="<?php echo $pasos_wizard[$paso_actual]['icono']; ?>"></i>
                    <?php echo $pasos_wizard[$paso_actual]['titulo']; ?>
                </h1>
                <div class="breadcrumb">
                    Plan Estratégico › Paso <?php echo $paso_actual; ?> de <?php echo count($pasos_wizard); ?>
                </div>
            </header>

            <div class="content-body fade-in">                <?php
                // Renderizar el contenido del paso actual
                switch ($paso_actual) {
                    case 1:
                        renderInformacionGeneral($plan_temporal);
                        break;
                    case 2:
                        renderVisionMision($plan_temporal);
                        break;
                    case 3:
                        renderValores($plan_temporal);
                        break;
                    case 4:
                        renderObjetivos($plan_temporal);
                        break;
                    case 5:
                        renderCadenaValor($plan_temporal);
                        break;
                    case 6:
                        renderMatrizBCG($plan_temporal);
                        break;
                    case 7:
                        renderFuerzasPorter($plan_temporal);
                        break;
                    case 8:
                        renderPEST($plan_temporal);
                        break;
                    case 9:
                        renderEstrategias($plan_temporal);
                        break;
                    case 10:
                        renderMatrizCAME($plan_temporal);
                        break;
                    case 11:
                        renderResumenEjecutivo($plan_temporal);
                        break;
                    default:
                        echo '<div class="section-card"><div class="section-content"><p>Paso no encontrado.</p></div></div>';
                }
                ?>
            </div>
        </main>
    </div>

    <!-- Step Navigation -->
    <div class="step-navigation">
        <?php if ($paso_actual > 1): ?>
            <a href="?paso=<?php echo $paso_actual - 1; ?><?php echo isset($_GET['id']) ? '&id=' . $_GET['id'] : ''; ?>" 
               class="btn btn-secondary">
                <i class="fas fa-chevron-left"></i> Anterior
            </a>
        <?php endif; ?>
        
        <?php if ($paso_actual < count($pasos_wizard)): ?>
            <a href="?paso=<?php echo $paso_actual + 1; ?><?php echo isset($_GET['id']) ? '&id=' . $_GET['id'] : ''; ?>" 
               class="btn btn-primary">
                Siguiente <i class="fas fa-chevron-right"></i>
            </a>
        <?php else: ?>
            <button onclick="finalizarPlan()" class="btn btn-success">
                <i class="fas fa-check"></i> Finalizar Plan
            </button>
        <?php endif; ?>
    </div>

    <!-- Toast container -->
    <div id="toastContainer"></div>

    <script>
        // Toggle sidebar en móvil
        function toggleSidebar() {
            document.getElementById('wizardSidebar').classList.toggle('active');
        }

        // Función para mostrar toast
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'exclamation'}"></i> ${message}`;
            
            document.getElementById('toastContainer').appendChild(toast);
            
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Función para guardar datos
        function guardarDatos(paso, datos) {
            const formData = new FormData();
            formData.append('paso', paso);
            formData.append('nombre_paso', '<?php echo array_search($pasos_wizard[$paso_actual], $pasos_wizard); ?>');
            
            for (const [key, value] of Object.entries(datos)) {
                if (Array.isArray(value)) {
                    value.forEach((item, index) => {
                        formData.append(`${key}[${index}]`, item);
                    });
                } else {
                    formData.append(key, value);
                }
            }

            return fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Datos guardados correctamente');
                    return true;
                } else {
                    showToast(data.message || 'Error al guardar', 'error');
                    return false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error de conexión', 'error');
                return false;
            });
        }

        // Función para finalizar plan
        function finalizarPlan() {
            if (confirm('¿Está seguro de que desea finalizar el plan estratégico?')) {
                fetch('../index.php?controller=PlanEstrategico&action=finalizarPlan', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Plan finalizado correctamente');
                        setTimeout(() => {
                            window.location.href = 'home.php';
                        }, 1500);
                    } else {
                        showToast(data.message || 'Error al finalizar', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error de conexión', 'error');
                });
            }
        }

        // Auto-guardar cambios
        let autoSaveTimeout;
        function autoSave() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                // Implementar auto-guardar aquí si es necesario
            }, 2000);
        }

        // Event listeners para campos del formulario
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', autoSave);
            });
        });
    </script>
</body>
</html>

<?php
// Función para verificar si un paso está completo
function isPasoCompleto($numero, $datos) {
    switch ($numero) {
        case 1:
            return !empty($datos['informacion_general']['nombre_plan']) && 
                   !empty($datos['informacion_general']['empresa']);
        case 2:
            return !empty($datos['vision_mision']['vision']) && 
                   !empty($datos['vision_mision']['mision']);
        case 3:
            return !empty($datos['valores']);
        case 4:
            return !empty($datos['objetivos']);
        case 5:
            return !empty($datos['cadena_valor']);
        case 6:
            return !empty($datos['matriz_bcg']);
        case 7:
            return !empty($datos['fuerzas_porter']);
        case 8:
            return !empty($datos['pest']);
        case 9:
            return !empty($datos['estrategias']);
        case 10:
            return !empty($datos['matriz_came']);
        case 11:
            return !empty($datos['resumen_ejecutivo']);
        default:
            return false;
    }
}

// Método para renderizar Información General
function renderInformacionGeneral($plan_temporal) {
    $datos = $plan_temporal['datos']['informacion_general'] ?? [];
    ?>
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-info-circle"></i>
                Información General del Plan
            </h3>
            <button onclick="toggleEdit('info-general')" class="btn btn-outline">
                <i class="fas fa-edit"></i> Editar
            </button>
        </div>
        <div class="section-content">
            <div id="info-general-view">
                <div class="data-grid">
                    <div class="data-item">
                        <div class="data-label">Nombre del Plan</div>
                        <div class="data-value"><?php echo htmlspecialchars($datos['nombre_plan'] ?? 'No definido'); ?></div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Empresa</div>
                        <div class="data-value"><?php echo htmlspecialchars($datos['empresa'] ?? 'No definida'); ?></div>
                    </div>
                </div>
                <?php if (!empty($datos['descripcion'])): ?>
                <div class="data-item">
                    <div class="data-label">Descripción</div>
                    <div class="data-value"><?php echo nl2br(htmlspecialchars($datos['descripcion'])); ?></div>
                </div>
                <?php endif; ?>
            </div>
            
            <div id="info-general-edit" style="display: none;" class="edit-mode">
                <form id="form-info-general">
                    <div class="form-group">
                        <label for="nombre_plan">Nombre del Plan *</label>
                        <input type="text" id="nombre_plan" name="nombre_plan" 
                               value="<?php echo htmlspecialchars($datos['nombre_plan'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="empresa">Empresa *</label>
                        <input type="text" id="empresa" name="empresa" 
                               value="<?php echo htmlspecialchars($datos['empresa'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" 
                                  placeholder="Describa brevemente el propósito de este plan estratégico..."><?php echo htmlspecialchars($datos['descripcion'] ?? ''); ?></textarea>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" onclick="cancelEdit('info-general')" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('form-info-general').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const datos = Object.fromEntries(formData.entries());
            
            guardarDatos(1, datos).then(success => {
                if (success) {
                    location.reload();
                }
            });
        });
    </script>
    <?php
}

// Método para renderizar Visión y Misión
function renderVisionMision($plan_temporal) {
    $datos = $plan_temporal['datos']['vision_mision'] ?? [];
    ?>
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-eye"></i>
                Visión y Misión
            </h3>
            <button onclick="toggleEdit('vision-mision')" class="btn btn-outline">
                <i class="fas fa-edit"></i> Editar
            </button>
        </div>
        <div class="section-content">
            <div id="vision-mision-view">
                <div class="data-grid">
                    <div class="data-item">
                        <div class="data-label">Visión</div>
                        <div class="data-value"><?php echo nl2br(htmlspecialchars($datos['vision'] ?? 'No definida')); ?></div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Misión</div>
                        <div class="data-value"><?php echo nl2br(htmlspecialchars($datos['mision'] ?? 'No definida')); ?></div>
                    </div>
                </div>
            </div>
            
            <div id="vision-mision-edit" style="display: none;" class="edit-mode">
                <form id="form-vision-mision">
                    <div class="form-group">
                        <label for="vision">Visión *</label>
                        <textarea id="vision" name="vision" required 
                                  placeholder="Describa cómo ve su empresa en el futuro..."><?php echo htmlspecialchars($datos['vision'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="mision">Misión *</label>
                        <textarea id="mision" name="mision" required 
                                  placeholder="Describa el propósito fundamental de su empresa..."><?php echo htmlspecialchars($datos['mision'] ?? ''); ?></textarea>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" onclick="cancelEdit('vision-mision')" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('form-vision-mision').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const datos = Object.fromEntries(formData.entries());
            
            guardarDatos(2, datos).then(success => {
                if (success) {
                    location.reload();
                }
            });
        });
    </script>
    <?php
}

// Método para renderizar Valores
function renderValores($plan_temporal) {
    $valores = $plan_temporal['datos']['valores'] ?? [];
    ?>
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-heart"></i>
                Valores Corporativos
            </h3>
            <button onclick="toggleEdit('valores')" class="btn btn-outline">
                <i class="fas fa-edit"></i> Editar
            </button>
        </div>
        <div class="section-content">
            <div id="valores-view">
                <?php if (!empty($valores)): ?>
                <div class="list-container">
                    <?php foreach ($valores as $index => $valor): ?>
                    <div class="list-item">
                        <strong><?php echo htmlspecialchars($valor['valor'] ?? $valor); ?></strong>
                        <?php if (isset($valor['descripcion']) && !empty($valor['descripcion'])): ?>
                        <br><small class="text-muted"><?php echo htmlspecialchars($valor['descripcion']); ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-muted">No se han definido valores corporativos.</p>
                <?php endif; ?>
            </div>
            
            <div id="valores-edit" style="display: none;" class="edit-mode">
                <form id="form-valores">
                    <div id="valores-container">
                        <?php if (!empty($valores)): ?>
                            <?php foreach ($valores as $index => $valor): ?>
                            <div class="form-group valores-item">
                                <label>Valor <?php echo $index + 1; ?></label>
                                <input type="text" name="valores[]" 
                                       value="<?php echo htmlspecialchars(is_array($valor) ? $valor['valor'] : $valor); ?>" 
                                       placeholder="Nombre del valor" required>
                                <?php if (is_array($valor) && isset($valor['descripcion'])): ?>
                                <textarea name="descripciones[]" 
                                          placeholder="Descripción del valor (opcional)"><?php echo htmlspecialchars($valor['descripcion']); ?></textarea>
                                <?php else: ?>
                                <textarea name="descripciones[]" 
                                          placeholder="Descripción del valor (opcional)"></textarea>
                                <?php endif; ?>
                                <button type="button" onclick="removeValor(this)" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <div class="form-group valores-item">
                            <label>Valor 1</label>
                            <input type="text" name="valores[]" placeholder="Nombre del valor" required>
                            <textarea name="descripciones[]" placeholder="Descripción del valor (opcional)"></textarea>
                            <button type="button" onclick="removeValor(this)" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" onclick="addValor()" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Agregar Valor
                    </button>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" onclick="cancelEdit('valores')" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function addValor() {
            const container = document.getElementById('valores-container');
            const count = container.children.length + 1;
            const div = document.createElement('div');
            div.className = 'form-group valores-item';
            div.innerHTML = `
                <label>Valor ${count}</label>
                <input type="text" name="valores[]" placeholder="Nombre del valor" required>
                <textarea name="descripciones[]" placeholder="Descripción del valor (opcional)"></textarea>
                <button type="button" onclick="removeValor(this)" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            `;
            container.appendChild(div);
        }

        function removeValor(button) {
            if (document.querySelectorAll('.valores-item').length > 1) {
                button.parentElement.remove();
                // Renumerar etiquetas
                document.querySelectorAll('.valores-item label').forEach((label, index) => {
                    label.textContent = `Valor ${index + 1}`;
                });
            }
        }

        document.getElementById('form-valores').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const valores = formData.getAll('valores[]');
            const descripciones = formData.getAll('descripciones[]');
            
            const datos = {
                valores: valores.map((valor, index) => ({
                    valor: valor,
                    descripcion: descripciones[index] || ''
                }))
            };
            
            guardarDatos(3, datos).then(success => {
                if (success) {
                    location.reload();
                }
            });
        });
    </script>
    <?php
}

// Continuar con los otros métodos...
function renderObjetivos($plan_temporal) {
    $objetivos = $plan_temporal['datos']['objetivos'] ?? [];
    ?>
    <div class="section-card">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-bullseye"></i>
                Objetivos Estratégicos
            </h3>
            <button onclick="toggleEdit('objetivos')" class="btn btn-outline">
                <i class="fas fa-edit"></i> Editar
            </button>
        </div>
        <div class="section-content">
            <div id="objetivos-view">
                <?php
                $objetivos_generales = array_filter($objetivos, function($obj) { 
                    return (is_array($obj) ? $obj['categoria'] : 'general') === 'general'; 
                });
                $objetivos_especificos = array_filter($objetivos, function($obj) { 
                    return (is_array($obj) ? $obj['categoria'] : 'especifico') === 'especifico'; 
                });
                ?>
                
                <?php if (!empty($objetivos_generales)): ?>
                <h4>Objetivos Generales</h4>
                <div class="list-container">
                    <?php foreach ($objetivos_generales as $objetivo): ?>
                    <div class="list-item">
                        <?php echo htmlspecialchars(is_array($objetivo) ? $objetivo['objetivo'] : $objetivo); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($objetivos_especificos)): ?>
                <h4>Objetivos Específicos</h4>
                <div class="list-container">
                    <?php foreach ($objetivos_especificos as $objetivo): ?>
                    <div class="list-item">
                        <?php echo htmlspecialchars(is_array($objetivo) ? $objetivo['objetivo'] : $objetivo); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (empty($objetivos)): ?>
                <p class="text-muted">No se han definido objetivos estratégicos.</p>
                <?php endif; ?>
            </div>
            
            <div id="objetivos-edit" style="display: none;" class="edit-mode">
                <form id="form-objetivos">
                    <h4>Objetivos Generales</h4>
                    <div id="objetivos-generales-container">
                        <?php 
                        $general_count = 0;
                        foreach ($objetivos_generales as $objetivo): 
                            $general_count++;
                        ?>
                        <div class="form-group objetivo-item">
                            <label>Objetivo General <?php echo $general_count; ?></label>
                            <textarea name="objetivos_generales[]" required><?php echo htmlspecialchars(is_array($objetivo) ? $objetivo['objetivo'] : $objetivo); ?></textarea>
                            <button type="button" onclick="removeObjetivo(this)" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if ($general_count === 0): ?>
                        <div class="form-group objetivo-item">
                            <label>Objetivo General 1</label>
                            <textarea name="objetivos_generales[]" required placeholder="Describa un objetivo general..."></textarea>
                            <button type="button" onclick="removeObjetivo(this)" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" onclick="addObjetivo('generales')" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Agregar Objetivo General
                    </button>

                    <h4 style="margin-top: 30px;">Objetivos Específicos</h4>
                    <div id="objetivos-especificos-container">
                        <?php 
                        $especifico_count = 0;
                        foreach ($objetivos_especificos as $objetivo): 
                            $especifico_count++;
                        ?>
                        <div class="form-group objetivo-item">
                            <label>Objetivo Específico <?php echo $especifico_count; ?></label>
                            <textarea name="objetivos_especificos[]" required><?php echo htmlspecialchars(is_array($objetivo) ? $objetivo['objetivo'] : $objetivo); ?></textarea>
                            <button type="button" onclick="removeObjetivo(this)" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if ($especifico_count === 0): ?>
                        <div class="form-group objetivo-item">
                            <label>Objetivo Específico 1</label>
                            <textarea name="objetivos_especificos[]" required placeholder="Describa un objetivo específico..."></textarea>
                            <button type="button" onclick="removeObjetivo(this)" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" onclick="addObjetivo('especificos')" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Agregar Objetivo Específico
                    </button>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" onclick="cancelEdit('objetivos')" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function addObjetivo(tipo) {
            const container = document.getElementById(`objetivos-${tipo}-container`);
            const count = container.children.length + 1;
            const div = document.createElement('div');
            div.className = 'form-group objetivo-item';
            const label = tipo === 'generales' ? 'General' : 'Específico';
            div.innerHTML = `
                <label>Objetivo ${label} ${count}</label>
                <textarea name="objetivos_${tipo}[]" required placeholder="Describa un objetivo ${label.toLowerCase()}..."></textarea>
                <button type="button" onclick="removeObjetivo(this)" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            `;
            container.appendChild(div);
        }

        function removeObjetivo(button) {
            const container = button.parentElement.parentElement;
            if (container.children.length > 1) {
                button.parentElement.remove();
                // Renumerar etiquetas
                Array.from(container.children).forEach((item, index) => {
                    const label = item.querySelector('label');
                    const currentText = label.textContent;
                    const type = currentText.includes('General') ? 'General' : 'Específico';
                    label.textContent = `Objetivo ${type} ${index + 1}`;
                });
            }
        }

        document.getElementById('form-objetivos').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const generales = formData.getAll('objetivos_generales[]');
            const especificos = formData.getAll('objetivos_especificos[]');
            
            const datos = {
                objetivos: [
                    ...generales.map(obj => ({ objetivo: obj, categoria: 'general' })),
                    ...especificos.map(obj => ({ objetivo: obj, categoria: 'especifico' }))
                ]
            };
            
            guardarDatos(4, datos).then(success => {
                if (success) {
                    location.reload();
                }
            });
        });
    </script>
    <?php
}

// Agregar métodos simplificados para los otros pasos
function renderCadenaValor($plan_temporal) {
    echo '<div class="section-card"><div class="section-content"><p>Sección Cadena de Valor - En desarrollo</p></div></div>';
}

function renderMatrizBCG($plan_temporal) {
    echo '<div class="section-card"><div class="section-content"><p>Sección Matriz BCG - En desarrollo</p></div></div>';
}

function renderFuerzasPorter($plan_temporal) {
    echo '<div class="section-card"><div class="section-content"><p>Sección Fuerzas de Porter - En desarrollo</p></div></div>';
}

function renderPEST($plan_temporal) {
    echo '<div class="section-card"><div class="section-content"><p>Sección Análisis PEST - En desarrollo</p></div></div>';
}

function renderEstrategias($plan_temporal) {
    echo '<div class="section-card"><div class="section-content"><p>Sección Identificación de Estrategias - En desarrollo</p></div></div>';
}

function renderMatrizCAME($plan_temporal) {
    echo '<div class="section-card"><div class="section-content"><p>Sección Matriz CAME - En desarrollo</p></div></div>';
}

function renderResumenEjecutivo($plan_temporal) {
    echo '<div class="section-card"><div class="section-content"><p>Sección Resumen Ejecutivo - En desarrollo</p></div></div>';
}
?>

<script>
// Funciones globales para toggle edit
function toggleEdit(section) {
    const viewDiv = document.getElementById(section + '-view');
    const editDiv = document.getElementById(section + '-edit');
    
    if (viewDiv && editDiv) {
        viewDiv.style.display = viewDiv.style.display === 'none' ? 'block' : 'none';
        editDiv.style.display = editDiv.style.display === 'none' ? 'block' : 'none';
    }
}

function cancelEdit(section) {
    const viewDiv = document.getElementById(section + '-view');
    const editDiv = document.getElementById(section + '-edit');
    
    if (viewDiv && editDiv) {
        viewDiv.style.display = 'block';
        editDiv.style.display = 'none';
        
        // Reset form if exists
        const form = editDiv.querySelector('form');
        if (form) {
            form.reset();
        }
    }
}
</script>
