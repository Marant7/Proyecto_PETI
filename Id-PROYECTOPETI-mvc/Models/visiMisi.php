<?php 
require_once __DIR__ . '/../config/clsconexion.php';  // Incluir la clase para la conexión

class visiMisi
{
    private $conexion;
    public function __construct()
    {
        $this->conexion = (new clsConexion())->getConexion();
    }

    public function actualizarVisionMision($id_usuario, $vision, $mision, $fecha_creacion)
    {
        $stmt = $this->conexion->prepare("UPDATE tb_empresa SET vision = ?, mision = ?, fecha_creacion = ? WHERE id_usuario = ?");
        
        if (!$stmt) {
            throw new Exception("Error en prepare: " . $this->conexion->error);
        }

        $stmt->bind_param("sssi", $vision, $mision, $fecha_creacion, $id_usuario);

        if (!$stmt->execute()) {
            throw new Exception("Error en execute: " . $stmt->error);
        }

        $stmt->close();
    }
}
?>
