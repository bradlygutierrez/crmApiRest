<?php
require_once __DIR__ . '/../models/Paciente.php';  
header('Content-Type: application/json');

class PacienteController
{
    public function __construct() {}

    public function listarPacientes()
    {
        $paciente = new Paciente();
        $stmt = $paciente->listarPacientes();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay pacientes disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarPaciente($data)
    {
        $paciente = new Paciente();
        if ($paciente->registrarPaciente($data)) {
            echo json_encode(["message" => "Paciente registrado correctamente"]);
        } else {
            echo json_encode(["message" => "Error al registrar paciente"]);
        }
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
