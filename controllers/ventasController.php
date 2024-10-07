<?php
require_once __DIR__ . '/../models/ventas.php';

class VentaController {
    public function listarVentas() {
        $venta = new Venta();
        $stms = $venta->listarVentas();
        $result = $stms->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    }

    public function registrarVenta($data) {
        $venta = new Venta();
        $venta->registrarVenta($data);
        echo json_encode(["message" => "Venta registrada"]);
    }
}
?>
