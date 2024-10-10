<?php
require_once __DIR__ . '/../config/Database.php';

class Interaccion
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarInteracciones(): mixed
    {
        // Realizamos un INNER JOIN para obtener información del paciente, usuario y tipo de interacción
        $query = "SELECT 
                Interaccion.id_interaccion, 
                Interaccion.fecha_interaccion, 
                Interaccion.nota_interaccion, 
                Paciente.nombre_paciente, 
                Usuario.nombre_usuario, 
                TipoInteraccion.nombre_tipoInteraccion 
            FROM Interaccion 
            INNER JOIN Paciente ON Interaccion.id_paciente = Paciente.id_paciente 
            INNER JOIN Usuario ON Interaccion.id_usuario = Usuario.id_usuario 
            INNER JOIN TipoInteraccion ON Interaccion.id_tipoInteraccion = TipoInteraccion.id_tipoInteraccion";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Devuelve un array asociativo
    }

    public function registrarInteraccion($data)
    {
        // Validar que se pasaron los datos necesarios
        if (!isset($data['fecha_interaccion'], $data['nota_interaccion'], $data['nombre_paciente'], $data['nombre_usuario'], $data['nombre_tipoInteraccion'])) {
            throw new Exception("Faltan datos requeridos");
        }

        // Obtener el id del paciente
        $queryPaciente = "SELECT id_paciente FROM Paciente WHERE nombre_paciente = :nombre_paciente LIMIT 1";
        $stmtPaciente = $this->conn->prepare($queryPaciente);
        $stmtPaciente->bindParam(":nombre_paciente", $data['nombre_paciente']);
        $stmtPaciente->execute();
        $paciente = $stmtPaciente->fetch(PDO::FETCH_ASSOC);

        // Si no se encuentra el paciente, manejar el error
        if (!$paciente) {
            throw new Exception("Paciente no encontrado");
        }

        // Obtener el id del usuario
        $queryUsuario = "SELECT id_usuario FROM Usuario WHERE nombre_usuario = :nombre_usuario LIMIT 1";
        $stmtUsuario = $this->conn->prepare($queryUsuario);
        $stmtUsuario->bindParam(":nombre_usuario", $data['nombre_usuario']);
        $stmtUsuario->execute();
        $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

        // Si no se encuentra el usuario, manejar el error
        if (!$usuario) {
            throw new Exception("Usuario no encontrado");
        }

        // Obtener el id del tipo de interacción
        $queryTipoInteraccion = "SELECT id_tipoInteraccion FROM TipoInteraccion WHERE nombre_tipoInteraccion = :nombre_tipoInteraccion LIMIT 1";
        $stmtTipoInteraccion = $this->conn->prepare($queryTipoInteraccion);
        $stmtTipoInteraccion->bindParam(":nombre_tipoInteraccion", $data['nombre_tipoInteraccion']);
        $stmtTipoInteraccion->execute();
        $tipoInteraccion = $stmtTipoInteraccion->fetch(PDO::FETCH_ASSOC);

        // Si no se encuentra el tipo de interacción, manejar el error
        if (!$tipoInteraccion) {
            throw new Exception("Tipo de interacción no encontrado");
        }

        // Ahora que tenemos los IDs, insertamos la nueva interacción
        $query = "INSERT INTO Interaccion (fecha_interaccion, nota_interaccion, id_paciente, id_usuario, id_tipoInteraccion) 
                  VALUES (:fecha_interaccion, :nota_interaccion, :id_paciente, :id_usuario, :id_tipoInteraccion)";
        $stmt = $this->conn->prepare($query);

        // Bindear los parámetros
        $stmt->bindParam(":fecha_interaccion", $data['fecha_interaccion']);
        $stmt->bindParam(":nota_interaccion", $data['nota_interaccion']);
        $stmt->bindParam(":id_paciente", $paciente['id_paciente']);
        $stmt->bindParam(":id_usuario", $usuario['id_usuario']);
        $stmt->bindParam(":id_tipoInteraccion", $tipoInteraccion['id_tipoInteraccion']);

        // Ejecutar la consulta
        return $stmt->execute();
    }
}
?>