# interface/dashboard

Módulo de tablero principal del sistema.

## Archivos

### index.php
Dashboard principal del sistema de inventario.

**Características:**
- Carga automática del bootstrap del sistema
- Usa layouts/header.php y layouts/footer.php
- Muestra tarjetas con métricas del inventario:
  - Valor total del almacén
  - Total de productos
  - Productos con stock bajo
  - Actividad reciente
- Accesos rápidos a módulos principales

**Ubicación:**
`System/interface/dashboard/index.php`

**Acceso:**
- Directo: `http://localhost/Sistema_Inventario/System/interface/dashboard/index.php`
- Redirección raíz: `http://localhost/Sistema_Inventario/` (redirige aquí)

**Estructura del código:**
```php
<?php
require_once __DIR__ . '/../../bootstrap.php';
$page_title = 'Dashboard';
$base_url = '../..';
include __DIR__ . '/../layouts/header.php';
?>

<!-- Contenido del dashboard -->
<div class="page-container">
    <div class="page-header">...</div>
    <section class="dashboard-content">
        <div class="card">...</div>
    </section>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
```

**Tarjetas incluidas:**
1. **Valor Total** - Suma de precios × cantidad (requiere consulta BD)
2. **Total Productos** - Conteo de productos en inventario
3. **Stock Bajo** - Productos por debajo del mínimo
4. **Actividad Reciente** - Últimos movimientos registrados

**Accesos rápidos:**
- Ver Inventario
- Nueva Recepción
- Ver Movimientos
- Administrar Catálogo

## Próximos pasos

- Conectar las tarjetas con datos reales de la BD usando `db()`
- Implementar gráficas con Chart.js o similar
- Agregar filtros por fecha para las métricas
- Sistema de notificaciones para stock bajo
