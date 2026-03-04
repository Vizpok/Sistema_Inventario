# interface/dashboard

Módulo de tablero principal del sistema.

## Archivos

### index.php
Dashboard principal del sistema de inventario con **datos en tiempo real**.

**Características:**
- Carga automática del bootstrap del sistema
- Usa layouts/header.php y layouts/footer.php
- Muestra tarjetas con métricas del inventario en tiempo real:
  - **Valor total del almacén**: Suma de (precio × cantidad disponible) de todos los productos
  - **Total de productos**: Conteo de productos catalogados
  - **Stock Bajo**: Productos cuya cantidad disponible es menor al stock mínimo
  - **Actividad Reciente**: Últimos 5 movimientos registrados (recepciones, transferencias, salidas)
- Accesos rápidos a módulos principales

**Ubicación:**
`System/interface/dashboard/index.php`

**Acceso:**
- Directo: `http://localhost/Sistema_Inventario/System/interface/dashboard/index.php`
- Redirección raíz: `http://localhost/Sistema_Inventario/` (redirige aquí)

## Dependencias de Datos

El dashboard consulta **datos en tiempo real** de las siguientes tablas:

1. **`inventario`** - Cantidad de productos disponibles por ubicación
2. **`productos`** - Precio unitario y stock mínimo
3. **`movimientos`** - Actividad reciente (recepciones, transferencias, salidas)
4. **`usuarios`** - Nombre del usuario que realizó cada movimiento

### Flujo de datos:

```
Recepción (reception/) 
    ↓
Genera movimientos (TIPO: 'RECEPCION')
    ↓
Actualiza inventario
    ↓
Dashboard lee datos actualizados
```

## Requisitos para Funcionamiento

El dashboard requiere que estén previamente cargados en la base de datos:

1. **Productos** - Deben existir en la tabla `productos` con precios definidos
2. **Inventario** - Al menos un registro en `inventario` con cantidad disponible > 0
3. **Usuarios** - Necesarios para registrar movimientos
4. **Movimientos** - Generados automáticamente por:
   - **Módulo de Recepción** (`interface/reception/`) - Al recibir productos
   - **Módulo de Movimientos** (`interface/movements/`) - Al transferir o registrar salidas
5. **Órdenes de Venta** - Generadas por el catálogo al crear ordenes

## Datos Iniciales

Por defecto, el sistema carga datos de prueba en `System/sql/Datos_Iniciales.sql` que incluyen:

- 10 productos en diferentes categorías
- 10 lotes de recepciones
- 10 ubicaciones de almacén
- 20 movimientos registrados
- 4 órdenes de venta con detalles

Ejecutar el script de datos iniciales para ver el dashboard funcionando con información de ejemplo.

## Estructura del código

```php
<?php
require_once __DIR__ . '/../../bootstrap.php';

$DB = new Database();

// Ejemplo: Consulta de valor total
$resultado = $DB->select("
    SELECT SUM(p.PRECIO * i.CANTIDAD_DISPONIBLE) as total 
    FROM inventario i
    JOIN productos p ON i.ID_PRODUCTO = p.ID_PRODUCTO
    WHERE i.CANTIDAD_DISPONIBLE > 0
");
$valor_total = $resultado[0]['total'] ?? 0;
echo '$' . number_format($valor_total, 2);
```

## Tarjetas incluidas

1. **Valor Total** - Suma de precios × cantidad disponible
2. **Total Productos** - Conteo de productos catalogados
3. **Stock Bajo** - Productos por debajo del mínimo
4. **Actividad Reciente** - Tabla con últimos 5 movimientos


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
