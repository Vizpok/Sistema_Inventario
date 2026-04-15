<?php
/**
 * Inventario - Eliminar
 * Elimina un producto del inventario
 */
require_once __DIR__ . '/../../../bootstrap.php';
requireAuth();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    $_SESSION['alert'] = [
        'message' => 'ID de inventario inválido.',
        'type' => 'error'
    ];
    header('Location: inventario.php');
    exit;
}

// Obtener información del inventario
$item = db()->select("SELECT i.ID_INVENTARIO, p.NOMBRE, p.SKU FROM inventario i LEFT JOIN productos p ON i.ID_PRODUCTO = p.ID_PRODUCTO WHERE i.ID_INVENTARIO = $id");
$item = $item[0] ?? null;

if (!$item) {
    $_SESSION['alert'] = [
        'message' => 'Registro de inventario no encontrado.',
        'type' => 'error'
    ];
    header('Location: inventario.php');
    exit;
}

// Eliminar el registro del inventario
db()->execute("DELETE FROM inventario WHERE ID_INVENTARIO = $id");

$_SESSION['alert'] = [
    'message' => 'Producto "' . $item['NOMBRE'] . '" eliminado del inventario correctamente.',
    'type' => 'success'
];

header('Location: inventario.php');
exit;
?>
