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
            case '/interacciones':
                $interaccionController = new InteraccionController();
                $this->handleInteracciones($interaccionController);
                break;

            case '/citas':
                $citaController = new CitaController();
                $this->handleCitas($citaController);
                break;

            case '/usuarios':
                $usuarioController = new UsuarioController();
                $this->handleUsuarios($usuarioController);
                break;

            case '/pacientes':
                $pacienteController = new PacienteController();
                $this->handlePacientes($pacienteController);
                break;

            case '/pacientes-citas':
                $pacienteCitaController = new PacienteCitaController();
                $pacienteCitaController->obtenerPacientesYCitasMesActual();
                break;

            case '/pacientes-registros':
                $pacienteRegistroController = new PacienteRegistroController();
                $pacienteRegistroController->obtenerPacientesPorMes();
                break;

            case '/servicios':
                $servicioController = new ServicioController();
                $this->handleServicios($servicioController);
                break;

            case '/empresas':
                $empresaController = new EmpresaController();
                $this->handleEmpresas($empresaController);
                break;

            case '/formularios':
                $formularioController = new FormularioController();
                $this->handleFormularios($formularioController);
                break;

            case '/contactos':
                $contactoController = new ContactoController();
                $this->handleContactos($contactoController);
                break;

            case '/login':
                $usuarioController = new UsuarioController();
                $this->handleLogin($usuarioController);
                break;

            case '/tickets':
                $ticketController = new TicketController();
                $this->handleTickets($ticketController);
                break;

            // Ruta para contar pacientes del mes actual
            case '/pacientes/contar-mes':
                $pacienteController = new PacienteController();
                $pacienteController->contarPacientesMesActual(); // Llama al método para contar pacientes del mes
                break;

            case '/tickets/pendientes': // Nueva ruta para contar tickets pendientes
                $ticketController = new TicketController();
                $this->handleTicketsPendientes($ticketController);
                break;
            
            case '/citas/contar':
                $citaController = new CitaController();
                $citaController->contarCitasMes(); // Llama al método que cuenta las citas
                break;
            case '/pacientes/contar':
                $pacienteController = new PacienteController();
                $pacienteController->contarPacientes(); // Llama al método que cuenta los pacientes
                break;

            default:
                // Verifica rutas específicas como /tickets/{id} o /pacientes/{id}
                if (preg_match('#^/tickets/(\d+)$#', $this->requestUri, $matches)) {
                    $this->routeTicketById($matches[1]);
                } elseif (preg_match('#^/pacientes/(\d+)$#', $this->requestUri, $matches)) {
                    $this->routePacienteById($matches[1]);
                } elseif (preg_match('#^/servicios/(\d+)$#', $this->requestUri, $matches)) {
                    $this->routeServicioById($matches[1]);
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Ruta no encontrada"]);
                }
                break;
        }
    }

    // Métodos para manejar cada tipo de ruta (GET, POST, PUT, etc.)

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

    // Nueva función para manejar la ruta de tickets pendientes
    private function handleTicketsPendientes($controller)
    {
        if ($this->method === 'GET') {
            $controller->obtenerTicketsPendientes();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    private function handleLogin($controller)
    {
        if ($this->method === 'POST') {
            $controller->loginUsuario($this->data);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    private function routeTicketById($id)
    {
        $ticketController = new TicketController();
        if ($this->method === 'PUT') {
            $ticketController->actualizarTicket($id, $this->data);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    private function routePacienteById($id)
    {
        $pacienteController = new PacienteController();
        if ($this->method === 'PUT') {
            $pacienteController->actualizarPaciente($id, $this->data);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    private function routeServicioById($id)
    {
        $servicioController = new ServicioController();
        if ($this->method === 'PUT') {
            $servicioController->actualizarServicio($id, $this->data);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }
}
