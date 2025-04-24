<?php
require_once "../Models/ClsValores.php";
session_start();

class ControladorValores
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ClsValores();
    }

    public function guardar()
    {
        // Verificar si los datos están presentes
        if (isset($_POST['valores']) && isset($_SESSION['user_id'])) {
            $valores = $_POST['valores']; // Ahora es un arreglo
            $id_usuario = $_SESSION['user_id'];

            // Obtener la empresa del usuario
            require_once __DIR__ . '/../config/clsconexion.php';
            $conexion = (new clsConexion())->getConexion();

            $stmt = $conexion->prepare("SELECT id_empresa FROM tb_empresa WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($fila = $resultado->fetch_assoc()) {
                $id_empresa = $fila['id_empresa'];

                // Insertar cada valor en la base de datos
                foreach ($valores as $valor) {
                    $this->modelo->guardarValor($id_empresa, $valor);
                }
            }

            // Redirigir con un mensaje de éxito
            header("Location: Vista/valores.php?msg=Valores guardados correctamente.");
            exit();
        } else {
            echo "Faltan datos del formulario o sesión";
        }
    }
}

// Ejecutar la acción si se llamó desde un formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controlador = new ControladorValores();
    $controlador->guardar();
}
?>
