<?php
require_once __DIR__ . '/../config/Database.php';  // Asegúrate de ajustar la ruta

class Paciente
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarPacientes(): mixed
    {
        $query = "SELECT * FROM Paciente";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function registrarPaciente($data)
    {
        $query = "INSERT INTO Paciente (nombre_paciente, fecha_nacimiento, email_paciente, telefono_paciente, fecha_registro, historial_medico, nota_paciente) 
                  VALUES (:nombre_paciente, :fecha_nacimiento, :email_paciente, :telefono_paciente, :fecha_registro, :historial_medico, :nota_paciente)";
        $stmt = $this->conn->prepare($query);

        // Binding de parámetros
        $stmt->bindParam(":nombre_paciente", $data['nombre_paciente']);
        $stmt->bindParam(":fecha_nacimiento", $data['fecha_nacimiento']);
        $stmt->bindParam(":email_paciente", $data['email_paciente']);
        $stmt->bindParam(":telefono_paciente", $data['telefono_paciente']);
        $stmt->bindParam(":fecha_registro", $data['fecha_registro']);
        $stmt->bindParam(":historial_medico", $data['historial_medico']);
        $stmt->bindParam(":nota_paciente", $data['nota_paciente']);

        return $stmt->execute();
    }
    public function contarPacientesMesActual(): int
    {
        $currentMonth = date('m'); // Mes actual
        $currentYear = date('Y');  // Año actual

        // Consulta SQL para contar pacientes registrados en el mes actual
        $query = "SELECT COUNT(*) AS total FROM Paciente 
                  WHERE MONTH(fecha_registro) = :currentMonth 
                  AND YEAR(fecha_registro) = :currentYear";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":currentMonth", $currentMonth, PDO::PARAM_INT);
        $stmt->bindParam(":currentYear", $currentYear, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int) $result['total']; // Retorna el total de pacientes
    }
    public function contarPacientes(): int
    {
        $query = "SELECT COUNT(*) AS total_pacientes FROM Paciente"; // Ajusta el nombre de la tabla si es necesario
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Retorna el número total de pacientes
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total_pacientes']; // Devuelve el conteo
    }

    public function editarPaciente($id_paciente, $data)
    {
        $query = "UPDATE Paciente 
              SET nombre_paciente = :nombre_paciente, 
                  fecha_nacimiento = :fecha_nacimiento, 
                  email_paciente = :email_paciente, 
                  telefono_paciente = :telefono_paciente, 
                  fecha_registro = :fecha_registro, 
                  historial_medico = :historial_medico, 
                  nota_paciente = :nota_paciente 
              WHERE id_paciente = :id_paciente";

        $stmt = $this->conn->prepare($query);

        // Binding de parámetros
        $stmt->bindParam(":nombre_paciente", $data['nombre_paciente']);
        $stmt->bindParam(":fecha_nacimiento", $data['fecha_nacimiento']);
        $stmt->bindParam(":email_paciente", $data['email_paciente']);
        $stmt->bindParam(":telefono_paciente", $data['telefono_paciente']);
        $stmt->bindParam(":fecha_registro", $data['fecha_registro']);
        $stmt->bindParam(":historial_medico", $data['historial_medico']);
        $stmt->bindParam(":nota_paciente", $data['nota_paciente']);
        $stmt->bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);  // Asignamos el ID del paciente a actualizar

        return $stmt->execute();
    }

    // Métodos para actualizar, obtener y listar...
}
?>