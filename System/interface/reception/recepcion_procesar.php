<?php
/**
 * Recepción - Procesar Recepción
 * Procesa la recepción de productos al almacén
 */

require_once __DIR__ . '/../../bootstrap.php';

// Requerir autenticación
requireAuth();

$base_url = '/Sistema_Inventario';

// Solo acepta POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('recepcion.php');
}

// Verificar token CSRF
if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
    showAlert('Token de seguridad inválido. Intenta nuevamente.', 'error');
    redirect($base_url . '/System/interface/reception/recepcion.php');
}

// Obtener datos del formulario
$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
$id_proveedor = isset($_POST['id_proveedor']) ? (int)$_POST['id_proveedor'] : 0;
$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;
$precio_compra = isset($_POST['precio_compra']) ? (float)$_POST['precio_compra'] : 0.0;
$codigo_lote = isset($_POST['codigo_lote']) ? trim($_POST['codigo_lote']) : '';
$fecha_vencimiento = isset($_POST['fecha_vencimiento']) && !empty($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : null;
$id_ubicacion = isset($_POST['id_ubicacion']) ? (int)$_POST['id_ubicacion'] : 0;

// Validaciones
$errores = [];

if ($id_producto <= 0) {
    $errores[] = 'Debes seleccionar un producto válido';
}

if ($id_proveedor <= 0) {
    $errores[] = 'Debes seleccionar un proveedor válido';
}

if ($cantidad <= 0) {
    $errores[] = 'La cantidad debe ser mayor a 0';
}

if ($precio_compra < 0) {
    $errores[] = 'El precio de compra debe ser mayor o igual a 0';
}

if (empty($codigo_lote)) {
    $errores[] = 'Debes ingresar un código de lote válido';
}

if ($id_ubicacion <= 0) {
    $errores[] = 'Debes seleccionar una ubicación válida';
}

// Verificar que el producto existe
$producto = db()->select("SELECT ID_PRODUCTO FROM productos WHERE ID_PRODUCTO = $id_producto LIMIT 1");
if (count($producto) === 0) {
    $errores[] = 'El producto no existe';
}

// Verificar que el proveedor existe
$proveedor = db()->select("SELECT ID_PROVEEDOR FROM proveedores WHERE ID_PROVEEDOR = $id_proveedor LIMIT 1");
if (count($proveedor) === 0) {
    $errores[] = 'El proveedor no existe';
}

// Verificar que la ubicación existe
$ubicacion = db()->select("SELECT ID_UBICACION FROM ubicaciones WHERE ID_UBICACION = $id_ubicacion LIMIT 1");
if (count($ubicacion) === 0) {
    $errores[] = 'La ubicación no existe';
}

// Verificar que el código de lote no esté duplicado
$lote_existente = db()->select("SELECT ID_LOTE FROM lotes WHERE CODIGO_LOTE = '$codigo_lote' LIMIT 1");
if (count($lote_existente) > 0) {
    $errores[] = 'El código de lote ya existe. Debes usar un código único';
}

// Si hay errores, mostrar alerta y redirigir
if (count($errores) > 0) {
    showAlert(implode('. ', $errores), 'error');
    redirect($base_url . '/System/interface/reception/recepcion.php');
}

// ID del usuario actual
$id_usuario = $_SESSION['user_id'] ?? 0;

if ($id_usuario <= 0) {
    showAlert('Error de autenticación. Usuario no identificado.', 'error');
    redirect($base_url . '/System/interface/reception/recepcion.php');
}

// Iniciar transacción
db()->execute("START TRANSACTION");

try {
    // Crear nuevo lote
    $fecha_vencimiento_sql = $fecha_vencimiento ? "'$fecha_vencimiento'" : 'NULL';
    $query_lote = "
        INSERT INTO lotes (ID_PRODUCTO, ID_PROVEEDOR, CODIGO_LOTE, PRECIO_COMPRA, FECHA_VENCIMIENTO)
        VALUES ($id_producto, $id_proveedor, '$codigo_lote', $precio_compra, $fecha_vencimiento_sql)
    ";

    if (!db()->execute($query_lote)) {
        throw new Exception('Error al crear el lote');
    }

    // Obtener el ID del lote recién creado
    $id_lote = db()->lastInsertId();

    // Registrar movimiento en tabla movimientos
    $query_movimiento = "
        INSERT INTO movimientos (ID_PRODUCTO, ID_LOTE, ID_USUARIO, ID_UBICACION_DESTINO, TIPO_MOVIMIENTO, CANTIDAD)
        VALUES ($id_producto, $id_lote, $id_usuario, $id_ubicacion, 'RECEPCION', $cantidad)
    ";

    if (!db()->execute($query_movimiento)) {
        throw new Exception('Error al registrar el movimiento');
    }

    // Verificar si ya existe inventario para este producto, lote y ubicación
    $inventario_existente = db()->select("
        SELECT ID_INVENTARIO 
        FROM inventario 
        WHERE ID_PRODUCTO = $id_producto AND ID_LOTE = $id_lote AND ID_UBICACION = $id_ubicacion 
        LIMIT 1
    ");

    if (count($inventario_existente) > 0) {
        // Actualizar inventario existente
        $query_inventario = "
            UPDATE inventario 
            SET CANTIDAD_TOTAL = CANTIDAD_TOTAL + $cantidad 
            WHERE ID_PRODUCTO = $id_producto AND ID_LOTE = $id_lote AND ID_UBICACION = $id_ubicacion
        ";
    } else {
        // Crear nuevo registro de inventario
        $query_inventario = "
            INSERT INTO inventario (ID_PRODUCTO, ID_LOTE, ID_UBICACION, CANTIDAD_TOTAL, CANTIDAD_RESERVADA)
            VALUES ($id_producto, $id_lote, $id_ubicacion, $cantidad, 0)
        ";
    }

    if (!db()->execute($query_inventario)) {
        throw new Exception('Error al actualizar el inventario');
    }

    // Confirmar transacción
    db()->execute("COMMIT");

    showAlert('Recepción registrada exitosamente', 'success');
    redirect($base_url . '/System/interface/reception/recepcion.php');

} catch (Exception $e) {
    // Revertir transacción en caso de error
    db()->execute("ROLLBACK");
    showAlert('Error al procesar la recepción: ' . $e->getMessage(), 'error');
    redirect($base_url . '/System/interface/reception/recepcion.php');
}
?>