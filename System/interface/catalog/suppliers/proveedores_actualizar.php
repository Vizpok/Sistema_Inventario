<?php
/**
 * Catálogo de Proveedores - Actualizar
 * Procesa la actualización de un proveedor existente
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Solo acepta POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('proveedores.php');
}

// Verificar token CSRF
if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
    showAlert('Token de seguridad inválido. Intenta nuevamente.', 'error');
    redirect('proveedores.php');
}

// Obtener y validar datos
$id_proveedor = isset($_POST['id_proveedor']) ? (int)$_POST['id_proveedor'] : 0;
$nombre = isset($_POST['nombre']) ? sanitize($_POST['nombre']) : '';
$rfc = isset($_POST['rfc']) ? sanitize($_POST['rfc']) : null;
$contacto = isset($_POST['contacto']) ? sanitize($_POST['contacto']) : null;

// Validaciones
$errores = [];

if ($id_proveedor <= 0) {
    $errores[] = 'Proveedor inválido';
}

if (empty($nombre)) {
    $errores[] = 'El nombre del proveedor es requerido';
}

// Verificar que el proveedor existe
$proveedor_exist = db()->select("SELECT ID_PROVEEDOR FROM proveedores WHERE ID_PROVEEDOR = $id_proveedor LIMIT 1");
if (count($proveedor_exist) === 0) {
    $errores[] = 'El proveedor no existe';
}

// Verificar que el nombre sea único (excepto el actual)
$nombre_exist = db()->select("SELECT ID_PROVEEDOR FROM proveedores WHERE NOMBRE = '" . db()->escape($nombre) . "' AND ID_PROVEEDOR != $id_proveedor LIMIT 1");
if (count($nombre_exist) > 0) {
    $errores[] = 'Ya existe otro proveedor con este nombre';
}

// Verificar que el RFC sea único (excepto el actual, si se proporciona)
if (!empty($rfc)) {
    $rfc_exist = db()->select("SELECT ID_PROVEEDOR FROM proveedores WHERE RFC = '" . db()->escape($rfc) . "' AND ID_PROVEEDOR != $id_proveedor LIMIT 1");
    if (count($rfc_exist) > 0) {
        $errores[] = 'Ya existe otro proveedor con este RFC';
    }
}

// Si hay errores, redirigir con mensaje
if (count($errores) > 0) {
    showAlert(implode('. ', $errores), 'error');
    redirect('proveedores_editar.php?id=' . $id_proveedor);
}

// Actualizar proveedor
$query = "
    UPDATE proveedores 
    SET 
        NOMBRE = '" . db()->escape($nombre) . "',
        RFC = " . ($rfc ? "'" . db()->escape($rfc) . "'" : "NULL") . ",
        CONTACTO = " . ($contacto ? "'" . db()->escape($contacto) . "'" : "NULL") . "
    WHERE ID_PROVEEDOR = $id_proveedor
";

if (db()->execute($query)) {
    showAlert('Proveedor actualizado exitosamente', 'success');
    redirect('proveedores.php');
} else {
    showAlert('Error al actualizar el proveedor. Intenta nuevamente.', 'error');
    redirect('proveedores_editar.php?id=' . $id_proveedor);
}
