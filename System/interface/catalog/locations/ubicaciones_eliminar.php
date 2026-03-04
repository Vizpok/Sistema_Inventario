<?php
/**
 * Catálogo de Ubicaciones - Eliminar
 * Procesa la eliminación de una ubicación
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Obtener ID de la ubicación
$id_ubicacion = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_ubicacion <= 0) {
    showAlert('Ubicación no encontrada', 'error');
    redirect('ubicaciones.php');
}

// Verificar que la ubicación existe
$ubicaciones = db()->select("SELECT ID_UBICACION FROM ubicaciones WHERE ID_UBICACION = $id_ubicacion LIMIT 1");
if (count($ubicaciones) === 0) {
    showAlert('La ubicación no existe', 'error');
    redirect('ubicaciones.php');
}

// Verificar si hay inventario asociado
$inventario = db()->select("SELECT COUNT(*) as total FROM inventario WHERE ID_UBICACION = $id_ubicacion");
$tiene_inventario = ($inventario[0]['total'] ?? 0) > 0;

if ($tiene_inventario) {
    showAlert('No se puede eliminar la ubicación porque tiene inventario registrado', 'error');
    redirect('ubicaciones.php');
}

// Eliminar ubicación
$query = "DELETE FROM ubicaciones WHERE ID_UBICACION = $id_ubicacion";

if (db()->execute($query)) {
    showAlert('Ubicación eliminada exitosamente', 'success');
    redirect('ubicaciones.php');
} else {
    showAlert('Error al eliminar la ubicación. Intenta nuevamente.', 'error');
    redirect('ubicaciones.php');
}
