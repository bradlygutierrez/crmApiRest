<?php

class Router
{
    private $requestUri;
    private $method;
    private $data;

    public function __construct()
    {
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

    public function route()
    {
        switch ($this->requestUri) {
            // Rutas para Interacciones
            case '/interacciones':
                $interaccionController = new InteraccionController();
                $this->handleInteracciones($interaccionController);
                break;

            // Rutas para Citas
            case '/citas':
                $citaController = new CitaController();
                $this->handleCitas($citaController);
                break;

            // Rutas para Usuarios
            case '/usuarios':
                $usuarioController = new UsuarioController();
                $this->handleUsuarios($usuarioController);
                break;

            // Rutas para Pacientes
            case '/pacientes':
                $pacienteController = new PacienteController();
                $this->handlePacientes($pacienteController);
                break;

            // Rutas para Servicios
            case '/servicios':
                $servicioController = new ServicioController();
                $this->handleServicios($servicioController);
                break;

            // Rutas para Empresas
            case '/empresas':
                $empresaController = new EmpresaController();
                $this->handleEmpresas($empresaController);
                break;

            // Rutas para Formularios
            case '/formularios':
                $formularioController = new FormularioController();
                $this->handleFormularios($formularioController);
                break;

            // Rutas para Contactos
            case '/contactos':
                $contactoController = new ContactoController();
                $this->handleContactos($contactoController);
                break;

            // Ruta para Login
            case '/login':
                $usuarioController = new UsuarioController();
                $this->handleLogin($usuarioController);
                break;

            // Rutas para Tickets de Soporte
            case '/tickets':
                $ticketController = new TicketController();
                $this->handleTickets($ticketController);
                break;

            // Ruta para actualizar un ticket específico
            default:
                // Verifica si la URL es `/tickets/{id}`
                if (preg_match('#^/tickets/(\d+)$#', $this->requestUri, $matches)) {
                    $ticketId = $matches[1];
                    $ticketController = new TicketController();
                    if ($this->method === 'PUT') {
                        $ticketController->actualizarTicket($ticketId, $this->data);
                    } else {
                        http_response_code(405);
                        echo json_encode(["message" => "Método no permitido"]);
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Ruta no encontrada"]);
                }
                break;
        }
    }

    private function handleInteracciones($controller)
    {
        switch ($this->method) {
            case 'GET':
                $controller->listarInteracciones();
                break;
            case 'POST':
                $controller->registrarInteraccion($this->data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }

    private function handleCitas($controller)
    {
        switch ($this->method) {
            case 'GET':
                $controller->listarCitas();
                break;
            case 'POST':
                $controller->registrarCita($this->data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }

    private function handleFormularios($controller)
    {
        switch ($this->method) {
            case 'GET':
                $controller->listarFormularios();
                break;
            case 'POST':
                $controller->registrarFormulario($this->data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }

    private function handleUsuarios($controller)
    {
        switch ($this->method) {
            case 'GET':
                $controller->listarUsuarios();
                break;
            case 'POST':
                $controller->registrarUsuario($this->data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }

    private function handlePacientes($controller)
    {
        switch ($this->method) {
            case 'GET':
                $controller->listarPacientes();
                break;
            case 'POST':
                $controller->registrarPaciente($this->data);
                break;
            case 'PUT':
                if (preg_match('/^\/pacientes\/([0-9]+)$/', $this->requestUri, $matches)) {
                    $id_paciente = $matches[1];
                    $controller->actualizarPaciente($id_paciente, $this->data);
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Ruta no encontrada"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }

    private function handleServicios($controller)
    {
        switch ($this->method) {
            case 'GET':
                $controller->listarServicios();
                break;
            case 'POST':
                $controller->registrarServicio($this->data);
                break;
            case 'PUT':
                if (preg_match('/^\/servicios\/([0-9]+)$/', $this->requestUri, $matches)) {
                    $id_servicio = $matches[1];
                    $controller->actualizarServicio($id_servicio, $this->data);
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Ruta no encontrada"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }

    private function handleEmpresas($controller)
    {
        switch ($this->method) {
            case 'GET':
                $controller->listarEmpresas();
                break;
            case 'POST':
                $controller->registrarEmpresa($this->data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }

    private function handleContactos($controller)
    {
        switch ($this->method) {
            case 'GET':
                $controller->listarContactos();
                break;
            case 'POST':
                $controller->registrarContacto($this->data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }

    private function handleTickets($controller)
    {
        switch ($this->method) {
            case 'GET':
                $controller->listarTickets();
                break;
            case 'POST':
                $controller->registrarTicket($this->data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }

    private function handleLogin($controller)
    {
        switch ($this->method) {
            case 'POST':
                $controller->loginUsuario($this->data);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
                break;
        }
    }
}
