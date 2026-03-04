<?php
/**
 * Catálogo de Ubicaciones - Guardar
 * Procesa la creación de una nueva ubicación
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
    redirect('ubicaciones_nuevo.php');
}

// Obtener y validar datos
$pasillo = isset($_POST['pasillo']) ? sanitize($_POST['pasillo']) : '';
$estante = isset($_POST['estante']) ? sanitize($_POST['estante']) : '';
$nivel = isset($_POST['nivel']) ? sanitize($_POST['nivel']) : '';

// Validaciones
$errores = [];

if (empty($pasillo)) {
    $errores[] = 'El pasillo es requerido';
}

if (empty($estante)) {
    $errores[] = 'El estante es requerido';
}

if (empty($nivel)) {
    $errores[] = 'El nivel es requerido';
}

// Generar código de ubicación
$codigo_ubicacion = strtoupper($pasillo) . '-' . $estante . '-' . $nivel;

// Verificar que la ubicación sea única
$codigo_exist = db()->select("SELECT ID_UBICACION FROM ubicaciones WHERE PASILLO = '" . db()->escape(strtoupper($pasillo)) . "' AND ESTANTE = '" . db()->escape($estante) . "' AND NIVEL = '" . db()->escape($nivel) . "' LIMIT 1");
if (count($codigo_exist) > 0) {
    $errores[] = 'Ya existe una ubicación con esta combinación de pasillo, estante y nivel';
}

// Si hay errores, redirigir con mensaje
if (count($errores) > 0) {
    showAlert(implode('. ', $errores), 'error');
    redirect('ubicaciones_nuevo.php');
}

// Insertar ubicación
$query = "
    INSERT INTO ubicaciones (PASILLO, ESTANTE, NIVEL)
    VALUES (
        '" . db()->escape(strtoupper($pasillo)) . "',
        '" . db()->escape($estante) . "',
        '" . db()->escape($nivel) . "'
    )
";

if (db()->execute($query)) {
    $id_ubicacion = db()->lastInsertId();
    showAlert('Ubicación creada exitosamente. Código: ' . $codigo_ubicacion, 'success');
    redirect('ubicaciones.php');
} else {
    showAlert('Error al crear la ubicación. Intenta nuevamente.', 'error');
    redirect('ubicaciones_nuevo.php');
}
