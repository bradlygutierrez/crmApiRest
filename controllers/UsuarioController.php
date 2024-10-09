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
        if ($usuario->registrarUsuario($data)) {
            echo json_encode(["message" => "Usuario registrado correctamente"]);
        } else {
            echo json_encode(["message" => "Error al registrar el usuario"]);
        }
    }
}
?>
