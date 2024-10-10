<?php

require_once __DIR__ . '/../config/Database.php';

class Usuario
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarUsuarios()
    {
        $query = "SELECT * FROM Usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function registrarUsuario($data)
    {
        // Verificamos que todos los datos requeridos están presentes
        if (!isset($data['nombre_usuario'], $data['email_usuario'], $data['contraseña'], $data['rol'])) {
            return false;
        }

        // Asignamos las variables
        $nombre_usuario = $data['nombre_usuario'];
        $email_usuario = $data['email_usuario'];
        $contraseña = $data['contraseña'];
        $rol = $data['rol'];

        // Preparamos la consulta SQL para insertar el usuario
        $query = "INSERT INTO Usuario (nombre_usuario, email_usuario, contraseña, rol) 
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Ejecutamos la consulta con los datos proporcionados
        if ($stmt->execute([$nombre_usuario, $email_usuario, $contraseña, $rol])) {
            return true;
        } else {
            return false;
        }
    }

    public function autenticarUsuario($email, $contraseña)
    {
        $query = "SELECT * FROM Usuario WHERE email_usuario = ? AND contraseña = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email, $contraseña]);

        // Verificamos si el usuario existe
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC); // Devolvemos los datos del usuario
        } else {
            return false;
        }
    }
}
