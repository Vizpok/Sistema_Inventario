# System

Este directorio contiene la base del sistema de inventario.

Metodología de trabajo: prototipo.
Base de datos: local (XAMPP), sin servicios en la nube para este entregable.

## Estructura estándar

- `core/`: clases principales del sistema (Database, etc.).
- `assets/`: recursos estáticos (css, iconos e imágenes).
- `config/`: configuración central de la aplicación.
- `interface/`: módulos de interfaz por funcionalidad.
- `sql/`: scripts SQL versionados del sistema.
- `helpers/`: funciones auxiliares y utilidades del sistema.

## Archivos principales

### core/Database.php
Clase oficial para manejo de conexión a base de datos.

**Características:**
- Conexión MySQLi con configuración centralizada desde `config/database.php`
- Métodos: `select()`, `execute()`, `escape()`, `lastInsertId()`
- Protección contra SQL injection
- Manejo automático de charset y cierre de conexión
- Singleton accesible globalmente mediante `db()`

**Uso:**
```php
require 'System/bootstrap.php';
$database = db();
$productos = $database->select("SELECT * FROM productos");
```

### bootstrap.php
Inicializador principal del sistema. Debe incluirse al inicio de cada archivo PHP.

**Funciones:**
- Inicia y configura sesiones según `config/session.php`
- Carga todas las configuraciones (app, database, paths, session)
- Define constantes globales: `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `TIMEZONE`, rutas
- Carga funciones auxiliares desde `helpers/functions.php`
- Configura zona horaria y manejo de errores según ambiente
- Provee función global `db()` para acceso a base de datos

**Uso:**
```php
<?php
require_once __DIR__ . '/System/bootstrap.php';
// Ya tienes acceso a todas las funciones y configuraciones
```

## Convenciones

- Cada carpeta debe contener al menos un archivo (`README.md` o `.gitkeep`) para que Git la incluya.
- No subir credenciales reales al repositorio.
- Mantener una estructura modular por carpeta para evitar desorden.

## Alcance del entregable

- Interfaces incluidas: login/logout, dashboard, catálogos, recepción, inventario, movimientos y ventas.
- Roles previstos: `admin` y `empleado`; en este entregable se prioriza flujo de `empleado`.
- No se crea módulo separado de empleados; la gestión se cubre desde usuarios y restricciones internas.
