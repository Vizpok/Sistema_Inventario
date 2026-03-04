<?php
/**
 * Catálogo de Proveedores - Guardar
 * Procesa la creación de un nuevo proveedor
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
    redirect('proveedores_nuevo.php');
}

// Obtener y validar datos
$nombre = isset($_POST['nombre']) ? sanitize($_POST['nombre']) : '';
$rfc = isset($_POST['rfc']) ? sanitize($_POST['rfc']) : null;
$contacto = isset($_POST['contacto']) ? sanitize($_POST['contacto']) : null;

// Validaciones
$errores = [];

if (empty($nombre)) {
    $errores[] = 'El nombre del proveedor es requerido';
}

// Verificar que el nombre sea único
$nombre_exist = db()->select("SELECT ID_PROVEEDOR FROM proveedores WHERE NOMBRE = '" . db()->escape($nombre) . "' LIMIT 1");
if (count($nombre_exist) > 0) {
    $errores[] = 'Ya existe un proveedor con este nombre';
}

// Si hay errores, redirigir con mensaje
if (count($errores) > 0) {
    showAlert(implode('. ', $errores), 'error');
    redirect('proveedores_nuevo.php');
}

// Insertar proveedor
$query = "
    INSERT INTO proveedores (NOMBRE, RFC, CONTACTO)
    VALUES (
        '" . db()->escape($nombre) . "',
        " . ($rfc ? "'" . db()->escape($rfc) . "'" : "NULL") . ",
        " . ($contacto ? "'" . db()->escape($contacto) . "'" : "NULL") . "
    )
";

if (db()->execute($query)) {
    $id_proveedor = db()->lastInsertId();
    showAlert('Proveedor creado exitosamente. ID: ' . $id_proveedor, 'success');
    redirect('proveedores.php');
} else {
    showAlert('Error al crear el proveedor. Intenta nuevamente.', 'error');
    redirect('proveedores_nuevo.php');
}
