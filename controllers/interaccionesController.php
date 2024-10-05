<?php
require_once '../models/interacciones.php';

class InteraccionController {
    public function listarInteracciones() {
        $interaccion = new Interaccion();
        $result = $interaccion->listarInteracciones();
        echo json_encode($result);
    }

    public function registrarInteraccion($data) {
        $interaccion = new Interaccion();
        $interaccion->registrarInteraccion($data);
        echo json_encode(["message" => "Interacción registrada"]);
    }
}
?>
