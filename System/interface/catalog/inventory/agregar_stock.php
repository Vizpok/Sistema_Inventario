<?php
require_once __DIR__ . '/../../../bootstrap.php';
header('Content-Type: application/json');

$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;

if ($id_producto <= 0 || $cantidad <= 0) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

$actual = db()->select("SELECT CANTIDAD_TOTAL FROM inventario WHERE ID_PRODUCTO = $id_producto LIMIT 1");
if ($actual && count($actual) > 0) {
    $nuevo = (int)$actual[0]['CANTIDAD_TOTAL'] + $cantidad;
    db()->execute("UPDATE inventario SET CANTIDAD_TOTAL = $nuevo WHERE ID_PRODUCTO = $id_producto");
    echo json_encode(['success' => true, 'nuevo_stock' => $nuevo]);
} else {
    // No tiene registro en inventario, crear uno nuevo
    db()->execute("INSERT INTO inventario (ID_PRODUCTO, ID_LOTE, ID_UBICACION, CANTIDAD_TOTAL, CANTIDAD_RESERVADA) VALUES ($id_producto, 1, 1, $cantidad, 0)");
    echo json_encode(['success' => true, 'nuevo_stock' => $cantidad]);
}
