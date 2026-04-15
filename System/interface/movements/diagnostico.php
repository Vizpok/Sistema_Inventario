<?php
/**
 * Diagnóstico - Verificar conexión y datos de BD
 */

require_once __DIR__ . '/../../bootstrap.php';

echo "<h1>Diagnóstico de Sistema de Inventario</h1>";
echo "<pre>";

// Verificar conexión a BD
try {
    echo "✓ Conexión a BD: OK\n\n";
} catch (Exception $e) {
    echo "✗ Error de conexión: " . $e->getMessage() . "\n";
    exit;
}

// Verificar tabla productos
echo "=== PRODUCTOS ===\n";
$productos = db()->select("SELECT ID_PRODUCTO, NOMBRE, SKU FROM productos ORDER BY ID_PRODUCTO");
foreach ($productos as $p) {
    echo "ID: {$p['ID_PRODUCTO']}, SKU: {$p['SKU']}, NOMBRE: {$p['NOMBRE']}\n";
}

// Verificar tabla lotes
echo "\n=== LOTES (Primeros 5) ===\n";
$lotes = db()->select("SELECT ID_LOTE, ID_PRODUCTO, CODIGO_LOTE, FECHA_VENCIMIENTO FROM lotes LIMIT 5");
foreach ($lotes as $l) {
    echo "ID: {$l['ID_LOTE']}, Producto: {$l['ID_PRODUCTO']}, Código: {$l['CODIGO_LOTE']}, Venc: {$l['FECHA_VENCIMIENTO']}\n";
}

// Verificar tabla inventario
echo "\n=== INVENTARIO (Primeros 5) ===\n";
$inventario = db()->select("SELECT ID_INVENTARIO, ID_PRODUCTO, ID_LOTE, ID_UBICACION, CANTIDAD_TOTAL, CANTIDAD_RESERVADA FROM inventario LIMIT 5");
foreach ($inventario as $inv) {
    echo "ID: {$inv['ID_INVENTARIO']}, Producto: {$inv['ID_PRODUCTO']}, Lote: {$inv['ID_LOTE']}, Ubicación: {$inv['ID_UBICACION']}, Total: {$inv['CANTIDAD_TOTAL']}, Reservada: {$inv['CANTIDAD_RESERVADA']}\n";
}

// Verificar lotes del producto 3 (Mouse)
echo "\n=== LOTES PARA PRODUCTO 3 (Mouse) ===\n";
$lotesProducto3 = db()->select("
    SELECT DISTINCT
        l.ID_LOTE,
        l.CODIGO_LOTE,
        l.FECHA_VENCIMIENTO
    FROM inventario i
    INNER JOIN lotes l ON i.ID_LOTE = l.ID_LOTE
    WHERE i.ID_PRODUCTO = 3
    AND (i.CANTIDAD_TOTAL - i.CANTIDAD_RESERVADA) > 0
    ORDER BY l.FECHA_VENCIMIENTO ASC
");
if (empty($lotesProducto3)) {
    echo "⚠️ No hay lotes encontrados para Producto 3\n";
} else {
    foreach ($lotesProducto3 as $l) {
        echo "ID: {$l['ID_LOTE']}, Código: {$l['CODIGO_LOTE']}, Venc: {$l['FECHA_VENCIMIENTO']}\n";
    }
}

// Verificar ubicaciones del lote 3
echo "\n=== UBICACIONES PARA PRODUCTO 3, LOTE 3 ===\n";
$ubicaciones = db()->select("
    SELECT 
        u.ID_UBICACION,
        u.CODIGO_UBICACION,
        i.CANTIDAD_TOTAL,
        i.CANTIDAD_RESERVADA,
        (i.CANTIDAD_TOTAL - i.CANTIDAD_RESERVADA) as CANTIDAD_DISPONIBLE
    FROM inventario i
    INNER JOIN ubicaciones u ON i.ID_UBICACION = u.ID_UBICACION
    WHERE i.ID_PRODUCTO = 3
        AND i.ID_LOTE = 3
        AND (i.CANTIDAD_TOTAL - i.CANTIDAD_RESERVADA) > 0
    ORDER BY u.CODIGO_UBICACION ASC
");
if (empty($ubicaciones)) {
    echo "⚠️ No hay ubicaciones encontradas\n";
} else {
    foreach ($ubicaciones as $u) {
        echo "ID: {$u['ID_UBICACION']}, Ubicación: {$u['CODIGO_UBICACION']}, Disponible: {$u['CANTIDAD_DISPONIBLE']}\n";
    }
}

echo "\n✓ Diagnóstico completado";
echo "\n</pre>";
echo "<hr>";
echo "<p><a href='movimiento_nuevo.php'>Volver al formulario</a></p>";
?>