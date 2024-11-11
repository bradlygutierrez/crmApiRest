<?php
require_once __DIR__ . '/../config/Database.php';

class Cita
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarCitas(): mixed
    {
        // Realizamos un INNER JOIN para obtener información del paciente y servicio relacionados con la cita
        $query = "SELECT 
                Cita.id_cita,
                Cita.fecha_cita,
                Cita.hora_cita,
                Cita.estado_cita,
                Paciente.nombre_paciente,
                Servicio.nombre_servicio,
                Servicio.costo_servicio
            FROM Cita
            INNER JOIN Paciente ON Cita.id_paciente = Paciente.id_paciente
            INNER JOIN Servicio ON Cita.id_servicio = Servicio.id_servicio
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Devuelve un array asociativo
    }


    public function registrarCita($data)
    {
        // Validar que se pasaron los datos necesarios
        if (!isset($data['nombre_paciente'], $data['nombre_servicio'], $data['fecha_cita'], $data['hora_cita'], $data['estado_cita'])) {
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

        // Obtener el id del servicio
        $queryServicio = "SELECT id_servicio FROM Servicio WHERE nombre_servicio = :nombre_servicio LIMIT 1";
        $stmtServicio = $this->conn->prepare($queryServicio);
        $stmtServicio->bindParam(":nombre_servicio", $data['nombre_servicio']);
        $stmtServicio->execute();
        $servicio = $stmtServicio->fetch(PDO::FETCH_ASSOC);

        // Si no se encuentra el servicio, manejar el error
        if (!$servicio) {
            throw new Exception("Servicio no encontrado");
        }

        // Ahora que tenemos los IDs, insertamos la nueva cita
        $query = "INSERT INTO Cita (fecha_cita, hora_cita, estado_cita, id_paciente, id_servicio) 
                  VALUES (:fecha_cita, :hora_cita, :estado_cita, :id_paciente, :id_servicio)";
        $stmt = $this->conn->prepare($query);

        // Bindear los parámetros
        $stmt->bindParam(":fecha_cita", $data['fecha_cita']);
        $stmt->bindParam(":hora_cita", $data['hora_cita']);
        $stmt->bindParam(":estado_cita", $data['estado_cita']);
        $stmt->bindParam(":id_paciente", $paciente['id_paciente']);
        $stmt->bindParam(":id_servicio", $servicio['id_servicio']);

        // Ejecutar la consulta
        return $stmt->execute();
    }

    public function listarCitasPorUsuario($nombre_paciente): mixed
    {
        try {
            $query = "SELECT 
                    Cita.id_cita,
                    Cita.fecha_cita,
                    Cita.hora_cita,
                    Cita.estado_cita,
                    Paciente.nombre_paciente,
                    Servicio.nombre_servicio,
                    Servicio.costo_servicio
                FROM Cita
                INNER JOIN Paciente ON Cita.id_paciente = Paciente.id_paciente
                INNER JOIN Servicio ON Cita.id_servicio = Servicio.id_servicio
                WHERE Paciente.nombre_paciente = :nombre_paciente";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre_paciente", $nombre_paciente, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            return null; // Handle errors appropriately
        }
    }

}
?>