<?php
require_once __DIR__ . '/../models/tipoInteracciones.php';

class TipoInteraccionController {
    public function listarTiposInteraccion() {
        $tipoInteraccion = new TipoInteraccion();
        $result = $tipoInteraccion->listarTiposInteraccion();
        echo json_encode($result);
    }

    public function obtenerTipoInteraccion($id) {
        $tipoInteraccion = new TipoInteraccion();
        $result = $tipoInteraccion->obtenerTipoInteraccion($id);
        echo json_encode($result);
    }
}
?>
