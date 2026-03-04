<?php
/**
 * Catálogo de Categorías - Actualizar
 * Procesa la actualización de una categoría existente
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
    redirect('categorias.php');
}

// Obtener y validar datos
$id_categoria = isset($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : 0;
$nombre = isset($_POST['nombre']) ? sanitize($_POST['nombre']) : '';
$codigo_prefijo = isset($_POST['codigo_prefijo']) ? sanitize($_POST['codigo_prefijo']) : '';

// Validaciones
$errores = [];

if ($id_categoria <= 0) {
    $errores[] = 'Categoría inválida';
}

if (empty($nombre)) {
    $errores[] = 'El nombre de la categoría es requerido';
}

if (empty($codigo_prefijo)) {
    $errores[] = 'El código prefijo es requerido';
}

// Verificar que la categoría existe
$categoria_exist = db()->select("SELECT ID_CATEGORIA FROM categorias WHERE ID_CATEGORIA = $id_categoria LIMIT 1");
if (count($categoria_exist) === 0) {
    $errores[] = 'La categoría no existe';
}

// Verificar que el nombre sea único (excepto la actual)
$nombre_exist = db()->select("SELECT ID_CATEGORIA FROM categorias WHERE NOMBRE = '" . db()->escape($nombre) . "' AND ID_CATEGORIA != $id_categoria LIMIT 1");
if (count($nombre_exist) > 0) {
    $errores[] = 'Ya existe otra categoría con este nombre';
}

// Verificar que el código prefijo sea único (excepto el actual)
$codigo_exist = db()->select("SELECT ID_CATEGORIA FROM categorias WHERE CODIGO_PREFIJO = '" . db()->escape($codigo_prefijo) . "' AND ID_CATEGORIA != $id_categoria LIMIT 1");
if (count($codigo_exist) > 0) {
    $errores[] = 'Ya existe otra categoría con este código prefijo';
}

// Si hay errores, redirigir con mensaje
if (count($errores) > 0) {
    showAlert(implode('. ', $errores), 'error');
    redirect('categorias_editar.php?id=' . $id_categoria);
}

// Actualizar categoría
$query = "
    UPDATE categorias 
    SET 
        NOMBRE = '" . db()->escape($nombre) . "',
        CODIGO_PREFIJO = '" . db()->escape(strtoupper($codigo_prefijo)) . "'
    WHERE ID_CATEGORIA = $id_categoria
";

if (db()->execute($query)) {
    showAlert('Categoría actualizada exitosamente', 'success');
    redirect('categorias.php');
} else {
    showAlert('Error al actualizar la categoría. Intenta nuevamente.', 'error');
    redirect('categorias_editar.php?id=' . $id_categoria);
}
