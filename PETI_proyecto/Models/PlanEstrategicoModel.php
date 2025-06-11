<?php
require_once __DIR__ . '/../config/clsconexion.php';

class PlanEstrategicoModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = (new clsConexion())->getConexion();
    }

    public function getIdEmpresaPorUsuario($id_usuario)
    {
        $stmt = $this->conexion->prepare("SELECT id_empresa FROM tb_empresa WHERE id_usuario = ?");
        if (!$stmt) {
            throw new Exception("Error en prepare (getIdEmpresaPorUsuario): " . $this->conexion->error);
        }
        $stmt->bind_param("i", $id_usuario);
        if (!$stmt->execute()) {
            throw new Exception("Error en execute (getIdEmpresaPorUsuario): " . $stmt->error);
        }
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $stmt->close();
        return $fila ? $fila['id_empresa'] : null;
    }

    public function getDatosEmpresa($id_empresa)
    {
        $stmt = $this->conexion->prepare(
            "SELECT e.nombre_empresa, e.mision, e.vision, e.descripcion AS descripcion_empresa, u.nombre AS nombre_responsable, u.apellido AS apellido_responsable, u.usuario AS codigo_responsable 
             FROM tb_empresa e
             JOIN tb_usuario u ON e.id_usuario = u.id_usuario
             WHERE e.id_empresa = ?"
        );
        if (!$stmt) {
            throw new Exception("Error en prepare (getDatosEmpresa): " . $this->conexion->error);
        }
        $stmt->bind_param("i", $id_empresa);
        if (!$stmt->execute()) {
            throw new Exception("Error en execute (getDatosEmpresa): " . $stmt->error);
        }
        $resultado = $stmt->get_result();
        $datos = $resultado->fetch_assoc();
        $stmt->close();
        return $datos;
    }

    public function getObjetivosEstrategicosYEspecificos($id_empresa, $misionDeLaEmpresa) 
    {
        $objetivos = [];
        $sql = "SELECT oe.id_obj_estra, oe.nombre_obj_estra, oes.descripcion_espe 
                FROM tb_obj_estra oe
                LEFT JOIN tb_obj_especificos oes ON oe.id_obj_estra = oes.id_obj_estra
                WHERE oe.id_empresa = ?
                ORDER BY oe.id_obj_estra, oes.id_obj_espe";
        
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en prepare (getObjetivosEstrategicosYEspecificos): " . $this->conexion->error);
        }
        $stmt->bind_param("i", $id_empresa);

        if (!$stmt->execute()) {
            throw new Exception("Error en execute (getObjetivosEstrategicosYEspecificos): " . $stmt->error);
        }
        
        $resultado = $stmt->get_result();
        $objetivosAgrupados = [];
        while ($fila = $resultado->fetch_assoc()) {
            $id_obj_estra = $fila['id_obj_estra'];
            if (!isset($objetivosAgrupados[$id_obj_estra])) {
                $objetivosAgrupados[$id_obj_estra] = [
                    'general' => $fila['nombre_obj_estra'],
                    'especificos' => []
                ];
            }
            if (!empty($fila['descripcion_espe'])) {
                $objetivosAgrupados[$id_obj_estra]['especificos'][] = $fila['descripcion_espe'];
            }
        }
        $stmt->close();
        
        $objetivosFinales = [];
        foreach($objetivosAgrupados as $objEst) {
            if (!empty($objEst['especificos'])) {
                foreach($objEst['especificos'] as $esp) {
                    $objetivosFinales[] = [
                        'mision_relacionada' => $misionDeLaEmpresa,
                        'general' => $objEst['general'],
                        'especifico' => $esp
                    ];
                }
            } else { 
                 $objetivosFinales[] = [
                    'mision_relacionada' => $misionDeLaEmpresa, 
                    'general' => $objEst['general'],
                    'especifico' => 'N/A' 
                ];
            }
        }
        if (empty($objetivosFinales) && !empty($misionDeLaEmpresa)) {
        }
        return $objetivosFinales;
    }

    public function getFoda($id_empresa)
    {
        $foda = [
            'fortalezas' => [],
            'debilidades' => [],
            'oportunidades' => [],
            'amenazas' => []
        ];

        $tablasFoda = [
            'fortalezas' => 'tb_fortalezas',
            'debilidades' => 'tb_debilidades',
            'oportunidades' => 'tb_oportunidades',
            'amenazas' => 'tb_amenazas'
        ];

        foreach ($tablasFoda as $tipo => $tabla) {
            $stmt = $this->conexion->prepare("SELECT descripcion FROM $tabla WHERE id_empresa = ?");
            if (!$stmt) {
                throw new Exception("Error en prepare (getFoda - $tipo): " . $this->conexion->error);
            }
            $stmt->bind_param("i", $id_empresa);
            if (!$stmt->execute()) {
                throw new Exception("Error en execute (getFoda - $tipo): " . $stmt->error);
            }
            $resultado = $stmt->get_result();
            while ($fila = $resultado->fetch_assoc()) {
                $foda[$tipo][] = $fila['descripcion'];
            }
            $stmt->close();
        }
        return $foda;
    }

    public function getResumenPlanEstrategicoCompleto($id_usuario)
    {
        $id_empresa = $this->getIdEmpresaPorUsuario($id_usuario);
        if (!$id_empresa) {
            return null; 
        }

        $datosEmpresa = $this->getDatosEmpresa($id_empresa);
        if (!$datosEmpresa) {
            return null;
        }

        $misionParaObjetivos = $datosEmpresa['mision'] ?? "Misión no definida";
        $objetivos = $this->getObjetivosEstrategicosYEspecificos($id_empresa, $misionParaObjetivos);
        
        $foda = $this->getFoda($id_empresa);

        $estrategiaIdentificada = ""; 
        $conclusionesGenerales = ""; 

        return [
            'nombre_empresa' => $datosEmpresa['nombre_empresa'],
            'responsable_nombres' => $datosEmpresa['nombre_responsable'] . ' ' . $datosEmpresa['apellido_responsable'],
            'responsable_codigo' => $datosEmpresa['codigo_responsable'], 
            'logo_placeholder' => "Logo de " . $datosEmpresa['nombre_empresa'], 
            'mision' => $datosEmpresa['mision'], 
            'vision' => $datosEmpresa['vision'],
            'objetivos' => $objetivos, 
            'foda' => $foda,
            'estrategia_identificada' => $estrategiaIdentificada,
            'conclusiones' => $conclusionesGenerales
        ];
    }
}
?>