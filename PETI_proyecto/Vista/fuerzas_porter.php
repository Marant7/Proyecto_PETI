<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Simulación para desarrollo si no hay sesión
    $_SESSION['user_id'] = 1;
    $_SESSION['usuario'] = "Usuario de Prueba";
}

// Estructura de datos con todo el texto de la imagen
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

// ------ DESCRIPCIÓN CONCISA PARA EL USUARIO ------
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ingresar Visión y Diseño</title>
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
  <script src="../public/js/visiMisi.js"></script> <!-- Agregar el archivo JS -->
  <style>
    .descripcion-pagina {
        margin-bottom: 20px; padding: 15px; background-color: #f9f9f9;
        border-left: 5px solid #007bff; color: #333;
        line-height: 1.6; font-size: 0.95em;
        border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .descripcion-pagina p { margin-bottom: 8px; }
    .descripcion-pagina strong { font-weight: 600; }

    .tabla-perfil { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size:0.9em; table-layout: fixed; }
    .tabla-perfil th, .tabla-perfil td { border: 1px solid #ddd; padding: 8px 6px; text-align: center; vertical-align: middle; }
    .tabla-perfil th.header-perfil { background-color: #e0e0e0; font-weight: bold; width: 30%;}
    .tabla-perfil th.header-escala { background-color: #f5f5f5; width: 9%;}
    .tabla-perfil th.header-estado { background-color: #e0e0e0; font-weight: bold; width: 20%;}
    .tabla-perfil td.factor-nombre-celda { text-align: left; background-color: #f7f7f7; padding-left: 10px; }
    .tabla-perfil td.factor-nombre-celda .descriptor-contexto {
        display: block; font-size: 0.85em; color: #757575; margin-top: 3px;
    }
    .tabla-perfil td.factor-nombre-celda .descriptor-contexto .texto-hostil { color: #d32f2f; }
    .tabla-perfil td.factor-nombre-celda .descriptor-contexto .texto-favorable { color: #388e3c; }
    .tabla-perfil td.celda-radio { }
    .tabla-perfil td.celda-estado { font-weight: bold; }
    .tabla-perfil td.celda-estado.estado-hostil { color: #d32f2f; }
    .tabla-perfil td.celda-estado.estado-favorable { color: #388e3c; }
    .fila-titulo-seccion td { background-color: #dcedc8; font-weight: bold; text-align: left; padding-left: 15px; font-size: 1.1em;}
    .conclusion-area { margin-top: 20px; }
    .conclusion-area .titulo-conclusion { background-color: #e0e0e0; font-weight: bold; padding: 8px; text-align:left; }
    .conclusion-area .texto-resultado-conclusion { border: 1px solid #ddd; padding: 10px; min-height: 50px; background-color: #fff; text-align:left;}
    .conclusion-area .total-general { font-weight: bold; font-size: 1.2em; text-align: center; padding: 8px; border: 1px solid #ddd; background-color: #f5f5f5;}
    .conclusion-area .etiqueta-total { font-weight:normal; }
    input[type="radio"] { margin: 0; transform: scale(1.3); cursor:pointer; }
  </style>
</head>
<body>
 <div class="full-width navBar">
    <div class="full-width navBar-options">
        <i class="zmdi zmdi-more-vert btn-menu" id="btn-menu"></i>
        <div class="mdl-tooltip" for="btn-menu">Menu</div>
        <nav class="navBar-options-list">
            <ul class="list-unstyle">
                <li class="btn-Notification" id="notifications"><i class="zmdi zmdi-notifications"></i><div class="mdl-tooltip" for="notifications">Notifications</div></li>
                <li class="btn-exit" id="btn-exit"><i class="zmdi zmdi-power"></i><div class="mdl-tooltip" for="btn-exit">LogOut</div></li>
                <li class="text-condensedLight noLink"><small><?php echo htmlspecialchars($_SESSION['usuario']); ?></small></li>
                <li class="noLink"><figure><img src="../public/assets/img/avatar-male.png" alt="Avatar" class="img-responsive"></figure></li>
            </ul>
        </nav>
    </div>
</div>

<div class="main-layout">
    <div class="navLateral">
        <?php include 'sidebar.php'; // Asegúrate que la ruta sea correcta ?>
    </div>
    <div class="pageContent">
        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">

            <div class="mdl-tabs__panel is-active" id="tabAnalisisConciso">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col">
                        <div class="full-width panel mdl-shadow--2dp">
                            <div class="full-width panel-content">
                                <div class="descripcion-pagina">
                                    <?php echo $descripcion_inicial; ?>
                                </div>

                                <form id="perfilCompetitivoForm" method="POST" action="guardar_perfil_competitivo.php">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">

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
                                            $factor_global_index = 0;
                                            foreach ($perfil_competitivo_data['secciones'] as $seccion):
                                            ?>
                                                <tr class="fila-titulo-seccion">
                                                    <td colspan="<?php echo count($perfil_competitivo_data['columnas_escala']) + 2; ?>">
                                                        <?php echo htmlspecialchars($seccion['titulo_seccion']); ?>
                                                    </td>
                                                </tr>
                                                <?php foreach ($seccion['factores'] as $factor_info): ?>
                                                    <?php $radio_name = "factor_" . $factor_global_index; ?>
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
                                                                       class="factor-radio-selector">
                                                            </td>
                                                        <?php endfor; ?>
                                                        <td class="celda-estado" id="estado-<?php echo $radio_name; ?>">
                                                            -
                                                        </td>
                                                    </tr>
                                                <?php
                                                    $factor_global_index++;
                                                endforeach; // Fin factores
                                            endforeach; // Fin secciones
                                            ?>
                                        </tbody>
                                    </table>

                                    <div class="conclusion-area mdl-grid">
                                        <div class="mdl-cell mdl-cell--2-col titulo-conclusion" style="text-align: left; vertical-align: middle; display: flex; align-items: center;">
                                            <?php echo htmlspecialchars($perfil_competitivo_data['conclusion_info']['titulo']); ?>
                                        </div>
                                        <div class="mdl-cell mdl-cell--8-col texto-resultado-conclusion" id="texto-final-conclusion" style="vertical-align: middle;">
                                            Seleccione todas las opciones para ver la conclusión.
                                        </div>
                                        <div class="mdl-cell mdl-cell--2-col total-general" style="vertical-align: middle; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                            <span class="etiqueta-total" style="display:block;"><?php echo htmlspecialchars($perfil_competitivo_data['conclusion_info']['etiqueta_total']); ?></span>
                                            <span id="puntaje-total-final" style="display:block;">0</span>
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

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../public/js/jquery-1.11.2.min.js"><\/script>')</script>
<script src="../public/js/material.min.js"></script>
<script src="../public/js/sweetalert2.min.js"></script>
<script src="../public/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="../public/js/main.js"></script>
<script>
$(document).ready(function() {
    const textosConclusionJS = <?php echo json_encode($textos_conclusion_mapeo); ?>;
    const totalFactoresRequeridos = <?php echo $factor_global_index; ?>;

    function actualizarEstadoFila(radioInput) {
        const valorSeleccionado = parseInt($(radioInput).val(), 10);
        const filaFactor = $(radioInput).closest('tr');
        const radioName = $(radioInput).attr('name');
        const celdaEstado = $('#estado-' + radioName);

        const descriptorHostilEspecifico = filaFactor.data('descriptor-hostil');
        const descriptorFavorableEspecifico = filaFactor.data('descriptor-favorable');

        celdaEstado.removeClass('estado-hostil estado-favorable');
        let textoEstado = '-';

        if (valorSeleccionado === 1 || valorSeleccionado === 2) {
            textoEstado = "HOSTIL: " + descriptorHostilEspecifico;
            celdaEstado.addClass('estado-hostil');
        } else if (valorSeleccionado >= 3 && valorSeleccionado <= 5) {
            textoEstado = "FAVORABLE: " + descriptorFavorableEspecifico;
            celdaEstado.addClass('estado-favorable');
        }
        
        celdaEstado.text(textoEstado);
    }

    function actualizarCalculosGlobales() {
        let puntajeTotal = 0;
        let factoresSeleccionados = 0;
        $('input.factor-radio-selector:checked').each(function() {
            puntajeTotal += parseInt($(this).val(), 10);
            factoresSeleccionados++;
        });
        $('#puntaje-total-final').text(puntajeTotal);

        let conclusionFinalTexto = "Por favor, complete todas las selecciones para generar la conclusión.";
        if (factoresSeleccionados === totalFactoresRequeridos) {
            if (puntajeTotal < 30) {
                conclusionFinalTexto = textosConclusionJS["clave_B38"];
            } else if (puntajeTotal < 45) {
                conclusionFinalTexto = textosConclusionJS["clave_B39"];
            } else if (puntajeTotal < 60) {
                conclusionFinalTexto = textosConclusionJS["clave_B40"];
            } else {
                conclusionFinalTexto = textosConclusionJS["clave_B41"];
            }
        } else if (factoresSeleccionados > 0) {
            conclusionFinalTexto = "Continúe seleccionando todas las opciones...";
        }
        $('#texto-final-conclusion').text(conclusionFinalTexto);
    }

    $('input.factor-radio-selector').on('change', function() {
        actualizarEstadoFila(this);
        actualizarCalculosGlobales();
    });

    $('#perfilCompetitivoForm').on('submit', function(e) {
        const seleccionesHechas = $('input.factor-radio-selector:checked').length;
        if (seleccionesHechas < totalFactoresRequeridos) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Formulario Incompleto',
                text: 'Debe seleccionar una opción para cada factor antes de guardar.',
                confirmButtonText: 'Entendido'
            });
        }
    });
    actualizarCalculosGlobales();
});
</script>
</body>
</html>