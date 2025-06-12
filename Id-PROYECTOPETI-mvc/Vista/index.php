<?php
// Incluimos el archivo Router.php
require_once __DIR__ . '/../Core/Router.php';

// Creamos una instancia del Router
$router = new Router();

// Llamamos al método que maneja las rutas
$router->handleRequest();