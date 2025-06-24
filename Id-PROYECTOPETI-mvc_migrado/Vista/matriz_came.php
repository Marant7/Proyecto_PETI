<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    header("Location: ../Vista/index.php");
    exit();
}

// Obtener datos FODA de la sesión
$foda_data = $_SESSION['foda_data'] ?? null;

// Procesar el formulario CAME
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_came'])) {
        $acciones_came = [];
        
        // Recopilar todas las acciones
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'accion_') === 0 && !empty($value)) {
                $acciones_came[] = $value;
            }
        }
        
        // Guardar en sesión
        $_SESSION['matriz_came'] = [
            'acciones' => $acciones_came,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];
        
        // Mensaje de éxito
        $mensaje_exito = "Matriz CAME guardada exitosamente.";
    }
}

// Verificar si hay datos FODA disponibles
if (!$foda_data) {
    header("Location: identificacion_de_estrategias.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PETI - Matriz CAME</title>
    
    <!-- Material Design CSS -->
    <link rel="stylesheet" href="../public/css/material.min.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">    <link rel="stylesheet" href="../public/css/normalize.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/plan-estrategico.css">
</head>
<body>
    <!-- pageContent -->
    <section class="full-width pageContent">
        <section class="full-width header-well">
            <div class="full-width header-well-icon">
                <i class="zmdi zmdi-assignment-check"></i>
            </div>
            <div class="full-width header-well-text">
                <p class="text-condensedLight">
                    MATRIZ CAME - PLAN DE ACCIÓN ESTRATÉGICO
                </p>
            </div>
        </section>

        <div class="full-width divider-menu-h"></div>

        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <div class="full-width panel mdl-shadow--2dp">
                    <div class="full-width panel-tittle bg-primary text-center tittles">
                        11. MATRIZ CAME
                    </div>
                    <div class="full-width panel-content">
                        <?php if (isset($mensaje_exito)): ?>
                        <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; background-color: #dff0d8; border: 1px solid #d6e9c6; border-radius: 4px; color: #3c763d;">
                            <strong><i class="zmdi zmdi-check"></i></strong> <?= $mensaje_exito ?>
                        </div>
                        <?php endif; ?>

                        <div class="came-description" style="margin-bottom: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #17a2b8;">
                            <p style="margin-bottom: 15px; font-size: 16px;">
                                A continuación y para finalizar de elaborar un Plan Estratégico, además de tener 
                                identificada la estrategia es necesario determinar acciones que permitan corregir las 
                                debilidades, afrontar las amenazas, mantener las fortalezas y explotar las oportunidades.
                            </p>
                            <p style="margin: 0; font-style: italic; font-weight: 500; color: #495057;">
                                <strong>Reflexione y anote acciones a llevar a cabo teniendo en cuenta que estas acciones 
                                deben favorecer la ejecución exitosa de la estrategia general identificada.</strong>
                            </p>
                        </div>

                        <form method="POST" id="cameForm">
                            <div class="came-matrix" style="overflow-x: auto;">                                <table style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px;">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold; width: 100px;">Acciones</th>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold; width: 300px;">Debilidad a Corregir</th>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold;">Acción Correctiva</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="background-color: #5bc0de; color: white;">
                                            <td rowspan="<?= count($foda_data['debilidades']) + 1 ?>" style="padding: 15px; text-align: center; font-weight: bold; vertical-align: middle; font-size: 24px;">C</td>
                                        </tr>
                                        <?php foreach ($foda_data['debilidades'] as $index => $debilidad): ?>
                                        <tr style="border: 1px solid #ddd;">
                                            <td style="padding: 10px; border: 1px solid #ddd; background-color: #FFE0B2; font-size: 12px; line-height: 1.3;">
                                                <strong>D<?= $index + 1 ?>:</strong><br>
                                                <?= htmlspecialchars($debilidad) ?>
                                            </td>
                                            <td style="padding: 8px; border: 1px solid #ddd;">
                                                <textarea name="accion_c<?= $index + 1 ?>" rows="3" 
                                                         placeholder="¿Qué acción específica implementará para corregir esta debilidad?" 
                                                         style="width: 100%; border: none; resize: vertical; font-family: inherit; padding: 5px;"></textarea>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>                                <table style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px;">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold; width: 100px;">Acciones</th>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold; width: 300px;">Amenaza a Afrontar</th>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold;">Acción Defensiva</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="background-color: #5bc0de; color: white;">
                                            <td rowspan="<?= count($foda_data['amenazas']) + 1 ?>" style="padding: 15px; text-align: center; font-weight: bold; vertical-align: middle; font-size: 24px;">A</td>
                                        </tr>
                                        <?php foreach ($foda_data['amenazas'] as $index => $amenaza): ?>
                                        <tr style="border: 1px solid #ddd;">
                                            <td style="padding: 10px; border: 1px solid #ddd; background-color: #FFCDD2; font-size: 12px; line-height: 1.3;">
                                                <strong>A<?= $index + 1 ?>:</strong><br>
                                                <?= htmlspecialchars($amenaza) ?>
                                            </td>
                                            <td style="padding: 8px; border: 1px solid #ddd;">
                                                <textarea name="accion_a<?= $index + 1 ?>" rows="3" 
                                                         placeholder="¿Qué acción específica implementará para afrontar esta amenaza?" 
                                                         style="width: 100%; border: none; resize: vertical; font-family: inherit; padding: 5px;"></textarea>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>                                <table style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px;">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold; width: 100px;">Acciones</th>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold; width: 300px;">Fortaleza a Mantener</th>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold;">Acción de Mantenimiento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="background-color: #5bc0de; color: white;">
                                            <td rowspan="<?= count($foda_data['fortalezas']) + 1 ?>" style="padding: 15px; text-align: center; font-weight: bold; vertical-align: middle; font-size: 24px;">M</td>
                                        </tr>
                                        <?php foreach ($foda_data['fortalezas'] as $index => $fortaleza): ?>
                                        <tr style="border: 1px solid #ddd;">
                                            <td style="padding: 10px; border: 1px solid #ddd; background-color: #C8E6C9; font-size: 12px; line-height: 1.3;">
                                                <strong>F<?= $index + 1 ?>:</strong><br>
                                                <?= htmlspecialchars($fortaleza) ?>
                                            </td>
                                            <td style="padding: 8px; border: 1px solid #ddd;">
                                                <textarea name="accion_m<?= $index + 1 ?>" rows="3" 
                                                         placeholder="¿Qué acción específica implementará para mantener esta fortaleza?" 
                                                         style="width: 100%; border: none; resize: vertical; font-family: inherit; padding: 5px;"></textarea>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>                                <table style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 14px;">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold; width: 100px;">Acciones</th>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold; width: 300px;">Oportunidad a Explotar</th>
                                            <th style="background-color: #5bc0de; color: white; padding: 15px; text-align: center; font-weight: bold;">Acción de Aprovechamiento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="background-color: #5bc0de; color: white;">
                                            <td rowspan="<?= count($foda_data['oportunidades']) + 1 ?>" style="padding: 15px; text-align: center; font-weight: bold; vertical-align: middle; font-size: 24px;">E</td>
                                        </tr>
                                        <?php foreach ($foda_data['oportunidades'] as $index => $oportunidad): ?>
                                        <tr style="border: 1px solid #ddd;">
                                            <td style="padding: 10px; border: 1px solid #ddd; background-color: #E3F2FD; font-size: 12px; line-height: 1.3;">
                                                <strong>O<?= $index + 1 ?>:</strong><br>
                                                <?= htmlspecialchars($oportunidad) ?>
                                            </td>
                                            <td style="padding: 8px; border: 1px solid #ddd;">
                                                <textarea name="accion_e<?= $index + 1 ?>" rows="3" 
                                                         placeholder="¿Qué acción específica implementará para explotar esta oportunidad?" 
                                                         style="width: 100%; border: none; resize: vertical; font-family: inherit; padding: 5px;"></textarea>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center" style="margin: 30px 0;">
                                <button type="submit" name="save_came" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored">
                                    <i class="zmdi zmdi-save"></i> Guardar Matriz CAME
                                </button>
                                <a href="identificacion_de_estrategias.php" 
                                   class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" 
                                   style="background-color: #6c757d; color: white; margin-left: 10px;">
                                    <i class="zmdi zmdi-arrow-left"></i> Volver
                                </a>
                                <?php if (isset($_SESSION['matriz_came'])): ?>
                                <a href="../index.php?controller=ResumenFinal&action=mostrarResumen" 
                                   class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" 
                                   style="background-color: #28a745; color: white; margin-left: 10px;">
                                    <i class="zmdi zmdi-assignment"></i> Ver Resumen Final
                                </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Panel de datos FODA de referencia -->
                <div class="full-width panel mdl-shadow--2dp" style="margin-top: 20px;">
                    <div class="full-width panel-tittle bg-info text-center tittles">
                        DATOS FODA DE REFERENCIA
                    </div>
                    <div class="full-width panel-content">
                        <div class="mdl-grid">
                            <!-- Fortalezas -->
                            <div class="mdl-cell mdl-cell--6-col">
                                <div style="background-color: #E8F5E8; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                                    <h4 style="color: #2E7D32; margin-bottom: 10px;">
                                        <i class="zmdi zmdi-thumb-up"></i> FORTALEZAS
                                    </h4>
                                    <?php if ($foda_data && !empty($foda_data['fortalezas'])): ?>
                                        <ul style="margin: 0; padding-left: 20px;">
                                            <?php foreach ($foda_data['fortalezas'] as $index => $fortaleza): ?>
                                                <li style="margin-bottom: 5px;"><?= htmlspecialchars($fortaleza) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p style="margin: 0; color: #666;">No hay fortalezas registradas.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Debilidades -->
                            <div class="mdl-cell mdl-cell--6-col">
                                <div style="background-color: #FFF3E0; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                                    <h4 style="color: #F57C00; margin-bottom: 10px;">
                                        <i class="zmdi zmdi-thumb-down"></i> DEBILIDADES
                                    </h4>
                                    <?php if ($foda_data && !empty($foda_data['debilidades'])): ?>
                                        <ul style="margin: 0; padding-left: 20px;">
                                            <?php foreach ($foda_data['debilidades'] as $index => $debilidad): ?>
                                                <li style="margin-bottom: 5px;"><?= htmlspecialchars($debilidad) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p style="margin: 0; color: #666;">No hay debilidades registradas.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Oportunidades -->
                            <div class="mdl-cell mdl-cell--6-col">
                                <div style="background-color: #E3F2FD; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                                    <h4 style="color: #1976D2; margin-bottom: 10px;">
                                        <i class="zmdi zmdi-trending-up"></i> OPORTUNIDADES
                                    </h4>
                                    <?php if ($foda_data && !empty($foda_data['oportunidades'])): ?>
                                        <ul style="margin: 0; padding-left: 20px;">
                                            <?php foreach ($foda_data['oportunidades'] as $index => $oportunidad): ?>
                                                <li style="margin-bottom: 5px;"><?= htmlspecialchars($oportunidad) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p style="margin: 0; color: #666;">No hay oportunidades registradas.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Amenazas -->
                            <div class="mdl-cell mdl-cell--6-col">
                                <div style="background-color: #FFEBEE; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                                    <h4 style="color: #D32F2F; margin-bottom: 10px;">
                                        <i class="zmdi zmdi-trending-down"></i> AMENAZAS
                                    </h4>
                                    <?php if ($foda_data && !empty($foda_data['amenazas'])): ?>
                                        <ul style="margin: 0; padding-left: 20px;">
                                            <?php foreach ($foda_data['amenazas'] as $index => $amenaza): ?>
                                                <li style="margin-bottom: 5px;"><?= htmlspecialchars($amenaza) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p style="margin: 0; color: #666;">No hay amenazas registradas.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="../public/js/material.min.js"></script>
    <script>
        // Auto-resize textareas
        document.addEventListener('DOMContentLoaded', function() {
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            });
        });

        // Validación del formulario
        document.getElementById('cameForm').addEventListener('submit', function(e) {
            const textareas = document.querySelectorAll('textarea');
            let hasContent = false;
            
            textareas.forEach(textarea => {
                if (textarea.value.trim() !== '') {
                    hasContent = true;
                }
            });
            
            if (!hasContent) {
                e.preventDefault();
                alert('Debe llenar al menos una acción antes de guardar la matriz CAME.');
                return false;
            }
        });
    </script>

    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        
        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        
        .came-matrix table {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .came-matrix textarea:focus {
            outline: 2px solid #2196F3;
            outline-offset: 2px;
        }
        
        .came-matrix tr:hover {
            background-color: #f8f9fa;
        }
        
        @media (max-width: 768px) {
            .came-matrix {
                font-size: 12px;
            }
            
            .came-matrix td, .came-matrix th {
                padding: 8px 4px !important;
            }
        }
    </style>
</body>
</html>
