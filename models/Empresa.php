<?php
require_once __DIR__ . '/../config/Database.php';

class Empresa {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarEmpresas() {
        $query = "SELECT * FROM Empresa";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Retorna el resultado
    }

    public function registrarEmpresa($data) {
        $query = "INSERT INTO Empresa (nombre_empresa, direccion_empresa, telefono_empresa) 
                  VALUES (:nombre_empresa, :direccion_empresa, :telefono_empresa)";
        $stmt = $this->conn->prepare($query);

        // Binding de parámetros
        $stmt->bindParam(":nombre_empresa", $data['nombre_empresa']);
        $stmt->bindParam(":direccion_empresa", $data['direccion_empresa']);
        $stmt->bindParam(":telefono_empresa", $data['telefono_empresa']);

        $stmt->execute();
    }
}
?>
