<?php
/**
 * Catálogo de Productos - Actualizar
 * Procesa la actualización de un producto existente
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Solo acepta POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('productos.php');
}

// Verificar token CSRF
if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
    showAlert('Token de seguridad inválido. Intenta nuevamente.', 'error');
    redirect('productos.php');
}

// Obtener y validar datos
$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
$codigo_barras = isset($_POST['codigo_barras']) ? sanitize($_POST['codigo_barras']) : null;
$nombre = isset($_POST['nombre']) ? sanitize($_POST['nombre']) : '';
$id_categoria = isset($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : 0;
$precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0;
$stock_minimo = isset($_POST['stock_minimo']) ? (int)$_POST['stock_minimo'] : 10;

// Validaciones
$errores = [];

if ($id_producto <= 0) {
    $errores[] = 'Producto inválido';
}

if (empty($nombre)) {
    $errores[] = 'El nombre del producto es requerido';
}

if ($id_categoria <= 0) {
    $errores[] = 'Debes seleccionar una categoría válida';
}

if ($precio <= 0) {
    $errores[] = 'El precio debe ser mayor a 0';
}

if ($stock_minimo < 1) {
    $errores[] = 'El stock mínimo debe ser mayor a 0';
}

// Verificar que el producto existe
$producto_exist = db()->select("SELECT ID_PRODUCTO FROM productos WHERE ID_PRODUCTO = $id_producto LIMIT 1");
if (count($producto_exist) === 0) {
    $errores[] = 'El producto no existe';
}

// Si hay errores, redirigir con mensaje
if (count($errores) > 0) {
    showAlert(implode('. ', $errores), 'error');
    redirect('productos_editar.php?id=' . $id_producto);
}

// Actualizar producto
$query = "
    UPDATE productos 
    SET 
        CODIGO_BARRAS = " . ($codigo_barras ? "'" . db()->escape($codigo_barras) . "'" : "NULL") . ",
        NOMBRE = '" . db()->escape($nombre) . "',
        ID_CATEGORIA = $id_categoria,
        PRECIO = $precio,
        STOCK_MINIMO = $stock_minimo
    WHERE ID_PRODUCTO = $id_producto
";

if (db()->execute($query)) {
    showAlert('Producto actualizado exitosamente', 'success');
    redirect('productos.php');
} else {
    showAlert('Error al actualizar el producto. Intenta nuevamente.', 'error');
    redirect('productos_editar.php?id=' . $id_producto);
}
