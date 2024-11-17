<?php
require_once __DIR__ . '/../models/Paciente.php';
header('Content-Type: application/json');

class PacienteController
{
    public function __construct()
    {
    }

    // Método para contar pacientes del mes actual
    public function contarPacientesMesActual()
    {
        $paciente = new Paciente();
        $totalPacientesMes = $paciente->contarPacientesMesActual(); // Contamos pacientes del mes actual

        echo json_encode(["total_pacientes_mes" => $totalPacientesMes]); // Respondemos con el total en formato JSON
    }
    public function obtenerPacientesPendientes()
    {
        $paciente = new Paciente();
        $result = $paciente->obtenerPacientesPendientes();

        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay pacientes con chequeos pendientes"], JSON_UNESCAPED_UNICODE);
        }
    }
    public function listarPacientes()
    {
        $paciente = new Paciente();
        $stmt = $paciente->listarPacientes();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo json_encode($result, flags: JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay pacientes disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarPaciente($data)
    {
        try {
            $paciente = new Paciente();
            $paciente->registrarPaciente($data);
            echo json_encode(["message" => "Paciente y usuario registrados correctamente"]);
        } catch (Exception $e) {
            echo json_encode(["message" => "Error al registrar paciente o usuario", "error" => $e->getMessage()]);
        }
    }




    // Método para contar el número de pacientes
    public function contarPacientes()
    {
        $paciente = new Paciente();
        $totalPacientes = $paciente->contarPacientes(); // Llamamos al método contarPacientes del modelo

        echo json_encode(["total_pacientes" => $totalPacientes]); // Enviamos el resultado en formato JSON
    }

    // Método para actualizar un paciente
    public function actualizarPaciente($id_paciente, $data)
    {
        $paciente = new Paciente();
        if ($paciente->editarPaciente($id_paciente, $data)) {
            echo json_encode(["message" => "Paciente actualizado correctamente"]);
        } else {
            echo json_encode(["message" => "Error al actualizar paciente"]);
        }
    }
}
