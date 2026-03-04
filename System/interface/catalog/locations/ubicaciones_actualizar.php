<?php
/**
 * Catálogo de Ubicaciones - Actualizar
 * Procesa la actualización de una ubicación existente
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Solo acepta POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('ubicaciones.php');
}

// Verificar token CSRF
if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
    showAlert('Token de seguridad inválido. Intenta nuevamente.', 'error');
    redirect('ubicaciones.php');
}

// Obtener y validar datos
$id_ubicacion = isset($_POST['id_ubicacion']) ? (int)$_POST['id_ubicacion'] : 0;
$pasillo = isset($_POST['pasillo']) ? sanitize($_POST['pasillo']) : '';
$estante = isset($_POST['estante']) ? sanitize($_POST['estante']) : '';
$nivel = isset($_POST['nivel']) ? sanitize($_POST['nivel']) : '';

// Validaciones
$errores = [];

if ($id_ubicacion <= 0) {
    $errores[] = 'Ubicación inválida';
}

if (empty($pasillo)) {
    $errores[] = 'El pasillo es requerido';
}

if (empty($estante)) {
    $errores[] = 'El estante es requerido';
}

if (empty($nivel)) {
    $errores[] = 'El nivel es requerido';
}

// Verificar que la ubicación existe
$ubicacion_exist = db()->select("SELECT ID_UBICACION FROM ubicaciones WHERE ID_UBICACION = $id_ubicacion LIMIT 1");
if (count($ubicacion_exist) === 0) {
    $errores[] = 'La ubicación no existe';
}

// Verificar que la nueva ubicación sea única (excepto la actual)
$codigo_exist = db()->select("SELECT ID_UBICACION FROM ubicaciones WHERE PASILLO = '" . db()->escape(strtoupper($pasillo)) . "' AND ESTANTE = '" . db()->escape($estante) . "' AND NIVEL = '" . db()->escape($nivel) . "' AND ID_UBICACION != $id_ubicacion LIMIT 1");
if (count($codigo_exist) > 0) {
    $errores[] = 'Ya existe otra ubicación con esta combinación de pasillo, estante y nivel';
}

// Si hay errores, redirigir con mensaje
if (count($errores) > 0) {
    showAlert(implode('. ', $errores), 'error');
    redirect('ubicaciones_editar.php?id=' . $id_ubicacion);
}

// Actualizar ubicación
$query = "
    UPDATE ubicaciones 
    SET 
        PASILLO = '" . db()->escape(strtoupper($pasillo)) . "',
        ESTANTE = '" . db()->escape($estante) . "',
        NIVEL = '" . db()->escape($nivel) . "'
    WHERE ID_UBICACION = $id_ubicacion
";

if (db()->execute($query)) {
    $codigo_ubicacion = strtoupper($pasillo) . '-' . $estante . '-' . $nivel;
    showAlert('Ubicación actualizada exitosamente. Código: ' . $codigo_ubicacion, 'success');
    redirect('ubicaciones.php');
} else {
    showAlert('Error al actualizar la ubicación. Intenta nuevamente.', 'error');
    redirect('ubicaciones_editar.php?id=' . $id_ubicacion);
}
