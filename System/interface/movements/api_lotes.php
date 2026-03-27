<?php
/**
 * API - Obtener lotes de un producto
 * Retorna los lotes disponibles para un producto específico
 */

require_once __DIR__ . '/../../../bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;

    if ($id_producto <= 0) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'lotes' => []], JSON_UNESCAPED_UNICODE));
    }

    $lotes = db()->select("
        SELECT DISTINCT
            l.ID_LOTE,
            l.CODIGO_LOTE,
            l.FECHA_VENCIMIENTO
        FROM lotes l
        INNER JOIN inventario i ON l.ID_LOTE = i.ID_LOTE
        WHERE l.ID_PRODUCTO = $id_producto
        AND (i.CANTIDAD_TOTAL - i.CANTIDAD_RESERVADA) > 0
        ORDER BY l.FECHA_VENCIMIENTO ASC
    ");

    exit(json_encode(['success' => true, 'lotes' => $lotes ?: []], JSON_UNESCAPED_UNICODE));
} catch (Throwable $e) {
    http_response_code(500);
    exit(json_encode(['success' => false, 'error' => $e->getMessage(), 'lotes' => []], JSON_UNESCAPED_UNICODE));
}


