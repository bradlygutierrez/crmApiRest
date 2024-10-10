<?php
require_once __DIR__ . '/../config/Database.php';

class Contacto {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarContactos() {
        // Consulta con INNER JOIN para obtener datos de Contacto y Empresa
        $query = "SELECT 
                c.id_contacto,
                c.nombre_contacto,
                c.email_contacto,
                c.telefono_contacto,
                c.cargo,
                e.nombre_empresa,
                e.tipo_empresa  
            FROM 
                Contacto c
            INNER JOIN 
                Empresa e ON c.id_empresa = e.id_empresa"; // Relación entre Contacto y Empresa
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Retorna el resultado
    }
    
    public function registrarContacto($data) {
        // Validar que se pasaron los datos necesarios
        if (!isset($data['nombre_contacto'], $data['email_contacto'], $data['telefono_contacto'], $data['cargo'], $data['nombre_empresa'])) {
            throw new Exception("Faltan datos requeridos");
        }

        // Obtener el id de la empresa
        $queryEmpresa = "SELECT id_empresa FROM Empresa WHERE nombre_empresa = :nombre_empresa LIMIT 1";
        $stmtEmpresa = $this->conn->prepare($queryEmpresa);
        $stmtEmpresa->bindParam(":nombre_empresa", $data['nombre_empresa']);
        $stmtEmpresa->execute();
        $empresa = $stmtEmpresa->fetch(PDO::FETCH_ASSOC);

        // Si no se encuentra la empresa, manejar el error
        if (!$empresa) {
            throw new Exception("Empresa no encontrada");
        }

        // Ahora que tenemos el ID de la empresa, insertamos el nuevo contacto
        $query = "INSERT INTO Contacto (nombre_contacto, email_contacto, telefono_contacto, cargo, id_empresa) 
                  VALUES (:nombre_contacto, :email_contacto, :telefono_contacto, :cargo, :id_empresa)";
        $stmt = $this->conn->prepare($query);

        // Bindear los parámetros
        $stmt->bindParam(":nombre_contacto", $data['nombre_contacto']);
        $stmt->bindParam(":email_contacto", $data['email_contacto']);
        $stmt->bindParam(":telefono_contacto", $data['telefono_contacto']);
        $stmt->bindParam(":cargo", $data['cargo']);
        $stmt->bindParam(":id_empresa", $empresa['id_empresa']); // Usar el id_empresa obtenido

        // Ejecutar la consulta
        return $stmt->execute(); // Retorna true si la inserción fue exitosa
    }
}
?>
