<?php
require_once __DIR__ . '/../config/Database.php';

class Empresa
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarEmpresas()
    {
        $query = "SELECT * FROM Empresa";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Retorna el resultado
    }

    public function registrarEmpresa($data)
    {
        $query = "INSERT INTO Empresa (nombre_empresa, tipo_empresa, direccion, telefono, email) 
                  VALUES (:nombre_empresa, :tipo_empresa, :direccion, :telefono, :email)";
        $stmt = $this->conn->prepare($query);

        // Binding de parámetros
        $stmt->bindParam(":nombre_empresa", $data['nombre_empresa']);
        $stmt->bindParam(":tipo_empresa", $data['tipo_empresa']);
        $stmt->bindParam(":direccion", $data['direccion']);
        $stmt->bindParam(":telefono", $data['telefono']);
        $stmt->bindParam(":email", $data['email']);

        // Ejecutar la consulta y verificar el resultado
        if ($stmt->execute()) {
            return true; // Indica que la inserción fue exitosa
        } else {
            return false; // Indica que hubo un error
        }
    }

    public function modificarEmpresa($id_empresa, $data)
    {
        // Verificar que se pasaron los datos necesarios
        if (!isset($data['nombre_empresa'], $data['tipo_empresa'], $data['direccion'], $data['telefono'], $data['email'])) {
            throw new Exception("Faltan datos requeridos para la actualización");
        }

        // Crear la consulta de actualización
        $query = "UPDATE Empresa 
                  SET nombre_empresa = :nombre_empresa, 
                      tipo_empresa = :tipo_empresa, 
                      direccion = :direccion, 
                      telefono = :telefono, 
                      email = :email 
                  WHERE id_empresa = :id_empresa";

        $stmt = $this->conn->prepare($query);

        // Bindear los parámetros
        $stmt->bindParam(":nombre_empresa", $data['nombre_empresa']);
        $stmt->bindParam(":tipo_empresa", $data['tipo_empresa']);
        $stmt->bindParam(":direccion", $data['direccion']);
        $stmt->bindParam(":telefono", $data['telefono']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":id_empresa", $id_empresa, PDO::PARAM_INT);

        // Ejecutar la consulta y verificar si tuvo éxito
        if (!$stmt->execute()) {
            throw new Exception("Error al modificar la empresa");
        }

        return true;
    }

}
?>