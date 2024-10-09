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
        header("Access-Control-Allow-Origin: http://localhost:3001");
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
                $this->dispatch($interaccionController, 'listarInteracciones', 'registrarInteraccion');
                break;

            // Rutas para Citas
            case '/citas':
                $citaController = new CitaController();
                $this->dispatch($citaController, 'listarCitas', 'registrarCita');
                break;

            // Rutas para Usuarios
            case '/usuarios':
                $usuarioController = new UsuarioController();
                $this->dispatch($usuarioController, 'listarUsuarios', 'registrarUsuario');
                break;

            // Rutas para Pacientes
            case '/pacientes':
                $pacienteController = new PacienteController();
                $this->dispatch($pacienteController, 'listarPacientes', 'registrarPaciente');
                break;

            // Rutas para Empresas
            case '/empresas':
                $empresaController = new EmpresaController();
                $this->dispatch($empresaController, 'listarEmpresas', 'registrarEmpresa');
                break;

            // Rutas para Contactos
            case '/contactos':
                $contactoController = new ContactoController();
                $this->dispatch($contactoController, 'listarContactos', 'registrarContacto');
                break;

            // Rutas para Servicios
            case '/servicios':
                $servicioController = new ServicioController();
                $this->dispatch($servicioController, 'listarServicios', 'registrarServicio');
                break;

            // Rutas para Formularios
            case '/formularios':
                $formularioController = new FormularioController();
                $this->dispatch($formularioController, 'listarFormularios', 'registrarFormulario');
                break;

            default:
                http_response_code(404);
                echo json_encode(["message" => "Ruta no encontrada"]);
                break;
        }
    }

    private function dispatch($controller, $getMethod, $postMethod)
    {
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
            case 'PUT':
                if (method_exists($controller, 'actualizar')) {
                    $controller->actualizar($this->data);
                } else {
                    http_response_code(405);
                    echo json_encode(["message" => "Método no permitido"]);
                }
                break;
            case 'DELETE':
                if (method_exists($controller, 'eliminar')) {
                    $controller->eliminar($this->data);
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
}
?>