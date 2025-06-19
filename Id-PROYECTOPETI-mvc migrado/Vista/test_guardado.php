<?php
// Script para simular datos de sesión y probar el guardado
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Simulación de Datos de Sesión para Testing</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .info { color: blue; }
    .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
    .code { background: #f5f5f5; padding: 10px; font-family: monospace; }
    button { background: #007cba; color: white; border: none; padding: 10px 20px; margin: 5px; cursor: pointer; }
    button:hover { background: #005a87; }
</style>";

// Función para generar datos de sesión de prueba
function generarDatosPrueba() {
    $_SESSION['user'] = [
        'id_usuario' => 1,
        'id_empresa' => 1
    ];
    
    $_SESSION['plan_temporal'] = [
        'id_usuario' => 1,
        'fecha_inicio' => date('Y-m-d H:i:s'),
        'paso_actual' => 11,
        'nuevo_plan' => [
            'nombre_plan' => 'Plan Estratégico Test',
            'empresa' => 'Empresa Test',
            'descripcion' => 'Plan de prueba para debugging'
        ],
        'vision_mision' => [
            'vision' => 'Ser líderes en nuestro sector',
            'mision' => 'Brindar servicios de calidad'
        ],
        'valores' => [
            ['valor' => 'Honestidad', 'descripcion' => 'Actuar con transparencia'],
            ['valor' => 'Innovación', 'descripcion' => 'Buscar soluciones creativas']
        ],
        'objetivos' => [
            'uen_descripcion' => 'Unidad estratégica principal',
            'objetivos_generales' => [
                'Incrementar ventas en 20%',
                'Mejorar satisfacción del cliente'
            ],
            'objetivos_especificos' => [
                'Implementar nuevo sistema CRM',
                'Capacitar al personal en atención al cliente'
            ]
        ],
        'cadena_valor' => [
            'q1' => 4, 'q2' => 3, 'q3' => 5, 'q4' => 4, 'q5' => 3,
            'q6' => 4, 'q7' => 5, 'q8' => 3, 'q9' => 4, 'q10' => 5,
            'fortalezas' => [
                'Personal capacitado',
                'Tecnología moderna'
            ],
            'debilidades' => [
                'Procesos desactualizados',
                'Falta de marketing digital'
            ]
        ],
        'matriz_bcg' => [
            'num_productos' => 3,
            'num_competidores' => 3,
            'anio_inicio' => 2022,
            'anio_fin' => 2024,
            'productos' => ['Producto A', 'Producto B', 'Producto C'],
            'fortalezas' => ['Calidad superior', 'Precio competitivo'],
            'debilidades' => ['Poca promoción', 'Canal de distribución limitado']
        ],
        'fuerzas_porter' => [
            'poder_negociacion_proveedores' => 'Medio - Tenemos múltiples proveedores',
            'poder_negociacion_compradores' => 'Alto - Clientes sensibles al precio',
            'amenaza_productos_sustitutos' => 'Baja - Pocos sustitutos en el mercado',
            'amenaza_nuevos_competidores' => 'Media - Barreras de entrada moderadas',
            'rivalidad_competidores' => 'Alta - Mercado competitivo',
            'oportunidades' => [
                'Expansión a nuevos mercados',
                'Alianzas estratégicas'
            ],
            'amenazas' => [
                'Entrada de competidores internacionales',
                'Cambios regulatorios'
            ]
        ],
        'pest' => [
            'factor_politico' => 'Estabilidad política favorable para inversión',
            'factor_economico' => 'Crecimiento económico del 3% anual',
            'factor_social' => 'Mayor conciencia ambiental en consumidores',
            'factor_tecnologico' => 'Avances en digitalización e IA',
            'oportunidades' => [
                'Incentivos gubernamentales para tecnología',
                'Crecimiento del e-commerce',
                'Mayor demanda de productos sostenibles'
            ],
            'amenazas' => [
                'Posibles cambios en regulación fiscal',
                'Volatilidad en tipos de cambio',
                'Competencia tecnológica internacional'
            ]
        ],
        'estrategias' => [
            'fortalezas' => [
                'Equipo técnico altamente capacitado',
                'Infraestructura tecnológica robusta',
                'Relaciones sólidas con clientes clave'
            ],
            'debilidades' => [
                'Procesos internos poco optimizados',
                'Limitada presencia en redes sociales',
                'Dependencia de pocos productos estrella'
            ],
            'oportunidades' => [
                'Crecimiento del mercado digital',
                'Nuevas regulaciones favorables',
                'Posibilidad de expansión internacional'
            ],
            'amenazas' => [
                'Entrada de competidores con mayor capital',
                'Cambios tecnológicos disruptivos',
                'Posible recesión económica'
            ],
            'fo_estrategias' => [
                'Usar equipo técnico para aprovechar crecimiento digital',
                'Leveragear relaciones con clientes para expansión internacional'
            ],
            'fa_estrategias' => [
                'Fortalecer infraestructura para resistir competencia',
                'Usar capacitación técnica para adaptarse a cambios tecnológicos'
            ],
            'do_estrategias' => [
                'Optimizar procesos para aprovechar nuevas regulaciones',
                'Mejorar presencia digital para captar mercado digital'
            ],
            'da_estrategias' => [
                'Diversificar productos para reducir dependencia',
                'Automatizar procesos para resistir recesión'
            ]
        ],
        'matriz_came' => [
            'corregir_1' => 'Implementar sistema de gestión de procesos para optimizar operaciones',
            'corregir_2' => 'Desarrollar estrategia de marketing digital y presencia en redes sociales',
            'corregir_3' => 'Diversificar portafolio de productos para reducir dependencia',
            'afrontar_1' => 'Fortalecer capacidades tecnológicas para competir con nuevos entrantes',
            'afrontar_2' => 'Crear fondo de contingencia para períodos de recesión económica',
            'afrontar_3' => 'Establecer alianzas estratégicas para mantener competitividad',
            'mantener_1' => 'Continuar programa de capacitación técnica del personal',
            'mantener_2' => 'Mantener y actualizar infraestructura tecnológica',
            'mantener_3' => 'Fortalecer relaciones con clientes clave mediante programa de fidelización',
            'explotar_1' => 'Expandir servicios digitales aprovechando crecimiento del mercado',
            'explotar_2' => 'Iniciar proceso de expansión internacional con clientes actuales',
            'explotar_3' => 'Desarrollar productos sostenibles aprovechando regulaciones favorables'
        ],
        'resumen_ejecutivo' => [
            'emprendedores_promotores' => 'Equipo directivo con 15 años de experiencia en el sector, conformado por profesionales con MBA y especializaciones técnicas.',
            'identificacion_estrategica' => 'La empresa se posiciona como proveedor tecnológico especializado, con estrategia de diferenciación basada en calidad e innovación.',
            'conclusiones' => 'El plan estratégico identifica oportunidades claras de crecimiento en el mercado digital, requiriendo inversión en optimización de procesos y expansión del portafolio.'
        ]
    ];
}

// Función para limpiar sesión
function limpiarSesion() {
    unset($_SESSION['plan_temporal']);
    unset($_SESSION['user']);
}

// Procesar acciones
if (isset($_GET['accion'])) {
    switch ($_GET['accion']) {
        case 'generar':
            generarDatosPrueba();
            echo "<div class='section'><p class='success'>✓ Datos de sesión de prueba generados exitosamente</p></div>";
            break;
        case 'limpiar':
            limpiarSesion();
            echo "<div class='section'><p class='info'>Sesión limpiada</p></div>";
            break;
        case 'guardar':
            if (isset($_SESSION['plan_temporal'])) {
                try {
                    require_once '../Controllers/PlanEstrategicoController.php';
                    $controller = new PlanEstrategicoController();
                    
                    echo "<div class='section'>";
                    echo "<h2>Resultado del Guardado</h2>";
                    
                    $id_plan = $controller->guardarPlanCompleto();
                    
                    if ($id_plan) {
                        echo "<p class='success'>✓ Plan guardado exitosamente con ID: $id_plan</p>";
                        
                        // Verificar en base de datos
                        $pdo = new PDO("mysql:host=localhost;dbname=plan_estrategico;charset=utf8", "root", "");
                        $stmt = $pdo->prepare("SELECT * FROM tb_plan_estrategico WHERE id_plan = ?");
                        $stmt->execute([$id_plan]);
                        $plan = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($plan) {
                            echo "<p class='success'>✓ Plan verificado en base de datos</p>";
                            echo "<pre class='code'>" . print_r($plan, true) . "</pre>";
                        }
                        
                        // Verificar módulos críticos
                        $tablas_criticas = [
                            'tb_analisis_pest' => 'PEST',
                            'tb_matriz_came' => 'Matriz CAME',
                            'tb_sintesis_estrategias' => 'Síntesis Estratégica'
                        ];
                        
                        foreach ($tablas_criticas as $tabla => $nombre) {
                            $stmt = $pdo->prepare("SELECT * FROM $tabla WHERE id_plan = ? OR id_empresa = ?");
                            $stmt->execute([$id_plan, 1]);
                            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            if ($datos) {
                                echo "<p class='success'>✓ $nombre guardado correctamente</p>";
                            } else {
                                echo "<p class='error'>✗ $nombre NO se guardó</p>";
                            }
                        }
                        
                    } else {
                        echo "<p class='error'>✗ Error: No se devolvió ID de plan</p>";
                    }
                    echo "</div>";
                    
                } catch (Exception $e) {
                    echo "<div class='section'>";
                    echo "<p class='error'>✗ Exception: " . $e->getMessage() . "</p>";
                    echo "<pre class='code'>" . $e->getTraceAsString() . "</pre>";
                    echo "</div>";
                }
            } else {
                echo "<div class='section'><p class='error'>✗ No hay datos de sesión para guardar</p></div>";
            }
            break;
    }
}

// Mostrar estado actual
echo "<div class='section'>";
echo "<h2>Estado Actual de la Sesión</h2>";

if (isset($_SESSION['plan_temporal'])) {
    echo "<p class='success'>✓ Sesión del plan existe</p>";
    
    $modulos = ['nuevo_plan', 'vision_mision', 'valores', 'objetivos', 'cadena_valor', 'matriz_bcg', 'fuerzas_porter', 'pest', 'estrategias', 'matriz_came', 'resumen_ejecutivo'];
    
    echo "<h3>Módulos disponibles:</h3><ul>";
    foreach ($modulos as $modulo) {
        if (isset($_SESSION['plan_temporal'][$modulo])) {
            echo "<li class='success'>✓ $modulo</li>";
        } else {
            echo "<li class='error'>✗ $modulo</li>";
        }
    }
    echo "</ul>";
    
} else {
    echo "<p class='error'>✗ No hay sesión de plan</p>";
}

if (isset($_SESSION['user'])) {
    echo "<p class='success'>✓ Usuario autenticado: ID " . $_SESSION['user']['id_usuario'] . "</p>";
} else {
    echo "<p class='error'>✗ No hay usuario autenticado</p>";
}

echo "</div>";

// Controles
echo "<div class='section'>";
echo "<h2>Controles de Testing</h2>";
echo "<button onclick=\"location.href='?accion=generar'\">Generar Datos de Prueba</button>";
echo "<button onclick=\"location.href='?accion=guardar'\">Probar Guardado</button>";
echo "<button onclick=\"location.href='?accion=limpiar'\">Limpiar Sesión</button>";
echo "<button onclick=\"location.href='debug_guardado_completo.php'\">Ver Debug Completo</button>";
echo "</div>";

?>
