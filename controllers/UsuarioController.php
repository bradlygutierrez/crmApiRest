<?php
require_once __DIR__ . '/../models/Usuario.php';
header('Content-Type: application/json');

class UsuarioController
{
    public function __construct() {}

    public function listarUsuarios()
    {
        $usuario = new Usuario();
        $stmt = $usuario->listarUsuarios();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay usuarios disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarUsuario($data)
    {
        $usuario = new Usuario();

        // Verificación de campos antes de llamar al método
        if (!isset($data['nombre_usuario'], $data['email_usuario'], $data['contraseña'], $data['rol'])) {
            echo json_encode(["message" => "Datos incompletos"], JSON_UNESCAPED_UNICODE);
            return;
        }

        if ($usuario->registrarUsuario($data)) {
            echo json_encode(["message" => "Usuario registrado correctamente"]);
        } else {
            echo json_encode(["message" => "Error al registrar el usuario"]);
        }
    }

    public function loginUsuario($data)
    {
        $usuario = new Usuario();
        $email = $data['email_usuario'];
        $password = $data['contraseña'];

        $autenticado = $usuario->autenticarUsuario($email, $password);

        if ($autenticado) {
            echo json_encode([
                "message" => "Login exitoso",
                "usuario" => $autenticado
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Credenciales incorrectas"]);
        }
    }
}
