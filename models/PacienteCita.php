<?php
require_once __DIR__ . '/../config/Database.php';

class PacienteCita
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerPacientesYCitasMesActual()
    {
        // Consulta para obtener el total de pacientes
        $queryTotalPacientes = "SELECT COUNT(*) as totalPacientes FROM Paciente";
        $stmtTotal = $this->conn->prepare($queryTotalPacientes);
        $stmtTotal->execute();
        $totalPacientes = $stmtTotal->fetch(PDO::FETCH_ASSOC)['totalPacientes'];

        // Consulta para obtener el número de pacientes con al menos una cita en el mes actual
        $queryPacientesConCitaMes = "SELECT COUNT(DISTINCT id_paciente) as pacientesConCita
            FROM Cita 
            WHERE estado_cita = 'confirmada'
            AND MONTH(fecha_cita) = MONTH(CURDATE())
            AND YEAR(fecha_cita) = YEAR(CURDATE())";
        $stmtCitaMes = $this->conn->prepare($queryPacientesConCitaMes);
        $stmtCitaMes->execute();
        $pacientesConCitaMes = $stmtCitaMes->fetch(PDO::FETCH_ASSOC)['pacientesConCita'];

        // Calcular pacientes sin cita
        $pacientesSinCitaMes = $totalPacientes - $pacientesConCitaMes;

        // Devolver los resultados
        return [
            'totalPacientes' => $totalPacientes,
            'pacientesConCitaMes' => $pacientesConCitaMes,
            'pacientesSinCitaMes' => $pacientesSinCitaMes,
        ];
    }
}
?>
