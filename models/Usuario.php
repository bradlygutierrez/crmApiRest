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

    public function listarUsuarios(): mixed
    {
        $query = "SELECT * FROM Usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function registrarUsuario($data)
    {
        $query = "INSERT INTO Usuario (nombre_usuario, email_usuario, password_usuario, telefono_usuario) 
                  VALUES (:nombre_usuario, :email_usuario, :password_usuario, :telefono_usuario)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre_usuario", $data['nombre_usuario']);
        $stmt->bindParam(":email_usuario", $data['email_usuario']);
        $stmt->bindParam(":password_usuario", $data['password_usuario']);
        $stmt->bindParam(":telefono_usuario", $data['telefono_usuario']);
        
        return $stmt->execute();
    }
}
?>
