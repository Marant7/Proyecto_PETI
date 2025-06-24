<?php
/**
 * Exportador de PDF para el Resumen Ejecutivo del PETI
 * Utiliza TCPDF para generar PDFs profesionales
 */

require_once __DIR__ . '/../vendor/TCPDF-main/tcpdf.php';
require_once __DIR__ . '/../config/clsconexion.php';
require_once __DIR__ . '/../Models/PlanModel.php';

class ResumenEjecutivoPDFExporter extends TCPDF {
    
    private $plan_id;
    private $datos_plan;
    private $model;
    
    public function __construct($plan_id) {
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false);
        
        $this->plan_id = $plan_id;
        
        // Configurar modelo
        $db = (new clsConexion())->getConexion();
        $this->model = new PlanModel($db);
        
        // Configurar PDF
        $this->configurarPDF();
        
        // Cargar datos
        $this->cargarDatos();
    }
    
    private function configurarPDF() {
        // Información del documento
        $this->SetCreator('Sistema PETI');
        $this->SetAuthor('Plan Estratégico de TI');
        $this->SetTitle('Resumen Ejecutivo - Plan Estratégico de TI');
        $this->SetSubject('Resumen Ejecutivo');
        $this->SetKeywords('PETI, Plan Estratégico, TI, Resumen Ejecutivo');
          // Configurar márgenes
        $this->SetMargins(20, 35, 20);
        $this->SetHeaderMargin(10);
        $this->SetFooterMargin(15);
        
        // Configurar salto de página automático
        $this->SetAutoPageBreak(TRUE, 25);
        
        // Configurar fuente
        $this->SetFont('helvetica', '', 10);
    }
    
    private function cargarDatos() {
        // Obtener todos los datos del plan
        $this->datos_plan = [
            'informacion_empresa' => $this->model->obtenerInformacionEmpresa($this->plan_id),
            'fecha_elaboracion' => $this->model->obtenerFechaElaboracion($this->plan_id),
            'vision_mision' => $this->model->obtenerVisionMision($this->plan_id),
            'valores' => $this->model->obtenerValores($this->plan_id),
            'objetivos' => $this->model->obtenerObjetivos($this->plan_id),
            'cadena_valor' => $this->model->obtenerCadenaValor($this->plan_id),
            'bcg' => $this->model->obtenerMatrizBCG($this->plan_id),
            'fuerzas_porter' => $this->model->obtenerFuerzasPorter($this->plan_id),
            'pest' => $this->model->obtenerPEST($this->plan_id),
            'estrategias' => $this->model->obtenerEstrategias($this->plan_id),
            'matriz_came' => $this->model->obtenerMatrizCame($this->plan_id),
            'resumen_ejecutivo' => $this->model->obtenerResumenEjecutivo($this->plan_id)
        ];
    }
      // Header personalizado
    public function Header() {
        // Logo o título
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor(51, 122, 183); // Azul
        $this->Cell(0, 15, 'PLAN ESTRATÉGICO DE TECNOLOGÍAS DE INFORMACIÓN', 0, 1, 'C');
        
        // Espacio adicional entre títulos
        $this->Ln(2);
        
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(108, 117, 125); // Gris
        $this->Cell(0, 10, 'RESUMEN EJECUTIVO', 0, 1, 'C');
        
        // Línea separadora
        $this->SetDrawColor(51, 122, 183);
        $this->SetLineWidth(0.5);
        $this->Line(20, 40, 190, 40);
        
        $this->Ln(5);
    }
    
    // Footer personalizado
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        
        // Información de la empresa
        $nombre_empresa = $this->datos_plan['informacion_empresa']['nombre_empresa'] ?? 'Mi Empresa';
        $this->Cell(0, 10, $nombre_empresa . ' - Generado el ' . date('d/m/Y H:i'), 0, 0, 'L');
        
        // Número de página
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'R');
    }
    
    public function generarPDF() {
        // Agregar primera página
        $this->AddPage();
        
        // Generar contenido
        $this->generarInformacionGeneral();
        $this->generarEmprendedoresPromotores();
        $this->generarVisionMisionValores();
        $this->generarUnidadesEstrategicas();
        $this->generarObjetivosEstrategicos();
        $this->generarAnalisisFODA();
        $this->generarIdentificacionEstrategica();
        $this->generarAccionesCompetitivas();
        $this->generarConclusiones();
        
        return $this;
    }
    
    private function generarInformacionGeneral() {
        $this->agregarSeccion('Información General', '#4CAF50');
        
        $nombre_empresa = $this->datos_plan['informacion_empresa']['nombre_empresa'] ?? 'Mi Empresa';
        $fecha_elaboracion = $this->datos_plan['fecha_elaboracion'] ?? date('d/m/Y');
        
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(50, 8, 'Nombre de la Empresa:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 11);
        $this->Cell(0, 8, $nombre_empresa, 0, 1, 'L');
        
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(50, 8, 'Fecha de Elaboración:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 11);
        $this->Cell(0, 8, $fecha_elaboracion, 0, 1, 'L');
        
        $this->Ln(5);
    }
    
    private function generarEmprendedoresPromotores() {
        $resumen = $this->datos_plan['resumen_ejecutivo'];
        $emprendedores = $resumen['emprendedores_promotores'] ?? '';
        
        if (!empty($emprendedores)) {
            $this->agregarSeccion('Emprendedores / Promotores', '#FF9800');
            $this->SetFont('helvetica', '', 10);
            $this->MultiCell(0, 6, $emprendedores, 0, 'J');
            $this->Ln(5);
        }
    }
    
    private function generarVisionMisionValores() {
        $this->agregarSeccion('Misión, Visión y Valores', '#4CAF50');
        
        $vision_mision = $this->datos_plan['vision_mision'];
        $valores = $this->datos_plan['valores'];
        
        // Misión
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 8, 'Misión:', 0, 1, 'L');
        $this->SetFont('helvetica', '', 10);
        $mision = $vision_mision['mision'] ?? 'No definida';
        $this->MultiCell(0, 6, $mision, 0, 'J');
        $this->Ln(3);
        
        // Visión
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 8, 'Visión:', 0, 1, 'L');
        $this->SetFont('helvetica', '', 10);
        $vision = $vision_mision['vision'] ?? 'No definida';
        $this->MultiCell(0, 6, $vision, 0, 'J');
        $this->Ln(3);
        
        // Valores
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 8, 'Valores:', 0, 1, 'L');
        $this->SetFont('helvetica', '', 10);
        
        if (!empty($valores)) {
            foreach ($valores as $valor) {
                $this->Cell(5, 6, '•', 0, 0, 'L');
                $this->MultiCell(0, 6, $valor, 0, 'J');
            }
        } else {
            $this->Cell(0, 6, 'No se han definido valores', 0, 1, 'L');
        }
        
        $this->Ln(5);
    }
    
    private function generarUnidadesEstrategicas() {
        $objetivos = $this->datos_plan['objetivos'];
        $uen_descripcion = $objetivos['uen_descripcion'] ?? 'No especificada';
        
        $this->agregarSeccion('Unidades Estratégicas', '#4CAF50');
        $this->SetFont('helvetica', '', 10);
        $this->MultiCell(0, 6, $uen_descripcion, 0, 'J');
        $this->Ln(5);
    }
    
    private function generarObjetivosEstrategicos() {
        $this->agregarSeccion('Objetivos Estratégicos', '#4CAF50');
        
        $objetivos = $this->datos_plan['objetivos'];
        
        // Objetivos Generales
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 8, 'Objetivos Generales:', 0, 1, 'L');
        $this->SetFont('helvetica', '', 10);
        
        $objetivos_generales = $objetivos['objetivos_generales'] ?? [];
        if (is_string($objetivos_generales)) {
            $objetivos_generales = [$objetivos_generales];
        }
        
        if (!empty($objetivos_generales)) {
            foreach ($objetivos_generales as $objetivo) {
                if (!empty(trim($objetivo))) {
                    $this->Cell(5, 6, '•', 0, 0, 'L');
                    $this->MultiCell(0, 6, trim($objetivo), 0, 'J');
                }
            }
        } else {
            $this->Cell(0, 6, 'No se han definido objetivos generales', 0, 1, 'L');
        }
        
        $this->Ln(3);
        
        // Objetivos Específicos
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 8, 'Objetivos Específicos:', 0, 1, 'L');
        $this->SetFont('helvetica', '', 10);
        
        $objetivos_especificos = $objetivos['objetivos_especificos'] ?? [];
        $objetivos_esp_procesados = [];
        
        if (is_array($objetivos_especificos)) {
            foreach ($objetivos_especificos as $grupo) {
                if (is_array($grupo)) {
                    $objetivos_esp_procesados = array_merge($objetivos_esp_procesados, array_filter($grupo));
                } elseif (is_string($grupo) && !empty(trim($grupo))) {
                    $objetivos_esp_procesados[] = trim($grupo);
                }
            }
        }
        
        if (!empty($objetivos_esp_procesados)) {
            foreach ($objetivos_esp_procesados as $objetivo) {
                if (!empty(trim($objetivo))) {
                    $this->Cell(5, 6, '•', 0, 0, 'L');
                    $this->MultiCell(0, 6, trim($objetivo), 0, 'J');
                }
            }
        } else {
            $this->Cell(0, 6, 'No se han definido objetivos específicos', 0, 1, 'L');
        }
        
        $this->Ln(5);
    }
    
    private function generarAnalisisFODA() {
        $this->agregarSeccion('Análisis FODA', '#4CAF50');
        
        // Consolidar FODA desde todas las fuentes (similar al resumen ejecutivo)
        $foda = $this->consolidarFODA();
        
        // Fortalezas
        $this->SetFont('helvetica', 'B', 11);
        $this->SetTextColor(76, 175, 80); // Verde
        $this->Cell(0, 8, 'Fortalezas:', 0, 1, 'L');
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 10);
        $this->generarListaItems($foda['fortalezas']);
        
        // Debilidades
        $this->SetFont('helvetica', 'B', 11);
        $this->SetTextColor(255, 152, 0); // Naranja
        $this->Cell(0, 8, 'Debilidades:', 0, 1, 'L');
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 10);
        $this->generarListaItems($foda['debilidades']);
        
        // Oportunidades
        $this->SetFont('helvetica', 'B', 11);
        $this->SetTextColor(33, 150, 243); // Azul
        $this->Cell(0, 8, 'Oportunidades:', 0, 1, 'L');
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 10);
        $this->generarListaItems($foda['oportunidades']);
        
        // Amenazas
        $this->SetFont('helvetica', 'B', 11);
        $this->SetTextColor(244, 67, 54); // Rojo
        $this->Cell(0, 8, 'Amenazas:', 0, 1, 'L');
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 10);
        $this->generarListaItems($foda['amenazas']);
        
        $this->Ln(5);
    }
    
    private function consolidarFODA() {
        $fortalezas = [];
        $debilidades = [];
        $oportunidades = [];
        $amenazas = [];
        
        // Consolidar desde todas las fuentes (similar al PHP del resumen ejecutivo)
        $datos_estrategias = $this->datos_plan['estrategias'];
        $datos_cadena_valor = $this->datos_plan['cadena_valor'];
        $datos_bcg = $this->datos_plan['bcg'] ? json_decode($this->datos_plan['bcg'], true) : [];
        $datos_pest = $this->datos_plan['pest'];
        $datos_fuerzas_porter = $this->datos_plan['fuerzas_porter'];
        
        // Desde estrategias
        if ($datos_estrategias && isset($datos_estrategias['foda'])) {
            $foda_data = $datos_estrategias['foda'];
            $fortalezas = array_merge($fortalezas, $foda_data['fortalezas'] ?? []);
            $debilidades = array_merge($debilidades, $foda_data['debilidades'] ?? []);
            $oportunidades = array_merge($oportunidades, $foda_data['oportunidades'] ?? []);
            $amenazas = array_merge($amenazas, $foda_data['amenazas'] ?? []);
        }
        
        // Desde cadena de valor
        if ($datos_cadena_valor) {
            if (!empty($datos_cadena_valor['fortalezas'])) {
                $fortalezas = array_merge($fortalezas, $datos_cadena_valor['fortalezas']);
            }
            if (!empty($datos_cadena_valor['debilidades'])) {
                $debilidades = array_merge($debilidades, $datos_cadena_valor['debilidades']);
            }
        }
        
        // Desde BCG
        if ($datos_bcg) {
            if (!empty($datos_bcg['fortalezas'])) {
                $bcg_fortalezas = is_array($datos_bcg['fortalezas']) 
                    ? $datos_bcg['fortalezas'] 
                    : array_filter(explode("\n", $datos_bcg['fortalezas']));
                $fortalezas = array_merge($fortalezas, $bcg_fortalezas);
            }
            if (!empty($datos_bcg['debilidades'])) {
                $bcg_debilidades = is_array($datos_bcg['debilidades']) 
                    ? $datos_bcg['debilidades'] 
                    : array_filter(explode("\n", $datos_bcg['debilidades']));
                $debilidades = array_merge($debilidades, $bcg_debilidades);
            }
        }
        
        // Desde PEST
        if ($datos_pest) {
            if (!empty($datos_pest['oportunidades'])) {
                $oportunidades = array_merge($oportunidades, $datos_pest['oportunidades']);
            }
            if (!empty($datos_pest['amenazas'])) {
                $amenazas = array_merge($amenazas, $datos_pest['amenazas']);
            }
        }
        
        // Desde Fuerzas Porter
        if ($datos_fuerzas_porter) {
            if (!empty($datos_fuerzas_porter['oportunidades'])) {
                $porter_oportunidades = is_array($datos_fuerzas_porter['oportunidades']) 
                    ? $datos_fuerzas_porter['oportunidades'] 
                    : array_filter(explode("\n", $datos_fuerzas_porter['oportunidades']));
                $oportunidades = array_merge($oportunidades, $porter_oportunidades);
            }
            if (!empty($datos_fuerzas_porter['amenazas'])) {
                $porter_amenazas = is_array($datos_fuerzas_porter['amenazas']) 
                    ? $datos_fuerzas_porter['amenazas'] 
                    : array_filter(explode("\n", $datos_fuerzas_porter['amenazas']));
                $amenazas = array_merge($amenazas, $porter_amenazas);
            }
        }
        
        return [
            'fortalezas' => array_values(array_filter(array_unique($fortalezas))),
            'debilidades' => array_values(array_filter(array_unique($debilidades))),
            'oportunidades' => array_values(array_filter(array_unique($oportunidades))),
            'amenazas' => array_values(array_filter(array_unique($amenazas)))
        ];
    }
    
    private function generarIdentificacionEstrategica() {
        $resumen = $this->datos_plan['resumen_ejecutivo'];
        $identificacion = $resumen['identificacion_estrategica'] ?? '';
        
        if (!empty($identificacion)) {
            $this->agregarSeccion('Identificación Estratégica', '#FF9800');
            $this->SetFont('helvetica', '', 10);
            $this->MultiCell(0, 6, $identificacion, 0, 'J');
            $this->Ln(5);
        }
    }
    
    private function generarAccionesCompetitivas() {
        $this->agregarSeccion('Acciones Competitivas (Estrategias CAME)', '#4CAF50');
        
        $datos_matriz_came = $this->datos_plan['matriz_came'];
        $acciones_competitivas = [];
        
        if (!empty($datos_matriz_came)) {
            // Buscar estrategias con patrón específico
            foreach ($datos_matriz_came as $key => $value) {
                if (preg_match('/^(corregir|afrontar|mantener|explotar)_\d+$/', $key) && 
                    is_string($value) && !empty(trim($value))) {
                    $acciones_competitivas[] = trim($value);
                }
            }
            
            // Buscar en categorías tradicionales si no hay con el patrón anterior
            if (empty($acciones_competitivas)) {
                $categorias_came = ['corregir', 'afrontar', 'mantener', 'explotar'];
                foreach ($categorias_came as $categoria) {
                    if (!empty($datos_matriz_came[$categoria])) {
                        if (is_array($datos_matriz_came[$categoria])) {
                            foreach ($datos_matriz_came[$categoria] as $estrategia) {
                                if (is_string($estrategia) && !empty(trim($estrategia))) {
                                    $acciones_competitivas[] = trim($estrategia);
                                }
                            }
                        } elseif (is_string($datos_matriz_came[$categoria]) && !empty(trim($datos_matriz_came[$categoria]))) {
                            $acciones_competitivas[] = trim($datos_matriz_came[$categoria]);
                        }
                    }
                }
            }
        }
        
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 6, 'Estrategias derivadas del análisis CAME (Corregir, Afrontar, Mantener, Explotar):', 0, 1, 'L');
        $this->Ln(2);
        
        $this->generarListaItems(array_unique($acciones_competitivas));
        
        if (!empty($acciones_competitivas)) {
            $this->SetFont('helvetica', 'B', 9);
            $this->SetTextColor(76, 175, 80);
            $this->Cell(0, 6, 'Total de estrategias: ' . count(array_unique($acciones_competitivas)), 0, 1, 'L');
            $this->SetTextColor(0, 0, 0);
        }
        
        $this->Ln(5);
    }
    
    private function generarConclusiones() {
        $resumen = $this->datos_plan['resumen_ejecutivo'];
        $conclusiones = $resumen['conclusiones'] ?? '';
        
        if (!empty($conclusiones)) {
            $this->agregarSeccion('Conclusiones', '#FF9800');
            $this->SetFont('helvetica', '', 10);
            $this->MultiCell(0, 6, $conclusiones, 0, 'J');
            $this->Ln(5);
        }
    }
    
    private function agregarSeccion($titulo, $color) {
        // Convertir color hex a RGB
        $rgb = $this->hexToRgb($color);
        
        $this->SetFont('helvetica', 'B', 12);
        $this->SetFillColor($rgb['r'], $rgb['g'], $rgb['b']);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 10, $titulo, 0, 1, 'L', true);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(3);
    }
    
    private function generarListaItems($items) {
        if (!empty($items)) {
            foreach ($items as $item) {
                if (!empty(trim($item))) {
                    $this->Cell(5, 6, '•', 0, 0, 'L');
                    $this->MultiCell(0, 6, trim($item), 0, 'J');
                }
            }
        } else {
            $this->Cell(0, 6, 'No se han definido elementos para esta sección', 0, 1, 'L');
        }
        $this->Ln(3);
    }
    
    private function hexToRgb($hex) {
        $hex = str_replace('#', '', $hex);
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }
    
    public function descargar($nombre_archivo = null) {
        if ($nombre_archivo === null) {
            $nombre_empresa = $this->datos_plan['informacion_empresa']['nombre_empresa'] ?? 'Empresa';
            $fecha = date('Y-m-d');
            $nombre_archivo = "Resumen_Ejecutivo_{$nombre_empresa}_{$fecha}.pdf";
        }
        
        $this->Output($nombre_archivo, 'D');
    }
    
    public function mostrar() {
        $this->Output('Resumen_Ejecutivo.pdf', 'I');
    }
}
?>
