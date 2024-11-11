<?php
session_start();

require_once __DIR__ . '/config/Database.php';  // Incluye la conexión a la base de datos
require_once __DIR__ . '/routes/Router.php';  // Incluye la clase Router

// Incluye los controladores
require_once __DIR__ . '/controllers/InteraccionController.php';
require_once __DIR__ . '/controllers/CitaController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/PacienteController.php';
require_once __DIR__ . '/controllers/EmpresaController.php';
require_once __DIR__ . '/controllers/ContactoController.php';
require_once __DIR__ . '/controllers/ServicioController.php';
require_once __DIR__ . '/controllers/FormularioController.php';
require_once __DIR__ . '/controllers/TicketsController.php';


// Instancia el enrutador y despacha la solicitud
$router = new Router();
$router->route();

?>
