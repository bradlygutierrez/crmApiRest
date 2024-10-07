<?php
require_once __DIR__ . '/../models/reports.php';

class GestorReportesController {
    public function generarReporteVentas() {
        $gestorReportes = new GestorReportes();
        $result = $gestorReportes->generarReporteVentas();
        echo json_encode($result);
    }

    public function generarReporteClientes() {
        $gestorReportes = new GestorReportes();
        $result = $gestorReportes->generarReporteClientes();
        echo json_encode($result);
    }

    public function generarReporteInventario() {
        $gestorReportes = new GestorReportes();
        $result = $gestorReportes->generarReporteInventario();
        echo json_encode($result);
    }
}
?>
