<?php
require_once __DIR__ . '/../models/interacciones.php';

class InteraccionController {
    public function listarInteracciones() {
        $interaccion = new Interaccion();
        $stmt = $interaccion ->listarInteracciones();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    }

    public function registrarInteraccion($data) {
        $interaccion = new Interaccion();
        $interaccion->registrarInteraccion($data);
        echo json_encode(["message" => "Interacción registrada"]);
    }
}
?>
