<?php 
require_once __DIR__ . '/../config/clsconexion.php';

class ClsValores
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = (new clsConexion())->getConexion();
    }

    public function guardarValor($id_empresa, $valor)
    {
        $stmt = $this->conexion->prepare("INSERT INTO tb_valores (id_empresa, valor) VALUES (?, ?)");
        
        if (!$stmt) {
            throw new Exception("Error en prepare: " . $this->conexion->error);
        }

        $stmt->bind_param("is", $id_empresa, $valor);

        if (!$stmt->execute()) {
            throw new Exception("Error en execute: " . $stmt->error);
        }

        $stmt->close();
    }
}
?>
