<?php
/**
 * Clase de Autenticación
 * Maneja login, logout y validación de sesiones
 */

class Auth {
    
    /**
     * Intenta autenticar un usuario
     * @param string $usuario
     * @param string $contrasena
     * @return array|false Array con datos del usuario o false si falla
     */
    public static function authenticate($usuario, $contrasena) {
        // Validar campos no vacíos
        if (empty($usuario) || empty($contrasena)) {
            return false;
        }

        // MODO ACTIVO: Autenticar desde la Base de Datos
        try {
            $database = new Database();
            $result = $database->select(
                "SELECT ID_USUARIO as id, NOMBRE as nombre, ROL as rol FROM usuarios 
                 WHERE (EMAIL = '" . $database->escape($usuario) . "' 
                 OR NOMBRE = '" . $database->escape($usuario) . "' 
                 OR SUBSTRING_INDEX(EMAIL, '@', 1) = '" . $database->escape($usuario) . "') 
                 AND PASSWORD_USUARIO = '" . $database->escape($contrasena) . "' 
                 AND ACTIVO = 1"
            );
            
            if (!empty($result)) {
                return [
                    'id' => $result[0]['id'],
                    'nombre' => $result[0]['nombre'],
                    'rol' => $result[0]['rol'],
                ];
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Crea una sesión de usuario autenticado
     * @param int $user_id
     * @param string $nombre
     * @param string $rol
     */
    public static function login($user_id, $nombre, $rol) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_nombre'] = $nombre;
        $_SESSION['user_rol'] = $rol;
        $_SESSION['user_login_time'] = time();
    }
    
    /**
     * Verifica si hay una sesión activa
     * @return bool
     */
    public static function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Obtiene los datos del usuario actual
     * @return array|null Array con datos del usuario o null
     */
    public static function getCurrentUser() {
        if (self::isAuthenticated()) {
            return [
                'id' => $_SESSION['user_id'],
                'nombre' => $_SESSION['user_nombre'],
                'rol' => $_SESSION['user_rol'],
            ];
        }
        return null;
    }
    
    /**
     * Cierra la sesión del usuario
     */
    public static function logout() {
        // Limpiar todas las variables de sesión
        $_SESSION = [];

        // Eliminar cookie de sesión
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Destruir sesión
        session_destroy();
    }
    
    /**
     * Valida que el usuario tenga un rol específico
     * @param string|array $roles El rol o roles permitidos
     * @return bool
     */
    public static function hasRole($roles) {
        if (!self::isAuthenticated()) {
            return false;
        }
        
        $rol_usuario = $_SESSION['user_rol'];
        $roles = is_array($roles) ? $roles : [$roles];
        
        return in_array($rol_usuario, $roles);
    }
}
