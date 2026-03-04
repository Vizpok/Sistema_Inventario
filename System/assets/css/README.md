# assets/css

Hojas de estilo del sistema.

## Archivos

### styles.css
Estilos globales del sistema (layout, sidebar, tabla, botones, etc).

**Secciones:**
- Variables CSS de colores y espaciados
- Layout principal (sidebar, main-content)
- Componentes (botones, formularios, tablas, tarjetas)
- Alertas y modales
- Clases utilitarias

**Uso:**
```html
<link rel="stylesheet" href="System/assets/css/styles.css">
```

### auth.css
Estilos específicos de las páginas de autenticación (login).

**Componentes:**
- `.auth-page` - Body de página de auth
- `.auth-container` - Contenedor principal
- `.auth-header` - Encabezado con logo
- `.auth-form` - Formulario
- `.auth-form-control` - Inputs
- `.auth-btn-submit` - Botón de envío
- `.auth-alert` - Alertas de error/éxito
- `.auth-credentials` - Caja de credenciales de prueba

**Uso:**
```html
<link rel="stylesheet" href="System/assets/css/auth.css">

<body class="auth-page">
    <div class="auth-container">
        <!-- Contenido de auth -->
    </div>
</body>
```

**Variables de color:**
- Gradiente de fondo: `linear-gradient(135deg, #0b1e36 0%, #152b47 100%)`
- Primario: `#0b1e36` (azul oscuro)
- Secundario: `#152b47` (azul más claro)
- Texto: `#333`
- Borde: `#e2e8f0`

## Estructura de estilos

Las clases CSS siguen un patrón consistente:
- `.auth-*` para componentes de autenticación
- Nombres descriptivos y en inglés
- Responsive design con media queries
- Transiciones suaves (0.2s)

## Futuras hojas de estilo

Se pueden agregar:
- `dashboard.css` - Estilos específicos del dashboard
- `forms.css` - Estilos avanzados para formularios
- `tables.css` - Estilos para tablas
- `print.css` - Estilos para impresión
- `responsive.css` - Breakpoints responsivos
- Temas alternativos (dark mode, etc)
