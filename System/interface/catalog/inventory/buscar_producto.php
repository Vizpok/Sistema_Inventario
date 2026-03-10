<?php
require_once __DIR__ . '/../../../bootstrap.php';
header('Content-Type: application/json');

$codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';
if ($codigo === '') {
    echo json_encode(['existe' => false]);
    exit;
}

$codigoEsc = db()->escape($codigo);
$producto = db()->select("SELECT p.ID_PRODUCTO, p.NOMBRE, p.SKU, COALESCE(i.CANTIDAD_TOTAL, 0) AS CANTIDAD_TOTAL FROM productos p LEFT JOIN inventario i ON p.ID_PRODUCTO = i.ID_PRODUCTO WHERE p.SKU = '$codigoEsc' OR p.CODIGO_BARRAS = '$codigoEsc' LIMIT 1");
if ($producto && count($producto) > 0) {
    echo json_encode([
        'existe' => true,
        'id_producto' => $producto[0]['ID_PRODUCTO'],
        'nombre' => $producto[0]['NOMBRE'],
        'sku' => $producto[0]['SKU'],
        'cantidad' => (int)$producto[0]['CANTIDAD_TOTAL']
    ]);
} else {
    echo json_encode(['existe' => false]);
}
