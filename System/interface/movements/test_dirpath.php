<?php
// Test para verificar rutas
echo "<pre>";
echo "DIR PATH TEST\n";
echo "================================\n";
echo "__DIR__ = " . __DIR__ . "\n";
echo "Bootstrap correcto = " . __DIR__ . '/../../bootstrap.php' . "\n";
echo "Bootstrap incorrecto = " . __DIR__ . '/../../../bootstrap.php' . "\n\n";

// Verificar si existen
echo "Archivos:\n";
echo "Existe ../../bootstrap.php? " . (file_exists(__DIR__ . '/../../bootstrap.php') ? 'SÍ ✓' : 'NO ✗') . "\n";
echo "Existe ../../../bootstrap.php? " . (file_exists(__DIR__ . '/../../../bootstrap.php') ? 'SÍ ✓' : 'NO ✗') . "\n\n";

// Verificar inventorio
if (file_exists(__DIR__ . '/../../bootstrap.php')) {
    require_once __DIR__ . '/../../bootstrap.php';
    
    echo "Base de datos diagnostics:\n";
    echo "==========================\n";
    
    // Verificar si hay productos
    $productos = db()->select("SELECT COUNT(*) as total FROM productos");
    echo "Productos en DB: " . $productos[0]['total'] . "\n";
    
    // Verificar si hay lotes
    $lotes = db()->select("SELECT COUNT(*) as total FROM lotes");
    echo "Lotes en DB: " . $lotes[0]['total'] . "\n";
    
    // Verificar si hay inventario
    $inventario = db()->select("SELECT COUNT(*) as total FROM inventario");
    echo "Registros de inventario: " . $inventario[0]['total'] . "\n";
    
    // Verificar inventario con disponibilidad > 0
    $disponibles = db()->select("
        SELECT COUNT(*) as total FROM inventario 
        WHERE (CANTIDAD_TOTAL - CANTIDAD_RESERVADA) > 0
    ");
    echo "Inventario con disponibilidad > 0: " . $disponibles[0]['total'] . "\n\n";
    
    // Probar API manualmente
    echo "TEST API - Producto ID 3:\n";
    echo "==========================\n";
    
    $id_producto = 3;
    $lotes_test = db()->select("
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
    
    echo "Lotes encontrados: " . count($lotes_test) . "\n";
    if (!empty($lotes_test)) {
        foreach ($lotes_test as $lote) {
            echo "  - Lote: " . $lote['CODIGO_LOTE'] . " (ID: " . $lote['ID_LOTE'] . ")\n";
        }
    }
}

echo "</pre>";
?>
