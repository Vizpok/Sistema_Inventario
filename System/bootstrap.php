<?php
/**
 * Bootstrap del Sistema
 * Inicializa los componentes principales del sistema
 */

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    $sessionConfig = require __DIR__ . '/config/session.php';
    
    session_name($sessionConfig['name']);
    session_set_cookie_params([
        'lifetime' => $sessionConfig['lifetime'] * 60,
        'secure' => $sessionConfig['secure'],
        'httponly' => $sessionConfig['httponly'],
        'samesite' => $sessionConfig['samesite']
    ]);
    
    session_start();
}

// Cargar configuración de la aplicación
$appConfig = require __DIR__ . '/config/app.php';

// Configurar zona horaria
date_default_timezone_set($appConfig['timezone']);

// Configurar manejo de errores según el ambiente
if ($appConfig['app_debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Cargar funciones auxiliares
require_once __DIR__ . '/helpers/functions.php';

// Cargar clases principales
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';

// Definir constantes del sistema
define('APP_NAME', $appConfig['app_name']);
define('APP_ENV', $appConfig['app_env']);
define('APP_DEBUG', $appConfig['app_debug']);
define('TIMEZONE', $appConfig['timezone']);

// Cargar rutas/paths
$paths = require __DIR__ . '/config/paths.php';
define('BASE_PATH', $paths['base_path']);
define('ASSETS_PATH', $paths['assets_path']);
define('INTERFACE_PATH', $paths['interface_path']);
define('SQL_PATH', $paths['sql_path']);

// Función global para obtener la instancia de la base de datos
function db() {
    static $database = null;
    if ($database === null) {
        $database = new Database();
    }
    return $database;
}

// Configurar charset para respuestas HTTP
header('Content-Type: text/html; charset=' . $appConfig['charset']);
