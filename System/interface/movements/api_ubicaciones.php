<?php
/**
 * API - Obtener ubicaciones de un producto/lote
 * Retorna las ubicaciones donde existe inventario para un producto-lote específico
 */

require_once __DIR__ . '/../../bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'ubicaciones' => [], 'error' => null];

try {
    $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
    $id_lote = isset($_POST['id_lote']) ? (int)$_POST['id_lote'] : 0;

    if ($id_producto <= 0 || $id_lote <= 0) {
        http_response_code(400);
        $response['error'] = 'Parámetros inválidos';
        exit(json_encode($response, JSON_UNESCAPED_UNICODE));
    }

    // Verificar que el producto y lote existen
    $producto = db()->select("SELECT ID_PRODUCTO FROM productos WHERE ID_PRODUCTO = $id_producto LIMIT 1");
    if (empty($producto)) {
        $response['error'] = 'Producto no encontrado';
        exit(json_encode($response, JSON_UNESCAPED_UNICODE));
    }

    $lote = db()->select("SELECT ID_LOTE FROM lotes WHERE ID_LOTE = $id_lote LIMIT 1");
    if (empty($lote)) {
        $response['error'] = 'Lote no encontrado';
        exit(json_encode($response, JSON_UNESCAPED_UNICODE));
    }

    // Obtener ubicaciones con inventario disponible
    $ubicaciones = db()->select("
        SELECT 
            u.ID_UBICACION,
            u.CODIGO_UBICACION,
            i.CANTIDAD_TOTAL,
            i.CANTIDAD_RESERVADA,
            (i.CANTIDAD_TOTAL - i.CANTIDAD_RESERVADA) as CANTIDAD_DISPONIBLE
        FROM inventario i
        INNER JOIN ubicaciones u ON i.ID_UBICACION = u.ID_UBICACION
        WHERE i.ID_PRODUCTO = $id_producto 
            AND i.ID_LOTE = $id_lote
            AND (i.CANTIDAD_TOTAL - i.CANTIDAD_RESERVADA) > 0
        ORDER BY u.CODIGO_UBICACION ASC
    ");

    $response['success'] = true;
    $response['ubicaciones'] = $ubicaciones ?: [];

} catch (Throwable $e) {
    http_response_code(500);
    $response['error'] = $e->getMessage();
}

exit(json_encode($response, JSON_UNESCAPED_UNICODE));


