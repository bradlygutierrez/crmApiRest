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
        $query = "INSERT INTO Usuario (nombre_usuario, email_usuario, contraseña, rol) 
                  VALUES (:nombre_usuario, :email_usuario, :contraseña, :rol)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nombre_usuario", $data['nombre_usuario']);
        $stmt->bindParam(":email_usuario", $data['email_usuario']);
        $stmt->bindParam(":contraseña", $data['contraseña']);
        $stmt->bindParam(":rol", $data['rol']);
        
        return $stmt->execute();
    }

    public function autenticarUsuario($email, $password)
    {
        $query = "SELECT * FROM Usuario WHERE email_usuario = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && $usuario['contraseña'] === $password) {
            return $usuario; // Retorna los datos del usuario si coincide la contraseña
        } else {
            return false;
        }
    }
}
?>
