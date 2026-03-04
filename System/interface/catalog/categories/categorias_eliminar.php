<?php
/**
 * Catálogo de Categorías - Eliminar
 * Procesa la eliminación de una categoría
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Obtener ID de la categoría
$id_categoria = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_categoria <= 0) {
    showAlert('Categoría no encontrada', 'error');
    redirect('categorias.php');
}

// Verificar que la categoría existe
$categorias = db()->select("SELECT ID_CATEGORIA FROM categorias WHERE ID_CATEGORIA = $id_categoria LIMIT 1");
if (count($categorias) === 0) {
    showAlert('La categoría no existe', 'error');
    redirect('categorias.php');
}

// Verificar si hay productos asociados
$productos = db()->select("SELECT COUNT(*) as total FROM productos WHERE ID_CATEGORIA = $id_categoria");
$tiene_productos = ($productos[0]['total'] ?? 0) > 0;

if ($tiene_productos) {
    showAlert('No se puede eliminar la categoría porque tiene productos asociados', 'error');
    redirect('categorias.php');
}

// Eliminar categoría
$query = "DELETE FROM categorias WHERE ID_CATEGORIA = $id_categoria";

if (db()->execute($query)) {
    showAlert('Categoría eliminada exitosamente', 'success');
    redirect('categorias.php');
} else {
    showAlert('Error al eliminar la categoría. Intenta nuevamente.', 'error');
    redirect('categorias.php');
}
