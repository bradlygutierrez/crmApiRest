<?php
require_once __DIR__ . '/../models/Usuario.php';
header('Content-Type: application/json');

class UsuarioController
{
    public function __construct() {}

    public function listarUsuarios()
    {
        $usuario = new Usuario();
        $usuarios = $usuario->listarUsuarios();

        if (!empty($usuarios)) {
            echo json_encode([
                "success" => true,
                "data" => $usuarios
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No hay usuarios disponibles"
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarUsuario($data)
    {
        $usuario = new Usuario();

        if (!isset($data['nombre_usuario'], $data['email_usuario'], $data['contraseña'], $data['rol'])) {
            echo json_encode([
                "success" => false,
                "message" => "Datos incompletos"
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $resultado = $usuario->registrarUsuario($data);
        echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
    }

    public function loginUsuario($data)
    {
        $usuario = new Usuario();

        if (!isset($data['email_usuario'], $data['contraseña'])) {
            echo json_encode([
                "success" => false,
                "message" => "Datos de inicio de sesión incompletos"
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $email = $data['email_usuario'];
        $password = $data['contraseña'];
        $resultado = $usuario->autenticarUsuario($email, $password);

        if ($resultado) {
            echo json_encode([
                "success" => true,
                "message" => "Login exitoso",
                "usuario" => $_SESSION['username']
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(401);
            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
        }
    }

    public function logoutUsuario()
    {
        $usuario = new Usuario();
        $resultado = $usuario->logoutUsuario();

        echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerUsuarioLogeado()
    {
        $usuario = new Usuario();
        $resultado = $usuario->obtenerUsuarioLogeado();

        if ($resultado) {
            echo json_encode([
                "success" => true,
                "usuario" => $resultado
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No hay usuario logeado"
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}
