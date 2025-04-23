<?php
require_once 'config/clsconexion.php';
require_once 'Modelo/Valor.php';

class ValorController {
    private $modelo;

    public function __construct() {
        $conexion = new clsConexion();
        $this->modelo = new Valor($conexion);
    }

    public function index() {
        $valores = $this->modelo->listarValores();
        include 'Vista/valores.php';
    }

    public function getValores() {
        return $this->modelo->listarValores();  // Asegúrate de llamar a listarValores
    }

    public function guardar() {
        $valor = $_POST['valor'];
        $empresa = $_POST['empresa'];
        $this->modelo->guardarValor($valor, $empresa);
        header("Location: valores.php");
    }

    public function eliminar() {
        $id = $_GET['id'];
        $this->modelo->eliminarValor($id);
        header("Location: valores.php");
    }

    public function editar() {
        $id = $_GET['id'];
        $valor = $this->modelo->obtenerValor($id);
        include 'Vista/valores.php';
    }

    public function actualizar() {
        $id = $_POST['id'];
        $valor = $_POST['valor'];
        $empresa = $_POST['empresa'];
        $this->modelo->actualizarValor($id, $valor, $empresa);
        header("Location: valores.php");
    }
}
?>
