<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');



require_once '../controllers/clienteController.php';
require_once '../controllers/ventasController.php';
require_once '../controllers/interaccionesController.php';
require_once '../controllers/productosController.php';
require_once '../controllers/tipoInteraccionesController.php';
require_once '../controllers/reportsController.php';
require_once '../controllers/gestorContactosController.php';

// Verificar los parámetros de la URL
// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Verificar si es una solicitud de OPTIONS y devolver una respuesta vacía
if ($method == "OPTIONS") {
    http_response_code(200);
    exit;
}

$request = $_SERVER['REQUEST_URI'];
$data = json_decode(file_get_contents("php://input"), true);

// Extraer la ruta de la solicitud
$requestPath = strtok($request, '?');

// Enrutador para llamar a los controladores correctos
switch ($requestPath) {
    case '/clientes':
        $clienteController = new ClienteController();
        switch ($method) {
            case 'GET':
                $clienteController->listarClientes();
                break;
            case 'POST':
                $clienteController->registrarCliente($data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/ventas':
        $ventaController = new VentaController();
        switch ($method) {
            case 'GET':
                $ventaController->listarVentas();
                break;
            case 'POST':
                $ventaController->registrarVenta($data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/interacciones':
        $interaccionController = new InteraccionController();
        switch ($method) {
            case 'GET':
                $interaccionController->listarInteracciones();
                break;
            case 'POST':
                $interaccionController->registrarInteraccion($data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/productos':
        $productoController = new ProductoController();
        switch ($method) {
            case 'GET':
                $productoController->listarProductos();
                break;
            case 'POST':
                $productoController->registrarProducto($data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/tipo_interacciones':
        $tipoInteraccionController = new TipoInteraccionController();
        switch ($method) {
            case 'GET':
                if (isset($requestUri[2])) {
                    $tipoInteraccionController->obtenerTipoInteraccion($requestUri[2]);
                } else {
                    $tipoInteraccionController->listarTiposInteraccion();
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/gestor_reportes':
        $gestorReportesController = new GestorReportesController();
        switch ($method) {
            case 'GET':
                if ($action === 'reporte_ventas') {
                    $gestorReportesController->generarReporteVentas();
                } elseif ($action === 'reporte_clientes') {
                    $gestorReportesController->generarReporteClientes();
                } elseif ($action === 'reporte_inventario') {
                    $gestorReportesController->generarReporteInventario();
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Action not found"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    case '/gestor_contactos':
        $gestorContactosController = new GestorContactosController();
        switch ($method) {
            case 'POST':
                if ($action === 'programar_seguimiento') {
                    $gestorContactosController->programarSeguimiento();
                } elseif ($action === 'enviar_correo_masivo') {
                    $gestorContactosController->enviarCorreoMasivo();
                } elseif ($action === 'gestionar_campana') {
                    $gestorContactosController->gestionarCampania();
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Action not found"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Controller not found"]);
        break;
}
?>