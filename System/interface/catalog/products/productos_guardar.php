<?php
/**
 * Catálogo de Productos - Guardar
 * Procesa la creación de un nuevo producto
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
    redirect('productos_nuevo.php');
}

// Obtener y validar datos
$sku = isset($_POST['sku']) ? sanitize($_POST['sku']) : '';
$codigo_barras = isset($_POST['codigo_barras']) ? sanitize($_POST['codigo_barras']) : null;
$nombre = isset($_POST['nombre']) ? sanitize($_POST['nombre']) : '';
$id_categoria = isset($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : 0;
$precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0;
$stock_minimo = isset($_POST['stock_minimo']) ? (int)$_POST['stock_minimo'] : 10;
$cantidad_disponible = isset($_POST['cantidad_disponible']) ? (int)$_POST['cantidad_disponible'] : 0;
$id_ubicacion = isset($_POST['id_ubicacion']) ? (int)$_POST['id_ubicacion'] : 1;

// Validaciones
$errores = [];

if (empty($sku)) {
    $errores[] = 'El SKU es requerido';
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

// Verificar que el SKU sea único
$sku_exist = db()->select("SELECT ID_PRODUCTO FROM productos WHERE SKU = '" . db()->escape($sku) . "' LIMIT 1");
if (count($sku_exist) > 0) {
    $errores[] = 'Ya existe un producto con este SKU';
}

// Si hay errores, redirigir con mensaje
if (count($errores) > 0) {
    showAlert(implode('. ', $errores), 'error');
    redirect('productos_nuevo.php');
}

// Insertar producto
$query = "
    INSERT INTO productos (ID_CATEGORIA, SKU, CODIGO_BARRAS, NOMBRE, PRECIO, STOCK_MINIMO)
    VALUES (
        $id_categoria,
        '" . db()->escape($sku) . "',
        " . ($codigo_barras ? "'" . db()->escape($codigo_barras) . "'" : "NULL") . ",
        '" . db()->escape($nombre) . "',
        $precio,
        $stock_minimo
    )
";

if (db()->execute($query)) {
    $id_producto = db()->lastInsertId();
    // Guardar en inventario
    $id_lote = 1;
    $cantidad_total = $cantidad_disponible;
    db()->execute("INSERT INTO inventario (ID_PRODUCTO, ID_LOTE, ID_UBICACION, CANTIDAD_TOTAL, CANTIDAD_RESERVADA) VALUES ($id_producto, $id_lote, $id_ubicacion, $cantidad_total, 0)");
    showAlert('Producto creado exitosamente. ID: ' . $id_producto, 'success');
    redirect('productos.php');
} else {
    showAlert('Error al crear el producto. Intenta nuevamente.', 'error');
    redirect('productos_nuevo.php');
}
