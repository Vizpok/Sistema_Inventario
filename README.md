# Sistema de Inventario

Sistema de gestión de inventario desarrollado para control de almacén y movimientos de productos.

## 🚀 Inicio Rápido

1. Importar base de datos desde `System/sql/Base_Inventario.sql`
2. Configurar conexión en `System/config/database.php`
3. Acceder a través de XAMPP: `http://localhost/Sistema_Inventario/`
4. El sistema redirige automáticamente al dashboard

## 📁 Estructura del Proyecto

```
Sistema_Inventario/
├── index.php             # Redirige al dashboard
└── System/               # Core del sistema
    ├── bootstrap.php     # Inicializador del sistema
    ├── core/             # Clases principales
    │   └── Database.php
    ├── config/           # Configuraciones
    │   ├── app.php
    │   ├── database.php
    │   ├── session.php
    │   └── paths.php
    ├── helpers/          # Funciones auxiliares
    │   └── functions.php
    ├── interface/        # Módulos de interfaz
    │   ├── layouts/      # Plantillas reutilizables
    │   │   ├── header.php
    │   │   └── footer.php
    │   ├── dashboard/
    │   │   └── index.php
    │   ├── catalog/
    │   ├── inventory/
    │   ├── movements/
    │   ├── reception/
    │   └── session/
    ├── assets/           # Recursos estáticos
    │   └── css/
    │       └── styles.css
    └── sql/              # Scripts de base de datos
```

## 🔧 Creación de Páginas Nuevas

Todas las páginas del sistema deben seguir esta estructura estándar:

```php
<?php
/**
 * Título de la página
 * Descripción breve
 */

// 1. Cargar el bootstrap del sistema
require_once __DIR__ . '/../../bootstrap.php';

// 2. Opcional: Requerir autenticación
// requireAuth();

// 3. Configurar variables para el layout
$page_title = 'Título de la Página';
$base_url = '../..'; // Ajustar según nivel de carpetas

// 4. Incluir header
include __DIR__ . '/../layouts/header.php';
?>

<!-- 5. Contenido de tu página -->
<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Título</h1>
        <p class="page-subtitle">Descripción</p>
    </div>
    
    <section>
        <!-- Tu contenido aquí -->
    </section>
</div>

<?php
// 6. Incluir footer
include __DIR__ . '/../layouts/footer.php';
?>
```

## 📄 Componentes del Sistema

### Bootstrap (System/bootstrap.php)
Inicializador que debe cargarse en cada página:
```php
require_once __DIR__ . '/System/bootstrap.php';
```

**Funciones:**
- Inicia sesiones
- Carga configuraciones
- Define constantes
- Carga helpers
- Provee función `db()` para acceso a BD

### Layouts (System/interface/layouts/)

**header.php** - Navbar y sidebar del sistema
**footer.php** - Cierre de HTML

### Database (System/core/Database.php)
Clase para manejo de base de datos:
```php
$database = db();
$productos = $database->select("SELECT * FROM productos");
$database->execute("UPDATE productos SET cantidad = 10 WHERE id = 1");
```

### Helpers (System/helpers/functions.php)
Funciones auxiliares disponibles:
- `requireAuth()` - Requiere autenticación
- `currentUser()` - Obtiene datos del usuario
- `formatMoney($amount)` - Formatea como moneda
- `formatDate($date)` - Formatea fecha
- `sanitize($string)` - Limpia entrada usuario
- `showAlert($msg, $type)` - Muestra alerta
- `redirect($url)` - Redirección HTTP

### Estilos CSS (System/assets/css/styles.css)
Componentes CSS reutilizables:

```html
<!-- Botones -->
<button class="btn btn-primary">Guardar</button>
<button class="btn btn-success">Crear</button>
<button class="btn btn-danger">Eliminar</button>
<button class="btn btn-warning">Advertencia</button>

<!-- Formularios -->
<div class="form-group">
    <label class="form-label">Nombre:</label>
    <input type="text" class="form-control">
</div>

<!-- Alertas -->
<div class="alert alert-success">Operación exitosa</div>
<div class="alert alert-error">Error al guardar</div>
<div class="alert alert-warning">Advertencia</div>
<div class="alert alert-info">Información</div>

<!-- Tablas -->
<table class="table">
    <thead><tr><th>Columna</th></tr></thead>
    <tbody><tr><td>Dato</td></tr></tbody>
</table>

<!-- Tarjetas -->
<div class="card">
    <h3>Título</h3>
    <p>Contenido</p>
</div>
```

## 🗑️ Archivos para Eliminar

Los siguientes archivos de la raíz YA fueron migrados al sistema y pueden eliminarse:

1. **conexion.php** - Reemplazado por `System/bootstrap.php` + `System/Database.php`
2. **encabezado.php** - Movido a `System/interface/layouts/header.php` y `footer.php`
3. **estilos.css** - Movido a `System/assets/css/styles.css`
4. **dashboard.php** - Movido a `System/interface/dashboard/index.php`
5. **prueba.php** - Archivo de pruebas, no necesario

**Para eliminarlos:**
```powershell
Remove-Item conexion.php, encabezado.php, estilos.css, dashboard.php, prueba.php
```

El archivo `index.php` en la raíz SÍ debe mantenerse (redirige al dashboard).

## � Tecnologías

- **Backend:** PHP 7.4+
- **Base de datos:** MySQL 5.7+ (XAMPP)
- **Frontend:** HTML5, CSS3, JavaScript
- **Iconos:** Bootstrap Icons
- **Servidor:** Apache (XAMPP)

## 📝 Convenciones de Código

1. Siempre incluir `System/bootstrap.php` en archivos nuevos
2. Usar layouts (header.php y footer.php) para mantener diseño consistente
3. Usar funciones del helper para sanitización y formateo
4. Documentar cambios en README.md de cada módulo
5. No hardcodear credenciales en el código
6. Usar la función `db()` para acceso a base de datos

## 🔐 Seguridad

- Sanitización de entradas con `sanitize()`
- Escape de SQL con `$database->escape()`
- CSRF tokens disponibles con `generateCsrfToken()`
- Verificación de autenticación con `requireAuth()`

## 👥 Roles del Sistema

- **Admin:** Acceso completo al sistema
- **Empleado:** Operaciones básicas de inventario

## 📚 Documentación Adicional

- [System/README.md](System/README.md) - Core del sistema
- [System/config/README.md](System/config/README.md) - Configuraciones
- [System/helpers/README.md](System/helpers/README.md) - Funciones auxiliares
- [System/interface/README.md](System/interface/README.md) - Módulos de interfaz
