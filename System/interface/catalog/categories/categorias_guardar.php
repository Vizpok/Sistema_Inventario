<?php
/**
 * Catálogo de Categorías - Guardar
 * Procesa la creación de una nueva categoría
 */

require_once __DIR__ . '/../../../bootstrap.php';

// Requerir autenticación
requireAuth();

// Solo acepta POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('categorias.php');
}

// Verificar token CSRF
if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
    showAlert('Token de seguridad inválido. Intenta nuevamente.', 'error');
    redirect('categorias_nuevo.php');
}

// Obtener y validar datos
$nombre = isset($_POST['nombre']) ? sanitize($_POST['nombre']) : '';
$codigo_prefijo = isset($_POST['codigo_prefijo']) ? sanitize($_POST['codigo_prefijo']) : '';

// Validaciones
$errores = [];

if (empty($nombre)) {
    $errores[] = 'El nombre de la categoría es requerido';
}

if (empty($codigo_prefijo)) {
    $errores[] = 'El código prefijo es requerido';
}

// Verificar que el nombre sea único
$nombre_exist = db()->select("SELECT ID_CATEGORIA FROM categorias WHERE NOMBRE = '" . db()->escape($nombre) . "' LIMIT 1");
if (count($nombre_exist) > 0) {
    $errores[] = 'Ya existe una categoría con este nombre';
}

// Verificar que el código prefijo sea único
$codigo_exist = db()->select("SELECT ID_CATEGORIA FROM categorias WHERE CODIGO_PREFIJO = '" . db()->escape($codigo_prefijo) . "' LIMIT 1");
if (count($codigo_exist) > 0) {
    $errores[] = 'Ya existe una categoría con este código prefijo';
}

// Si hay errores, redirigir con mensaje
if (count($errores) > 0) {
    showAlert(implode('. ', $errores), 'error');
    redirect('categorias_nuevo.php');
}

// Insertar categoría
$query = "
    INSERT INTO categorias (NOMBRE, CODIGO_PREFIJO)
    VALUES (
        '" . db()->escape($nombre) . "',
        '" . db()->escape(strtoupper($codigo_prefijo)) . "'
    )
";

if (db()->execute($query)) {
    $id_categoria = db()->lastInsertId();
    showAlert('Categoría creada exitosamente. ID: ' . $id_categoria, 'success');
    redirect('categorias.php');
} else {
    showAlert('Error al crear la categoría. Intenta nuevamente.', 'error');
    redirect('categorias_nuevo.php');
}
