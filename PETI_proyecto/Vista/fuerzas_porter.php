<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "No hay usuario en sesión";
    exit();
}

$perfil_competitivo_data = [
    "titulo_general" => "PERFIL COMPETITIVO",
    "columnas_escala" => ["Nada", "Poco", "Medio", "Alto", "Muy Alto"],
    "secciones" => [
        [
            "titulo_seccion" => "Rivalidad empresas del sector",
            "factores" => [
                ["nombre" => "- Crecimiento",                         "hostil" => "Lento",   "favorable" => "Rápido"],
                ["nombre" => "- Naturaleza de los competidores",      "hostil" => "Muchos",  "favorable" => "Pocos"],
                ["nombre" => "- Exceso de capacidad productiva",      "hostil" => "Sí",      "favorable" => "No"],
                ["nombre" => "- Rentabilidad media del sector",       "hostil" => "Baja",    "favorable" => "Alta"],
                ["nombre" => "- Diferenciación del producto",         "hostil" => "Escasa",  "favorable" => "Elevada"],
                ["nombre" => "- Barreras de salida",                  "hostil" => "Bajas",   "favorable" => "Altas"],
            ]
        ],
        [
            "titulo_seccion" => "Barreras de Entrada",
            "factores" => [
                ["nombre" => "- Economías de escala",                 "hostil" => "No",      "favorable" => "Sí"],
                ["nombre" => "- Necesidad de capital",                "hostil" => "Bajas",   "favorable" => "Altas"],
                ["nombre" => "- Acceso a la tecnología",              "hostil" => "Fácil",   "favorable" => "Difícil"],
                ["nombre" => "- Reglamentos o leyes limitativas",     "hostil" => "No",      "favorable" => "Sí"],
                ["nombre" => "- Trámites burocráticos",               "hostil" => "No",      "favorable" => "Sí"],
                ["nombre" => "- Reacción esperada actuales competidores", "hostil" => "Escasa",  "favorable" => "Enérgica"],
            ]
        ],
        [
            "titulo_seccion" => "Poder de los Clientes",
            "factores" => [
                ["nombre" => "- Número de clientes",                    "hostil" => "Pocos",   "favorable" => "Muchos"],
                ["nombre" => "- Posibilidad de integración ascendente", "hostil" => "Pequeña", "favorable" => "Grande"],
                ["nombre" => "- Rentabilidad de los clientes",          "hostil" => "Baja",    "favorable" => "Alta"],
                ["nombre" => "- Coste de cambio de proveedor para cliente", "hostil" => "Bajo",    "favorable" => "Alto"],
            ]
        ],
        [
            "titulo_seccion" => "Productos sustitutivos",
            "factores" => [
                ["nombre" => "- Disponibilidad de Productos Sustitutivos", "hostil" => "Grande",  "favorable" => "Pequeña"],
            ]
        ]
    ],
    "conclusion_info" => [
        "titulo" => "CONCLUSIÓN",
        "etiqueta_total" => "Total"
    ]
];

$textos_conclusion_mapeo = [
    "clave_B38" => "Estamos en un mercado altamente competitivo, en el que es muy difícil hacerse un hueco en el mercado.",
    "clave_B39" => "Estamos en un mercado de competitividad relativamente alta, pero con ciertas modificaciones en el producto y la política comercial de la empresa, podría encontrarse un nicho de mercado.",
    "clave_B40" => "La situación actual del mercado es favorable a la empresa.",
    "clave_B41" => "Estamos en una situación excelente para la empresa."
];

$descripcion_inicial = "
    <p><strong>Evalúe el Perfil Competitivo de su Empresa:</strong></p>
    <p style='text-align: left;'>
        Para cada factor listado, seleccione la opción (de \"Nada\" a \"Muy Alto\") que mejor describa su situación actual. 
        Debajo de cada factor, verá una guía del contexto <strong style='color:#d32f2f;'>Hostil</strong> (ej. Lento) y <strong style='color:#388e3c;'>Favorable</strong> (ej. Rápido) específico para ese ítem.
    </p>
    <p style='text-align: left;'>
        Al marcar una opción, la columna \"<strong>Estado Resultante</strong>\" indicará si su elección es <strong style='color:#d32f2f;'>HOSTIL</strong> o <strong style='color:#388e3c;'>FAVORABLE</strong> para ese factor, mostrando el descriptor correspondiente (ej. <strong style='color:#d32f2f;'>HOSTIL: Lento</strong>).
    </p>
    <p style='text-align: left;'>
        Complete todos los factores para ver el <strong>Puntaje Total</strong> y la <strong>Conclusión General</strong> sobre su perfil competitivo.
    </p>
";

require_once __DIR__ . '/../Models/PorterModel.php';
$porterModel = new PorterModel();
$datos_guardados_fp = null; 
$id_empresa_para_fp = null;  

if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../config/clsconexion.php'; 
    $temp_conexion_obj = new clsConexion();
    $temp_conexion = $temp_conexion_obj->getConexion();
    $stmt_emp = $temp_conexion->prepare("SELECT id_empresa FROM tb_empresa WHERE id_usuario = ? LIMIT 1");
    if ($stmt_emp) {
        $stmt_emp->bind_param("i", $_SESSION['user_id']);
        $stmt_emp->execute();
        $resultado_emp = $stmt_emp->get_result();
        if ($fila_emp = $resultado_emp->fetch_assoc()) {
            $id_empresa_para_fp = $fila_emp['id_empresa'];
        }
        $stmt_emp->close();
    } else {
        error_log("Vista Perfil Competitivo: Error al preparar consulta de empresa: " . $temp_conexion->error);
    }
}

if ($id_empresa_para_fp !== null) {
    $datos_guardados_fp = $porterModel->obtenerAnalisisPorEmpresa($id_empresa_para_fp);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Fuerzas Porter</title>
  <link rel="stylesheet" href="../public/css/fuerzas_porter.css">
  <link rel="stylesheet" href="../public/css/normalize.css">
  <link rel="stylesheet" href="../public/css/sweetalert2.css">
  <link rel="stylesheet" href="../public/css/material.min.css">
  <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
  <link rel="stylesheet" href="../public/css/jquery.mCustomScrollbar.css">
  <link rel="stylesheet" href="../public/css/main.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="../public/js/jquery-1.11.2.min.js"><\/script>')</script>
  <script src="../public/js/material.min.js"></script>
  <script src="../public/js/sweetalert2.min.js"></script>
  <script src="../public/js/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="../public/js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

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
                        <small><?php echo $_SESSION['usuario']; ?></small>
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

                    <div class="mdl-tabs__panel is-active" id="tabAnalisisConciso">
                        <div class="mdl-grid">
                            <div class="mdl-cell mdl-cell--12-col">
                                <div class="full-width panel mdl-shadow--2dp">
                                    <div class="full-width panel-content">
                                        <div class="descripcion-pagina">
                                            <?php echo $descripcion_inicial; ?>
                                        </div>

                                        <form id="perfilCompetitivoForm" method="POST" action="../Controllers/fuerzasPorterController.php">
                                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                                            <?php if ($id_empresa_para_fp): ?>
                                                <!-- <input type="hidden" name="id_empresa_seleccionada" value="<?php echo htmlspecialchars($id_empresa_para_fp); ?>"> -->
                                            <?php endif; ?>

                                            <table class="tabla-perfil">
                                                <thead>
                                                    <tr>
                                                        <th class="header-perfil"><?php echo htmlspecialchars($perfil_competitivo_data['titulo_general']); ?></th>
                                                        <?php foreach($perfil_competitivo_data['columnas_escala'] as $escala_item): ?>
                                                            <th class="header-escala"><?php echo htmlspecialchars($escala_item); ?></th>
                                                        <?php endforeach; ?>
                                                        <th class="header-estado">Estado Resultante</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $factor_global_index_vista = 0; 
                                                    foreach ($perfil_competitivo_data['secciones'] as $seccion):
                                                    ?>
                                                        <tr class="fila-titulo-seccion">
                                                            <td colspan="<?php echo count($perfil_competitivo_data['columnas_escala']) + 2; ?>">
                                                                <?php echo htmlspecialchars($seccion['titulo_seccion']); ?>
                                                            </td>
                                                        </tr>
                                                        <?php foreach ($seccion['factores'] as $factor_info): ?>
                                                            <?php
                                                            $radio_name = "factor_" . $factor_global_index_vista;
                                                            $nombre_columna_db = "q" . $factor_global_index_vista;
                                                            $valor_guardado_actual = null;
                                                            if ($datos_guardados_fp && isset($datos_guardados_fp[$nombre_columna_db])) {
                                                                $valor_guardado_actual = intval($datos_guardados_fp[$nombre_columna_db]);
                                                            }
                                                            ?>
                                                            <tr data-descriptor-hostil="<?php echo htmlspecialchars($factor_info['hostil']); ?>"
                                                                data-descriptor-favorable="<?php echo htmlspecialchars($factor_info['favorable']); ?>">
                                                                <td class="factor-nombre-celda">
                                                                    <?php echo htmlspecialchars($factor_info['nombre']); ?>
                                                                    <span class="descriptor-contexto">
                                                                        (<span class="texto-hostil">Hostil: <?php echo htmlspecialchars($factor_info['hostil']); ?></span> / 
                                                                        <span class="texto-favorable">Favorable: <?php echo htmlspecialchars($factor_info['favorable']); ?></span>)
                                                                    </span>
                                                                </td>
                                                                <?php for ($i = 1; $i <= count($perfil_competitivo_data['columnas_escala']); $i++): ?>
                                                                    <td class="celda-radio">
                                                                        <input type="radio"
                                                                            name="<?php echo $radio_name; ?>"
                                                                            value="<?php echo $i; ?>"
                                                                            required
                                                                            class="factor-radio-selector"
                                                                            <?php if ($valor_guardado_actual === $i) { echo 'checked'; } ?>
                                                                        >
                                                                    </td>
                                                                <?php endfor; ?>
                                                                <td class="celda-estado" id="estado-<?php echo $radio_name; ?>">
                                                                </td>
                                                            </tr>
                                                        <?php
                                                            $factor_global_index_vista++;
                                                        endforeach; 
                                                    endforeach; 
                                                    $total_factores_para_js = $factor_global_index_vista;
                                                    ?>
                                                </tbody>
                                            </table>

                                            <div class="conclusion-area mdl-grid">
                                                <div class="mdl-cell mdl-cell--2-col titulo-conclusion" style="text-align: left; vertical-align: middle; display: flex; align-items: center;">
                                                    <?php echo htmlspecialchars($perfil_competitivo_data['conclusion_info']['titulo']); ?>
                                                </div>
                                                <div class="mdl-cell mdl-cell--8-col texto-resultado-conclusion" id="texto-final-conclusion" style="vertical-align: middle;">
                                                    <?php 
                                                    if ($datos_guardados_fp && isset($datos_guardados_fp['texto_conclusion_generada']) && !empty($datos_guardados_fp['texto_conclusion_generada'])) {
                                                        echo htmlspecialchars($datos_guardados_fp['texto_conclusion_generada']);
                                                    } else {
                                                        echo "Seleccione todas las opciones para ver la conclusión.";
                                                    }
                                                    ?>
                                                </div>
                                                <div class="mdl-cell mdl-cell--2-col total-general" style="vertical-align: middle; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                                    <span class="etiqueta-total" style="display:block;"><?php echo htmlspecialchars($perfil_competitivo_data['conclusion_info']['etiqueta_total']); ?></span>
                                                    <span id="puntaje-total-final" style="display:block;">
                                                        <?php 
                                                        if ($datos_guardados_fp && isset($datos_guardados_fp['puntaje_total'])) {
                                                            echo htmlspecialchars($datos_guardados_fp['puntaje_total']);
                                                        } else {
                                                            echo "0";
                                                        }
                                                        ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div style="text-align: center; margin-top: 30px; margin-bottom: 20px;">
                                                <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect">
                                                    Guardar Análisis
                                                </button>
                                            </div>
                                        </form>
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

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../public/js/jquery-1.11.2.min.js"><\/script>')</script>
<script src="../public/js/material.min.js"></script>
<script src="../public/js/sweetalert2.min.js"></script>
<script src="../public/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="../public/js/main.js"></script>

<script>
    window.datosDesdePHP = {
        textosConclusion: <?php echo json_encode($textos_conclusion_mapeo); ?>,
        totalFactores: <?php echo $total_factores_para_js; ?>
    };
</script>
<script src="../public/js/fuerzas_porter.js"></script> 

</body>
</html>
