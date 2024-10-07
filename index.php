<?php

require_once __DIR__ . '/routes/Router.php';  // Incluye la clase Router usando ruta absoluta

// Incluye los controladores
require_once __DIR__ . '/controllers/clienteController.php';
require_once __DIR__ . '/controllers/ventasController.php';
require_once __DIR__ . '/controllers/interaccionesController.php';
require_once __DIR__ . '/controllers/productosController.php';
require_once __DIR__ . '/controllers/tipoInteraccionesController.php';
require_once __DIR__ . '/controllers/reportsController.php';
require_once __DIR__ . '/controllers/gestorContactosController.php';

// Instancia el enrutador y despacha la solicitud
$router = new Router();
$router->route();
