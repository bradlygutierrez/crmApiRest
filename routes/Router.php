<?php
// En tu configuración de sesión, asegúrate de que las cookies sean accesibles desde otros dominios

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
        header("Access-Control-Allow-Origin: http://localhost:3000"); // Permite el origen de tu frontend
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH"); // Métodos permitidos
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, credentials"); // Encabezados permitidos
        header("Access-Control-Allow-Credentials: true"); // Permitir cookies

        // Permitir el uso de cookies y credenciales
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

            case '/citas/usuario':
                $citaController = new CitaController();
                $this->handleListarCitasPorUsuario($citaController);
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

            case '/logout':
                $usuarioController = new UsuarioController();
                $this->handleLogout($usuarioController);
                break;

            // Ruta para obtener usuario logeado
            case '/usuario-logeado':
                $usuarioController = new UsuarioController();
                $this->handleObtenerUsuarioLogeado($usuarioController);
                break;

            // Rutas para Tickets de Soporte
            case '/tickets':
                $ticketController = new TicketController();
                $this->handleTickets($ticketController);
                break;

            // Ruta para listar tickets por usuario
            case '/tickets/usuario':
                $ticketController = new TicketController();
                $this->handleListarTicketsPorUsuario($ticketController);
                break;

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
            case '/pacientes/chequeos-pendientes':
                $pacienteController = new PacienteController();
                $pacienteController->obtenerPacientesPendientes();
                break;
            case '/recuperar-contrasena':
                $usuarioController = new UsuarioController();
                $this->handleRecuperarContrasena($usuarioController);
                break;
            // Ruta para actualizar un ticket específico
            default:
                // Verifica rutas específicas como /tickets/{id}, /pacientes/{id}, /servicios/{id}, /citas/{id}, /interacciones/{id}, /empresas/{id} o /contactos/{id}
                if (preg_match('#^/tickets/(\d+)$#', $this->requestUri, $matches)) {
                    $ticketId = $matches[1];
                    $ticketController = new TicketController();

                    // Decodificar el cuerpo de la solicitud JSON en $this->data
                    $this->data = json_decode(file_get_contents("php://input"), true);

                    if ($this->method === 'PATCH') {
                        $ticketController->cambiarEstadoTicket($ticketId, $this->data);
                    } else {
                        http_response_code(405);
                        echo json_encode(["message" => "Método no permitido"]);
                    }

                } elseif (preg_match('#^/pacientes/(\d+)$#', $this->requestUri, $matches)) {
                    $pacienteId = $matches[1];
                    $pacienteController = new PacienteController();

                    // Decodificar el cuerpo de la solicitud JSON en $this->data
                    $this->data = json_decode(file_get_contents("php://input"), true);

                    if ($this->method === 'PUT') {
                        $pacienteController->actualizarPaciente($pacienteId, $this->data);
                    } else {
                        http_response_code(405);
                        echo json_encode(["message" => "Método no permitido"]);
                    }

                } elseif (preg_match('#^/servicios/(\d+)$#', $this->requestUri, $matches)) {
                    $idServicio = $matches[1];
                    $servicioController = new ServicioController();

                    // Decodificar el cuerpo de la solicitud JSON en $this->data
                    $this->data = json_decode(file_get_contents("php://input"), true);

                    if ($this->method === 'PUT') {
                        $servicioController->actualizarServicio($idServicio, $this->data);
                    } else {
                        http_response_code(405);
                        echo json_encode(["message" => "Método no permitido"]);
                    }

                } elseif (preg_match('#^/citas/(\d+)$#', $this->requestUri, $matches)) {
                    $idCita = $matches[1];
                    $citaController = new CitaController();

                    // Decodificar el cuerpo de la solicitud JSON en $this->data
                    $this->data = json_decode(file_get_contents("php://input"), true);

                    if ($this->method === 'PUT') {
                        $citaController->modificarCita($idCita, $this->data);
                    } else {
                        http_response_code(405);
                        echo json_encode(["message" => "Método no permitido"]);
                    }

                } elseif (preg_match('#^/interacciones/(\d+)$#', $this->requestUri, $matches)) {
                    $idInteraccion = $matches[1];
                    $interaccionController = new InteraccionController();

                    // Decodificar el cuerpo de la solicitud JSON en $this->data
                    $this->data = json_decode(file_get_contents("php://input"), true);

                    if ($this->method === 'PUT') {
                        $interaccionController->modificarInteraccion($idInteraccion, $this->data);
                    } else {
                        http_response_code(405);
                        echo json_encode(["message" => "Método no permitido"]);
                    }

                } elseif (preg_match('#^/empresas/(\d+)$#', $this->requestUri, $matches)) {
                    $idEmpresa = $matches[1];
                    $empresaController = new EmpresaController();

                    // Decodificar el cuerpo de la solicitud JSON en $this->data
                    $this->data = json_decode(file_get_contents("php://input"), true);

                    if ($this->method === 'PUT') {
                        $empresaController->modificarEmpresa($idEmpresa, $this->data);
                    } else {
                        http_response_code(405);
                        echo json_encode(["message" => "Método no permitido"]);
                    }

                } elseif (preg_match('#^/contactos/(\d+)$#', $this->requestUri, $matches)) {
                    $idContacto = $matches[1];
                    $contactoController = new ContactoController();

                    // Decodificar el cuerpo de la solicitud JSON en $this->data
                    $this->data = json_decode(file_get_contents("php://input"), true);

                    if ($this->method === 'PUT') {
                        $contactoController->modificarContacto($idContacto, $this->data);
                    } else {
                        http_response_code(405);
                        echo json_encode(["message" => "Método no permitido"]);
                    }

                } elseif (preg_match('#^/usuarios/(\d+)$#', $this->requestUri, $matches)) {
                    $idUsuario = $matches[1];
                    $usuarioController = new UsuarioController();

                    // Decodificar el cuerpo de la solicitud JSON en $this->data
                    $this->data = json_decode(file_get_contents("php://input"), true);

                    if ($this->method === 'PUT') {
                        $usuarioController->actualizarUsuario($idUsuario, $this->data);
                    } else {
                        http_response_code(405);
                        echo json_encode(["message" => "Método no permitido"]);
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(["message" => "Ruta no encontrada"]);
                }

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

    // En el Router
    private function handleRecuperarContrasena($controller)
    {
        if ($this->method === 'POST') {
            $controller->recuperarContraseña($this->data);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
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

    private function handleListarTicketsPorUsuario($controller)
    {
        switch ($this->method) {
            case 'GET':
                $controller->listarTicketsPorUsuario();
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

    private function handleLogout($controller)
    {
        if ($this->method === 'POST') {
            $controller->logoutUsuario();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    private function handleObtenerUsuarioLogeado($controller)
    {
        if ($this->method === 'GET') {
            $controller->obtenerUsuarioLogeado();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    private function handleListarCitasPorUsuario($controller)
    {
        if ($this->method === 'GET') {
            $controller->listarCitasPorUsuario();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
    }

    private function handleTicketsPendientes($controller)
    {
        if ($this->method === 'GET') {
            $controller->obtenerTicketsPendientes();
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
