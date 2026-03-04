# layouts

Plantillas de diseño reutilizables del sistema.

## Archivos

### header.php
Encabezado del sistema que incluye el navbar/sidebar.

**Contenido:**
- DOCTYPE y etiquetas HTML/HEAD
- Meta tags y título de la página
- Link a estilos CSS (System/assets/css/styles.css)
- Link a Bootstrap Icons
- Sidebar de navegación con menú
- Header superior con información del usuario

**Variables opcionales:**
- `$page_title`: Título personalizado de la página
- `$base_url`: URL base para enlaces (por defecto '../..')

**Uso:**
```php
<?php
require_once __DIR__ . '/../../bootstrap.php';
$page_title = 'Mi Página';
$base_url = '../..';
include __DIR__ . '/../layouts/header.php';
?>

<!-- Tu contenido aquí -->

<?php include __DIR__ . '/../layouts/footer.php'; ?>
```

**Menú de navegación incluido:**
- Dashboard
- Inventario
- Recepción
- Movimientos
- Catálogo
- Salir (botón en footer del sidebar)

### footer.php
Pie del layout que cierra las etiquetas HTML.

**Contenido:**
- Cierre de div page-content
- Cierre de main main-content
- Cierre de div app-wrapper
- Cierre de body y html

**Uso:**
Siempre usar después de header.php para cerrar correctamente el HTML.

## Estructura HTML resultante

```html
<!DOCTYPE html>
<html>
  <head>...</head>
  <body>
    <div class="app-wrapper">
      <aside class="sidebar">...</aside>
      <main class="main-content">
        <header class="top-header">...</header>
        <div class="page-content">
          
          <!-- TU CONTENIDO VA AQUÍ -->
          
        </div>
      </main>
    </div>
  </body>
</html>
```

## Notas

- El header detecta automáticamente la página actual para resaltar el menú activo
- Si existe la función `currentUser()` del helper, mostrará el nombre del usuario
- Los enlaces usan `$base_url` para mantener rutas relativas correctas
- El layout es responsive con CSS Grid y Flexbox
