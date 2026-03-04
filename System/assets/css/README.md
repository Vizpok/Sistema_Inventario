# assets/css

Hojas de estilo del sistema.

## Archivos

### styles.css
Estilos globales del sistema de inventario (anteriormente estilos.css en raíz).

**Variables CSS definidas:**
- `--bg-main`: Color de fondo principal (#f4f6f9)
- `--sidebar-bg`: Fondo del sidebar (#0b1e36)
- `--sidebar-hover`: Hover del sidebar (#152b47)
- `--sidebar-active-bg`: Fondo item activo (#e2e8f0)
- `--text-dark`: Texto oscuro (#333)
- `--text-light`: Texto claro (#fff)
- `--border-color`: Color de bordes (#e2e8f0)

**Componentes incluidos:**
- Layout: `.app-wrapper`, `.sidebar`, `.main-content`, `.page-container`
- Navegación: `.sidebar-menu`, `.sidebar-header`, `.sidebar-footer`
- Botones: `.btn`, `.btn-primary`, `.btn-success`, `.btn-danger`, `.btn-warning`
- Formularios: `.form-group`, `.form-label`, `.form-control`
- Tablas: `.table` con estilos de thead/tbody
- Alertas: `.alert-success`, `.alert-error`, `.alert-warning`, `.alert-info`
- Tarjetas: `.card` con hover effect
- Modal: `.modal-overlay`, `.modal`
- Utilidades: `.text-center`, `.flex`, `.justify-between`, `.mt-20`, `.mb-20`

**Inclusión:**
```html
<link rel="stylesheet" href="System/assets/css/styles.css">
```

El layout header.php ya lo incluye automáticamente.
