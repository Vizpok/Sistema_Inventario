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
// Obtener instancia (via bootstrap.php)
$db = db();

// SELECT
$productos = $db->select("SELECT * FROM productos WHERE activo = 1");
foreach ($productos as $producto) {
    echo $producto['nombre'];
}

// INSERT
$nombre = $db->escape($_POST['nombre']);
$db->execute("INSERT INTO productos (nombre) VALUES ('$nombre')");
$nuevo_id = $db->lastInsertId();

// UPDATE
$db->execute("UPDATE productos SET precio = 100 WHERE id = 5");

// DELETE
$db->execute("DELETE FROM productos WHERE id = 10");

// Conexión raw (para casos avanzados)
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
```

## Futuras clases

Esta carpeta puede contener:
- `Auth.php` - Autenticación de usuarios
- `Session.php` - Manejo avanzado de sesiones
- `Router.php` - Sistema de rutas
- `Request.php` - Manejo de peticiones HTTP
- `Response.php` - Manejo de respuestas HTTP
- `Validator.php` - Validación de datos
- `Model.php` - Clase base para modelos

## Convenciones

- Una clase por archivo
- Nombres en PascalCase
- Documentación PHPDoc en todos los métodos públicos
- Manejo de errores con try-catch
