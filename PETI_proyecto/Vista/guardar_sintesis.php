<?php
// Archivo: guardar_sintesis.php
// Ubicación sugerida: en la misma carpeta donde tienes identificacion_estrategia.php

require_once '../Controllers/identificacionController.php';

$controller = new identificacionController();
$controller->procesarGuardarSintesis();
?>