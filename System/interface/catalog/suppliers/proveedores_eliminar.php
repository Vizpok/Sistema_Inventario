<?php
/**
 * Catálogo de Proveedores - Eliminar
 * Procesa la eliminación de un proveedor
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Obtener ID del proveedor
$id_proveedor = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_proveedor <= 0) {
    showAlert('Proveedor no encontrado', 'error');
    redirect('proveedores.php');
}

// Verificar que el proveedor existe
$proveedores = db()->select("SELECT ID_PROVEEDOR FROM proveedores WHERE ID_PROVEEDOR = $id_proveedor LIMIT 1");
if (count($proveedores) === 0) {
    showAlert('El proveedor no existe', 'error');
    redirect('proveedores.php');
}

// Verificar si hay lotes asociados
$lotes = db()->select("SELECT COUNT(*) as total FROM lotes WHERE ID_PROVEEDOR = $id_proveedor");
$tiene_lotes = ($lotes[0]['total'] ?? 0) > 0;

if ($tiene_lotes) {
    showAlert('No se puede eliminar el proveedor porque tiene lotes registrados asociados', 'error');
    redirect('proveedores.php');
}

// Eliminar proveedor
$query = "DELETE FROM proveedores WHERE ID_PROVEEDOR = $id_proveedor";

if (db()->execute($query)) {
    showAlert('Proveedor eliminado exitosamente', 'success');
    redirect('proveedores.php');
} else {
    showAlert('Error al eliminar el proveedor. Intenta nuevamente.', 'error');
    redirect('proveedores.php');
}
