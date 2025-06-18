<?php
// Archivo index.php principal del proyecto
session_start();

// Configurar reporte de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el router
require_once 'Core/Router.php';

// Crear instancia del router y manejar la petición
$router = new Router();
$router->handleRequest();
?>
