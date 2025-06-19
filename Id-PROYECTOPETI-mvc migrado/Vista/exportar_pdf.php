<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$id_plan = $_GET['id'] ?? null;
if (!$id_plan) {
    header('Location: home.php');
    exit();
}

// Cargar controlador y ejecutar exportación
require_once '../Controllers/PlanEstrategicoController.php';
$controller = new PlanEstrategicoController();
$controller->exportarPDF($id_plan);
?>
