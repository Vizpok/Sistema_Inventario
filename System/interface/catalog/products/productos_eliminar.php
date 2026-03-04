<?php
/**
 * Catálogo de Productos - Eliminar
 * Procesa la eliminación de un producto
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Obtener ID del producto
$id_producto = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_producto <= 0) {
    showAlert('Producto no encontrado', 'error');
    redirect('productos.php');
}

// Verificar que el producto existe
$productos = db()->select("SELECT ID_PRODUCTO FROM productos WHERE ID_PRODUCTO = $id_producto LIMIT 1");
if (count($productos) === 0) {
    showAlert('El producto no existe', 'error');
    redirect('productos.php');
}

// Verificar si hay registros de inventario asociados
$inventario = db()->select("SELECT COUNT(*) as total FROM inventario WHERE ID_PRODUCTO = $id_producto");
$tiene_inventario = ($inventario[0]['total'] ?? 0) > 0;

if ($tiene_inventario) {
    showAlert('No se puede eliminar el producto porque tiene registros de inventario asociados', 'error');
    redirect('productos.php');
}

// Verificar si hay detalles de orden asociados
$ordenes = db()->select("SELECT COUNT(*) as total FROM detalle_orden WHERE ID_PRODUCTO = $id_producto");
$tiene_ordenes = ($ordenes[0]['total'] ?? 0) > 0;

if ($tiene_ordenes) {
    showAlert('No se puede eliminar el producto porque tiene órdenes de venta asociadas', 'error');
    redirect('productos.php');
}

// Eliminar producto
$query = "DELETE FROM productos WHERE ID_PRODUCTO = $id_producto";

if (db()->execute($query)) {
    showAlert('Producto eliminado exitosamente', 'success');
    redirect('productos.php');
} else {
    showAlert('Error al eliminar el producto. Intenta nuevamente.', 'error');
    redirect('productos.php');
}
