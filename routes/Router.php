<?php

class Router {
    private $requestUri;
    private $method;
    private $data;

    public function __construct() {
        $this->requestUri = strtok($_SERVER['REQUEST_URI'], '?');
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->data = json_decode(file_get_contents("php://input"), true);

        // CORS headers
        header("Access-Control-Allow-Origin: http://localhost:3000");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Credentials: true");
        header('Content-Type: application/json');

        // Handle OPTIONS pre-flight requests
        if ($this->method == "OPTIONS") {
            http_response_code(200);
            exit;
        }
    }

    public function route() {
        switch ($this->requestUri) {
            case '/clientes':
                $clienteController = new ClienteController();
                $this->dispatch($clienteController, 'listarClientes', 'registrarCliente');
                break;

            case '/ventas':
                $ventaController = new VentaController();
                $this->dispatch($ventaController, 'listarVentas', 'registrarVenta');
                break;

            case '/interacciones':
                $interaccionController = new InteraccionController();
                $this->dispatch($interaccionController, 'listarInteracciones', 'registrarInteraccion');
                break;

            case '/productos':
                $productoController = new ProductoController();
                $this->dispatch($productoController, 'listarProductos', 'registrarProducto');
                break;

            case '/tipo_interacciones':
                $tipoInteraccionController = new TipoInteraccionController();
                $this->dispatch($tipoInteraccionController, 'listarTiposInteraccion', null);
                break;

            case '/gestor_reportes':
                $gestorReportesController = new GestorReportesController();
                $this->dispatchReports($gestorReportesController);
                break;

            case '/gestor_contactos':
                $gestorContactosController = new GestorContactosController();
                $this->dispatchContactos($gestorContactosController);
                break;

            default:
                http_response_code(404);
                echo json_encode(["message" => "Ruta no encontrada"]);
                break;
        }
    }

    private function dispatch($controller, $getMethod, $postMethod) {
        switch ($this->method) {
            case 'GET':
                if ($getMethod) {
                    $controller->$getMethod();
                } else {
                    http_response_code(405);
                    echo json_encode(["message" => "Método no permitido"]);
                }
                break;
            case 'POST':
                if ($postMethod) {
                    $controller->$postMethod($this->data);
                } else {
                    http_response_code(405);
                    echo json_encode(["message" => "Método no permitido"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }

    private function dispatchReports($controller) {
        switch ($this->method) {
            case 'GET':
                $action = isset($_GET['action']) ? $_GET['action'] : null;
                if ($action === 'reporte_ventas') {
                    $controller->generarReporteVentas();
                } elseif ($action === 'reporte_clientes') {
                    $controller->generarReporteClientes();
                } elseif ($action === 'reporte_inventario') {
                    $controller->generarReporteInventario();
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Acción no encontrada"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }

    private function dispatchContactos($controller) {
        switch ($this->method) {
            case 'POST':
                $action = isset($_POST['action']) ? $_POST['action'] : null;
                if ($action === 'programar_seguimiento') {
                    $controller->programarSeguimiento();
                } elseif ($action === 'enviar_correo_masivo') {
                    $controller->enviarCorreoMasivo();
                } elseif ($action === 'gestionar_campana') {
                    $controller->gestionarCampania();
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Acción no encontrada"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }
}
