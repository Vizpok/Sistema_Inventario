<?php
/**
 * API - Obtener ubicaciones de un producto/lote
 * Retorna las ubicaciones donde existe inventario para un producto-lote específico
 */

require_once __DIR__ . '/../../../bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
    $id_lote = isset($_POST['id_lote']) ? (int)$_POST['id_lote'] : 0;

    if ($id_producto <= 0 || $id_lote <= 0) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'ubicaciones' => []], JSON_UNESCAPED_UNICODE));
    }

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

    exit(json_encode(['success' => true, 'ubicaciones' => $ubicaciones ?: []], JSON_UNESCAPED_UNICODE));
} catch (Throwable $e) {
    http_response_code(500);
    exit(json_encode(['success' => false, 'error' => $e->getMessage(), 'ubicaciones' => []], JSON_UNESCAPED_UNICODE));
}


