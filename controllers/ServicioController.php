<?php
require_once __DIR__ . '/../models/Servicio.php';

class ServicioController {
    public function listarServicios() {
        $servicio = new Servicio();
        $stmt = $servicio->listarServicios();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay servicios disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarServicio($data) {
        $servicio = new Servicio();
        $servicio->registrarServicio($data);
        echo json_encode(["message" => "Servicio registrado"]);
    }
}
?>
