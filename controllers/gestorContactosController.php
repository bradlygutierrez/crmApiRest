<?php

require_once '../models/gestorContactos.php';
require_once '../config/Database.php';

class GestorContactosController {
    
    // Programar seguimiento a un cliente
    public function programarSeguimiento() {
        // Obtener los datos de la solicitud POST
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!empty($data['id_cliente']) && !empty($data['fecha_seguimiento']) && !empty($data['nota_seguimiento'])) {
            $gestor = new GestorContactos();
            $response = $gestor->programarSeguimiento($data);
            echo json_encode($response);
        } else {
            echo json_encode(["status" => "error", "message" => "Datos incompletos para programar seguimiento."]);
        }
    }

    // Enviar un correo masivo
    public function enviarCorreoMasivo() {
        // Obtener los datos de la solicitud POST
        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data['asunto']) && !empty($data['mensaje'])) {
            $gestor = new GestorContactos();
            $response = $gestor->enviarCorreoMasivo($data);
            echo json_encode($response);
        } else {
            echo json_encode(["status" => "error", "message" => "Datos incompletos para enviar correo masivo."]);
        }
    }

    // Gestionar una campaña
    public function gestionarCampania() {
        // Obtener los datos de la solicitud POST
        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data['nombre_campania']) && !empty($data['descripcion']) && !empty($data['fecha_inicio']) && !empty($data['fecha_fin'])) {
            $gestor = new GestorContactos();
            $response = $gestor->gestionarCampania($data);
            echo json_encode($response);
        } else {
            echo json_encode(["status" => "error", "message" => "Datos incompletos para gestionar campaña."]);
        }
    }
}

?>
