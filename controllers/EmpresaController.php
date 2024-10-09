<?php
require_once __DIR__ . '/../models/Empresa.php';

class EmpresaController {
    public function listarEmpresas() {
        $empresa = new Empresa();
        $stmt = $empresa->listarEmpresas();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay empresas disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarEmpresa($data) {
        $empresa = new Empresa();
        $empresa->registrarEmpresa($data);
        echo json_encode(["message" => "Empresa registrada"]);
    }
}
?>
