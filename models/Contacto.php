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
        $query = "INSERT INTO Contacto (nombre_contacto, email_contacto, telefono_contacto) 
                  VALUES (:nombre_contacto, :email_contacto, :telefono_contacto)";
        $stmt = $this->conn->prepare($query);

        // Binding de parámetros
        $stmt->bindParam(":nombre_contacto", $data['nombre_contacto']);
        $stmt->bindParam(":email_contacto", $data['email_contacto']);
        $stmt->bindParam(":telefono_contacto", $data['telefono_contacto']);

        $stmt->execute();
    }
}
?>
