<?php
require_once __DIR__ . '/../config/Database.php';

class Formulario {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarFormularios() {
        $query = "SELECT * FROM FormularioSatisfaccion";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Retorna el resultado
    }

    public function registrarFormulario($data) {
        $query = "INSERT INTO Formulario (titulo_formulario, contenido_formulario) 
                  VALUES (:titulo_formulario, :contenido_formulario)";
        $stmt = $this->conn->prepare($query);

        // Binding de parámetros
        $stmt->bindParam(":titulo_formulario", $data['titulo_formulario']);
        $stmt->bindParam(":contenido_formulario", $data['contenido_formulario']);

        $stmt->execute();
    }
}
?>
