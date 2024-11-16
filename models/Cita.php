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
    public function contarCitasMes()
    {
        $query = "SELECT COUNT(*) as total_citas_mes FROM Cita WHERE MONTH(fecha_cita) = MONTH(CURRENT_DATE) AND YEAR(fecha_cita) = YEAR(CURRENT_DATE)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_citas_mes'];
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
        if (!isset($data['nombre_paciente'], $data['nombre_servicio'], $data['fecha_cita'], $data['hora_cita'], $data['estado_cita'], $data['nombre_usuario'])) {
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

        $queryUsuario = "SELECT id_usuario FROM Usuario Where nombre_usuario = :nombre_usuario LIMIT 1";
        $stmtUsuario = $this->conn->prepare($queryUsuario);
        $stmtUsuario->bindParam(":nombre_usuario", $data['nombre_usuario']);
        $stmtUsuario->execute();
        $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            throw new Exception("Usuario no encontrado");
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
        $query = "INSERT INTO Cita (fecha_cita, hora_cita, estado_cita, id_paciente, id_servicio, id_usuario) 
                  VALUES (:fecha_cita, :hora_cita, :estado_cita, :id_paciente, :id_servicio, :id_usuario)";
        $stmt = $this->conn->prepare($query);

        // Bindear los parámetros
        $stmt->bindParam(":fecha_cita", $data['fecha_cita']);
        $stmt->bindParam(":hora_cita", $data['hora_cita']);
        $stmt->bindParam(":estado_cita", $data['estado_cita']);
        $stmt->bindParam(":id_paciente", $paciente['id_paciente']);
        $stmt->bindParam(":id_servicio", $servicio['id_servicio']);
        $stmt->bindParam(":id_usuario", $usuario['id_usuario']);



        // Ejecutar la consulta
        return $stmt->execute();
    }

    public function listarCitasPorUsuario($nombre_usuario): mixed
    {
        try {
            $query = "SELECT 
                    Cita.id_cita,
                    Cita.fecha_cita,
                    Cita.hora_cita,
                    Cita.estado_cita,
                    Paciente.nombre_paciente,
                    Servicio.nombre_servicio,
                    Servicio.costo_servicio,
                    Usuario.nombre_usuario
                FROM Cita
                INNER JOIN Paciente ON Cita.id_paciente = Paciente.id_paciente
                INNER JOIN Servicio ON Cita.id_servicio = Servicio.id_servicio
                INNER JOIN Usuario ON Cita.id_usuario = Usuario.id_usuario
                WHERE Usuario.nombre_usuario = :nombre_usuario";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            return null; // Maneja los errores adecuadamente
        }
    }

    public function modificarCita($id_cita, $data)
    {
        // Validar que se pasaron los datos necesarios
        if (!isset($data['nombre_paciente'], $data['nombre_servicio'], $data['fecha_cita'], $data['hora_cita'], $data['estado_cita'], $data['nombre_usuario'])) {
            throw new Exception("Faltan datos requeridos");
        }

        // Obtener el ID del paciente
        $queryPaciente = "SELECT id_paciente FROM Paciente WHERE nombre_paciente = :nombre_paciente LIMIT 1";
        $stmtPaciente = $this->conn->prepare($queryPaciente);
        $stmtPaciente->bindParam(":nombre_paciente", $data['nombre_paciente']);
        $stmtPaciente->execute();
        $paciente = $stmtPaciente->fetch(PDO::FETCH_ASSOC);

        if (!$paciente) {
            throw new Exception("Paciente no encontrado");
        }

        // Obtener el ID del usuario
        $queryUsuario = "SELECT id_usuario FROM Usuario WHERE nombre_usuario = :nombre_usuario LIMIT 1";
        $stmtUsuario = $this->conn->prepare($queryUsuario);
        $stmtUsuario->bindParam(":nombre_usuario", $data['nombre_usuario']);
        $stmtUsuario->execute();
        $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            throw new Exception("Usuario no encontrado");
        }

        // Obtener el ID del servicio
        $queryServicio = "SELECT id_servicio FROM Servicio WHERE nombre_servicio = :nombre_servicio LIMIT 1";
        $stmtServicio = $this->conn->prepare($queryServicio);
        $stmtServicio->bindParam(":nombre_servicio", $data['nombre_servicio']);
        $stmtServicio->execute();
        $servicio = $stmtServicio->fetch(PDO::FETCH_ASSOC);

        if (!$servicio) {
            throw new Exception("Servicio no encontrado");
        }

        // Actualizar la cita con los nuevos datos
        $query = "UPDATE Cita 
              SET fecha_cita = :fecha_cita, hora_cita = :hora_cita, estado_cita = :estado_cita, id_paciente = :id_paciente, id_servicio = :id_servicio, id_usuario = :id_usuario
              WHERE id_cita = :id_cita";
        $stmt = $this->conn->prepare($query);

        // Bindear los parámetros
        $stmt->bindParam(":fecha_cita", $data['fecha_cita']);
        $stmt->bindParam(":hora_cita", $data['hora_cita']);
        $stmt->bindParam(":estado_cita", $data['estado_cita']);
        $stmt->bindParam(":id_paciente", $paciente['id_paciente']);
        $stmt->bindParam(":id_servicio", $servicio['id_servicio']);
        $stmt->bindParam(":id_usuario", $usuario['id_usuario']);
        $stmt->bindParam(":id_cita", $id_cita, PDO::PARAM_INT);

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            throw new Exception("Error al modificar la cita");
        }
        return true;
    }

}
?>