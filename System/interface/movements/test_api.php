<?php
/**
 * Test API - Verificar api_lotes.php
 */

require_once __DIR__ . '/../../bootstrap.php';

echo "<h1>Test API Lotes</h1>";
echo "<pre>";

// Simular lo que hace el JavaScript
$id_producto = 3; // Mouse Óptico Inalámbrico

echo "Probando con ID_PRODUCTO = 3 (Mouse Óptico)\n\n";

// Verificar que el producto existe
$producto = db()->select("SELECT ID_PRODUCTO, NOMBRE, SKU FROM productos WHERE ID_PRODUCTO = $id_producto LIMIT 1");
if (empty($producto)) {
    echo "✗ Producto 3 no encontrado en BD\n";
} else {
    echo "✓ Producto encontrado: {$producto[0]['NOMBRE']} ({$producto[0]['SKU']})\n";
}

// Ejecutar la consulta exacta del api_lotes.php
echo "\nEjecutando consulta de lotes...\n";
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

if (empty($lotes)) {
    echo "⚠️ No hay lotes encontrados\n";
    echo "\nVerificando por qué no hay lotes...\n";
    
    // Verificar si hay inventario para este producto
    $inv = db()->select("SELECT COUNT(*) as total FROM inventario WHERE ID_PRODUCTO = $id_producto");
    echo "Records en inventario para producto 3: {$inv[0]['total']}\n";
    
    // Verificar si hay lotes ligados a ese inventario
    $inv_details = db()->select("SELECT ID_INVENTARIO, ID_LOTE, CANTIDAD_TOTAL, CANTIDAD_RESERVADA FROM inventario WHERE ID_PRODUCTO = $id_producto");
    echo "\nDetalles de inventario:\n";
    foreach ($inv_details as $inv_d) {
        $disponible = $inv_d['CANTIDAD_TOTAL'] - $inv_d['CANTIDAD_RESERVADA'];
        echo "  Lote {$inv_d['ID_LOTE']}: Total {$inv_d['CANTIDAD_TOTAL']}, Reservada {$inv_d['CANTIDAD_RESERVADA']}, Disponible $disponible\n";
    }
} else {
    echo "✓ Se encontraron " . count($lotes) . " lote(s):\n";
    foreach ($lotes as $l) {
        echo "  - {$l['CODIGO_LOTE']} (ID: {$l['ID_LOTE']}, Vencimiento: {$l['FECHA_VENCIMIENTO']})\n";
    }
}

// Mostrar JSON que devolvería el API
echo "\n\nRespuesta JSON que devolvería api_lotes.php:\n";
$response = ['success' => !empty($lotes), 'lotes' => $lotes ?: [], 'error' => null];
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

echo "\n</pre>";
echo "<hr>";
echo "<p><a href='movimiento_nuevo.php'>Volver al formulario</a> | ";
echo "<a href='diagnostico.php'>Ver diagn&oacute;stico completo</a></p>";
?>