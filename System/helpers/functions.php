<?php
/**
 * Funciones Auxiliares del Sistema
 * Funciones de uso común en toda la aplicación
 */

/**
 * Carga un archivo de configuración
 * @param string $name Nombre del archivo de configuración
 * @return array
 */
function config($name) {
    $path = __DIR__ . '/../config/' . $name . '.php';
    if (file_exists($path)) {
        return require $path;
    }
    return [];
}

/**
 * Redirige a una URL
 * @param string $url URL de destino
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Sanitiza una cadena de texto
 * @param string $string Cadena a sanitizar
 * @return string
 */
function sanitize($string) {
    return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
}

/**
 * Formatea una fecha al formato español
 * @param string $date Fecha a formatear
 * @return string
 */
function formatDate($date) {
    $timestamp = strtotime($date);
    return date('d/m/Y H:i', $timestamp);
}

/**
 * Formatea un número como moneda
 * @param float $amount Cantidad a formatear
 * @return string
 */
function formatMoney($amount) {
    return '$' . number_format($amount, 2, '.', ',');
}

/**
 * Verifica si el usuario está autenticado
 * @return bool
 */
function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

/**
 * Obtiene el usuario actual de la sesión
 * @return array|null
 */
function currentUser() {
    if (isAuthenticated()) {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'nombre' => $_SESSION['user_nombre'] ?? null,
            'rol' => $_SESSION['user_rol'] ?? null
        ];
    }
    return null;
}

/**
 * Requiere autenticación, redirige al login si no está autenticado
 */
function requireAuth() {
    if (!isAuthenticated()) {
        redirect('System/interface/session/login.php');
    }
}

/**
 * Muestra un mensaje de alerta
 * @param string $message Mensaje a mostrar
 * @param string $type Tipo de alerta (success, error, warning, info)
 */
function showAlert($message, $type = 'info') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Obtiene y limpia el mensaje de alerta
 * @return array|null
 */
function getAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        return $alert;
    }
    return null;
}

/**
 * Genera un token CSRF
 * @return string
 */
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifica un token CSRF
 * @param string $token Token a verificar
 * @return bool
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Obtiene la URL base del sistema
 * @return string
 */
function baseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    return $protocol . '://' . $host . $script;
}

/**
 * Carga una vista
 * @param string $view Ruta de la vista
 * @param array $data Datos a pasar a la vista
 */
function view($view, $data = []) {
    extract($data);
    $viewPath = __DIR__ . '/../interface/' . $view . '.php';
    if (file_exists($viewPath)) {
        include $viewPath;
    } else {
        die("Vista no encontrada: " . $view);
    }
}
