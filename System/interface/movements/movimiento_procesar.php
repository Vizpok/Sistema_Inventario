<?php
/**
 * Movimientos - Procesar Transferencia
 * Procesa trasferencias de productos entre ubicaciones
 */

require_once __DIR__ . '/../../bootstrap.php';

// Requerir autenticación
requireAuth();

$base_url = '/Sistema_Inventario';

// Solo acepta POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('movimientos.php');
}

// Verificar token CSRF
if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
    showAlert('Token de seguridad inválido. Intenta nuevamente.', 'error');
    redirect($base_url . '/System/interface/movements/movimientos.php');
}

// Obtener datos del formulario
$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;
$id_ubicacion_origen = isset($_POST['id_ubicacion_origen']) ? (int)$_POST['id_ubicacion_origen'] : 0;
$id_ubicacion_destino = isset($_POST['id_ubicacion_destino']) ? (int)$_POST['id_ubicacion_destino'] : 0;
$id_lote = isset($_POST['id_lote']) ? (int)$_POST['id_lote'] : 0;

// Validaciones
$errores = [];

if ($id_producto <= 0) {
    $errores[] = 'Debes seleccionar un producto válido';
}

if ($cantidad <= 0) {
    $errores[] = 'La cantidad debe ser mayor a 0';
}

if ($id_ubicacion_origen <= 0) {
    $errores[] = 'Debes seleccionar una ubicación de origen válida';
}

if ($id_ubicacion_destino <= 0) {
    $errores[] = 'Debes seleccionar una ubicación de destino válida';
}

if ($id_ubicacion_origen === $id_ubicacion_destino) {
    $errores[] = 'Las ubicaciones de origen y destino deben ser diferentes';
}

if ($id_lote <= 0) {
    $errores[] = 'Debes seleccionar un lote válido';
}

// Verificar que el producto existe
$producto = db()->select("SELECT ID_PRODUCTO FROM productos WHERE ID_PRODUCTO = $id_producto LIMIT 1");
if (count($producto) === 0) {
    $errores[] = 'El producto no existe';
}

// Verificar que existe inventario con la cantidad disponible
$inventario = db()->select("
    SELECT CANTIDAD_TOTAL, CANTIDAD_RESERVADA 
    FROM inventario 
    WHERE ID_PRODUCTO = $id_producto AND ID_LOTE = $id_lote AND ID_UBICACION = $id_ubicacion_origen 
    LIMIT 1
");

if (count($inventario) === 0) {
    $errores[] = 'No existe inventario para este producto en la ubicación de origen';
}

if (count($inventario) > 0) {
    $cantidad_disponible = (int)$inventario[0]['CANTIDAD_TOTAL'] - (int)$inventario[0]['CANTIDAD_RESERVADA'];
    if ($cantidad > $cantidad_disponible) {
        $errores[] = "No hay cantidad suficiente disponible. Disponible: $cantidad_disponible";
    }
}

// Si hay errores, mostrar alerta y redirigir
if (count($errores) > 0) {
    showAlert(implode('. ', $errores), 'error');
    redirect($base_url . '/System/interface/movements/movimientos.php');
}

// ID del usuario actual
$id_usuario = $_SESSION['ID_USUARIO'] ?? 0;

if ($id_usuario <= 0) {
    showAlert('Error de autenticación. Usuario no identificado.', 'error');
    redirect($base_url . '/System/interface/movements/movimientos.php');
}

// Iniciar transacción
db()->execute("START TRANSACTION");

try {
    // Registrar movimiento en tabla movimientos
    $query_movimiento = "
        INSERT INTO movimientos (ID_PRODUCTO, ID_LOTE, ID_USUARIO, ID_UBICACION_ORIGEN, ID_UBICACION_DESTINO, TIPO_MOVIMIENTO, CANTIDAD)
        VALUES ($id_producto, $id_lote, $id_usuario, $id_ubicacion_origen, $id_ubicacion_destino, 'TRANSFERENCIA', $cantidad)
    ";

    if (!db()->execute($query_movimiento)) {
        throw new Exception('Error al registrar el movimiento');
    }

    // Decrementar cantidad en ubicación origen
    $query_origen = "
        UPDATE inventario 
        SET CANTIDAD_TOTAL = CANTIDAD_TOTAL - $cantidad 
        WHERE ID_PRODUCTO = $id_producto AND ID_LOTE = $id_lote AND ID_UBICACION = $id_ubicacion_origen
    ";

    if (!db()->execute($query_origen)) {
        throw new Exception('Error al actualizar inventario en ubicación origen');
    }

    // Incrementar cantidad en ubicación destino si existe inventario
    $inventario_destino = db()->select("
        SELECT ID_INVENTARIO 
        FROM inventario 
        WHERE ID_PRODUCTO = $id_producto AND ID_LOTE = $id_lote AND ID_UBICACION = $id_ubicacion_destino 
        LIMIT 1
    ");

    if (count($inventario_destino) > 0) {
        // Actualizar inventario existente
        $query_destino = "
            UPDATE inventario 
            SET CANTIDAD_TOTAL = CANTIDAD_TOTAL + $cantidad 
            WHERE ID_PRODUCTO = $id_producto AND ID_LOTE = $id_lote AND ID_UBICACION = $id_ubicacion_destino
        ";
    } else {
        // Crear nuevo registro de inventario
        $query_destino = "
            INSERT INTO inventario (ID_PRODUCTO, ID_LOTE, ID_UBICACION, CANTIDAD_TOTAL, CANTIDAD_RESERVADA)
            VALUES ($id_producto, $id_lote, $id_ubicacion_destino, $cantidad, 0)
        ";
    }

    if (!db()->execute($query_destino)) {
        throw new Exception('Error al actualizar inventario en ubicación destino');
    }

    // Confirmar transacción
    db()->execute("COMMIT");

    showAlert('Transferencia registrada exitosamente', 'success');
    redirect($base_url . '/System/interface/movements/movimientos.php');

} catch (Exception $e) {
    // Revertir transacción en caso de error
    db()->execute("ROLLBACK");
    showAlert('Error al procesar la transferencia: ' . $e->getMessage(), 'error');
    redirect($base_url . '/System/interface/movements/movimientos.php');
}
?>
