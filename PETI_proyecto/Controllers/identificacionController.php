<?php
require_once __DIR__ . '/../Models/ClsIdentificacion.php';
require_once '../config/clsconexion.php';

class identificacionController 
{
    private $modelo;

    public function __construct() 
    {
        $this->modelo = new ClsIdentificacion();
    }

    /**
     * Obtiene todos los datos FODA de una empresa
     * @param int $id_empresa
     * @return array
     */
    public function obtenerDatosFODA($id_empresa)
    {
        // Usamos el modelo que ya tienes creado
        $datos = $this->modelo->obtenerTodoFODA($id_empresa);
        
        // Convertimos los arrays de objetos a arrays simples de strings
        // porque el modelo devuelve [['descripcion' => 'texto'], ['descripcion' => 'texto2']]
        // pero la vista necesita ['texto', 'texto2']
        
        $resultado = [
            'fortalezas'    => $this->extraerDescripciones($datos['fortalezas']),
            'debilidades'   => $this->extraerDescripciones($datos['debilidades']),
            'oportunidades' => $this->extraerDescripciones($datos['oportunidades']),
            'amenazas'      => $this->extraerDescripciones($datos['amenazas'])
        ];
        
        return $resultado;
    }

    /**
     * Extrae solo las descripciones de un array de datos
     * @param array $datos
     * @return array
     */
    private function extraerDescripciones($datos)
    {
        $descripciones = [];
        foreach ($datos as $item) {
            if (isset($item['descripcion'])) {
                $descripciones[] = $item['descripcion'];
            }
        }
        return $descripciones;
    }

    /**
     * Método auxiliar para obtener el id_empresa de un usuario
     * @param int $id_usuario
     * @return int|null
     */
    public function obtenerIdEmpresaPorUsuario($id_usuario)
    {
        $conexion = (new clsConexion())->getConexion();
        
        $query = "SELECT id_empresa FROM tb_empresa WHERE id_usuario = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['id_empresa'];
        }
        
        $stmt->close();
        return null;
    }
}
?>