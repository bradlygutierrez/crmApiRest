<?php
require_once __DIR__ . '/../config/Database.php';

class PacienteRegistro
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerPacientesPorMes()
    {
        // Consulta para obtener el número de pacientes registrados por mes
        $query = "
            SELECT 
                YEAR(fecha_registro) AS year,
                MONTH(fecha_registro) AS month,
                COUNT(*) AS totalPacientes
            FROM Paciente
            GROUP BY YEAR(fecha_registro), MONTH(fecha_registro)
            ORDER BY year DESC, month DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
