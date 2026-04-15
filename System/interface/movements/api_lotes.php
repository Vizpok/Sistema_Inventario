<?php
/**
 * API - Obtener lotes de un producto
 * Retorna los lotes disponibles para un producto específico
 */

require_once __DIR__ . '/../../bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'lotes' => [], 'error' => null];

try {
    $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;

    if ($id_producto <= 0) {
        http_response_code(400);
        $response['error'] = 'ID de producto inválido';
        exit(json_encode($response, JSON_UNESCAPED_UNICODE));
    }

    // Verificar que el producto existe
    $producto = db()->select("SELECT ID_PRODUCTO FROM productos WHERE ID_PRODUCTO = $id_producto LIMIT 1");
    if (empty($producto)) {
        $response['error'] = 'Producto no encontrado';
        exit(json_encode($response, JSON_UNESCAPED_UNICODE));
    }

    // Obtener lotes con inventario disponible
    $lotes = db()->select("
        SELECT DISTINCT
            l.ID_LOTE,
            l.CODIGO_LOTE,
            l.FECHA_VENCIMIENTO
        FROM inventario i
        INNER JOIN lotes l ON i.ID_LOTE = l.ID_LOTE
        WHERE i.ID_PRODUCTO = $id_producto
        AND (i.CANTIDAD_TOTAL - i.CANTIDAD_RESERVADA) > 0
        ORDER BY l.FECHA_VENCIMIENTO ASC
    ");

    $response['success'] = true;
    $response['lotes'] = $lotes ?: [];
    
} catch (Throwable $e) {
    http_response_code(500);
    $response['error'] = $e->getMessage();
}

exit(json_encode($response, JSON_UNESCAPED_UNICODE));


