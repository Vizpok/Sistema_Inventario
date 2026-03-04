# core

Clases principales del sistema.

## Archivos

### Database.php
Clase para manejo de conexión a la base de datos MySQL.

**Características:**
- Conexión MySQLi con configuración desde `config/database.php`
- Patrón Singleton accesible mediante función global `db()`
- Manejo automático de charset UTF-8
- Cierre automático de conexión

**Métodos disponibles:**
- `getConnection()` - Obtiene la conexión mysqli raw
- `select($query)` - Ejecuta SELECT y retorna array asociativo
- `execute($query)` - Ejecuta INSERT/UPDATE/DELETE
- `lastInsertId()` - Obtiene ID del último INSERT
- `escape($string)` - Escapa strings para prevenir SQL injection
- `close()` - Cierra la conexión manualmente

**Ejemplo de uso:**
```php
$db = db();
$productos = $db->select("SELECT * FROM productos WHERE activo = 1");
$db->execute("INSERT INTO productos (nombre) VALUES ('...')");
```

### Auth.php
Clase de autenticación y gestión de sesiones.

**Métodos estáticos:**
- `authenticate($usuario, $contrasena)` - Valida credenciales, retorna array con datos del usuario o false
- `login($user_id, $nombre, $rol)` - Crea sesión del usuario autenticado
- `isAuthenticated()` - Verifica si hay sesión activa (bool)
- `getCurrentUser()` - Obtiene datos del usuario actual (array o null)
- `logout()` - Destruye la sesión
- `hasRole($rol)` - Valida si el usuario tiene un rol específico (bool)

**Modo de autenticación actual:**

- El método `authenticate()` valida usuarios directamente contra la tabla `usuarios` en la base de datos.
- Acepta login por correo completo, nombre y parte local del correo (antes de `@`).
- Filtra únicamente usuarios activos (`ACTIVO = 1`).

**Datos necesarios en BD:**
- `ID_USUARIO`
- `NOMBRE`
- `EMAIL`
- `PASSWORD_USUARIO`
- `ROL`
- `ACTIVO`

**Ejemplo de uso:**
```php
require_once 'System/core/Auth.php';

// Autenticar usuario
$user = Auth::authenticate($usuario, $contrasena);
if ($user) {
    Auth::login($user['id'], $user['nombre'], $user['rol']);
}

// Verificar autenticación
if (Auth::isAuthenticated()) {
    $user = Auth::getCurrentUser();
    echo $user['nombre'];
}

// Validar rol
if (Auth::hasRole('ADMIN')) {
    // Usuario es admin
}

// Logout
Auth::logout();
```

**Variables de sesión creadas:**
```php
$_SESSION['user_id']         // ID del usuario
$_SESSION['user_nombre']     // Nombre del usuario
$_SESSION['user_rol']        // Rol (ADMIN, OPERADOR, etc)
$_SESSION['user_login_time'] // Timestamp del login
```

**Notas:**
- Las contraseñas están sin hash en el estado actual del proyecto (solo desarrollo).
- Para producción, migrar a `password_hash()` y `password_verify()`.
