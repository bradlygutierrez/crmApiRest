<?php
require_once __DIR__ . '/../models/Usuario.php';
header('Content-Type: application/json');

class UsuarioController
{
    public function __construct()
    {
    }

    public function listarUsuarios()
    {
        $usuario = new Usuario();
        $usuarios = $usuario->listarUsuarios();
        $result = $usuarios->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo json_encode($result, flags: JSON_UNESCAPED_UNICODE);
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

    public function actualizarUsuario($id_usuario, $data)
    {
        $usuario = new Usuario();

        // Verificar que el ID del usuario y al menos un campo estén presentes
        if (empty($id_usuario) || empty($data)) {
            echo json_encode([
                "success" => false,
                "message" => "ID de usuario o datos incompletos"
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $resultado = $usuario->actualizarUsuario($id_usuario, $data);

        if ($resultado) {
            echo json_encode([
                "success" => true,
                "message" => "Usuario actualizado correctamente"
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al actualizar el usuario"
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function recuperarContraseña($data)
    {
        if (!isset($data['email'])) {
            echo json_encode([
                "success" => false,
                "message" => "Correo electrónico es requerido."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Instanciar el modelo de Usuario
        $usuario = new Usuario();

        // Buscar el usuario por correo
        $usuarioRecuperado = $usuario->obtenerUsuarioPorEmail($data['email']);

        if ($usuarioRecuperado) {
            // Enviar la contraseña actual al correo del usuario
            $this->enviarCorreo($data['email'], $usuarioRecuperado['contraseña']);

            echo json_encode([
                "success" => true,
                "message" => "Se ha enviado un correo con tu contraseña actual."
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No se encontró un usuario con ese correo."
            ], JSON_UNESCAPED_UNICODE);
        }
    }



    private function enviarCorreo($email, $contraseña)
    {
        $asunto = "Tu contraseña actual";
        $mensaje = "Tu contraseña actual es: $contraseña";
        $headers = "From: no-reply@CLINMED.com";

        // Usar la función mail() de PHP para enviar el correo
        mail($email, $asunto, $mensaje, $headers);
    }


}
