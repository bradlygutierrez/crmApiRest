<?php
require_once '../models/productos.php';

class ProductoController {
    public function listarProductos() {
        $producto = new Producto();
        $stmt = $producto->listarProductos();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    }

    public function registrarProducto($data) {
        $producto = new Producto();
        $producto->registrarProducto($data);
        echo json_encode(["message" => "Producto registrado"]);
    }
}
?>
