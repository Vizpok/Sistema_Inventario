# interface/reception

Módulo de recepción de almacén.

## Estado

Este módulo está **pendiente de implementación**. Actualmente solo existe esta guía.

## Alcance previsto

- `recepcion.php`: vista de entrada de almacén
- `recepcion_procesar.php`: registro de entradas y actualización de existencias

## Propósito

Es el módulo responsable de registrar la entrada de productos al almacén desde proveedores. Al procesar una recepción:

1. Se verifica el producto y cantidad
2. Se genera un movimiento de tipo **'RECEPCION'**
3. Se actualiza la tabla `inventario` con la nueva cantidad
4. Los datos quedan reflejados automáticamente en:
   - **Dashboard** - Actualiza valor total, cantidad disponible, stock bajo
   - **Movimientos** - Registra el historial

## Dependencia con el Dashboard

El **Dashboard** (`interface/dashboard/index.php`) obtiene sus datos de los movimientos registrados en este módulo:

```
recepcion.php (interfaz)
    ↓
recepcion_procesar.php (lógica)
    ↓
INSERT INTO movimientos (TIPO: 'RECEPCION')
    ↓
UPDATE inventario (nueva cantidad)
    ↓
Dashboard lee datos actualizados
```

### Consultas del Dashboard que usan estos datos:

- **Valor Total**: `SELECT SUM(p.PRECIO * i.CANTIDAD_DISPONIBLE)` desde tabla `inventario`
- **Total Productos**: `SELECT COUNT(DISTINCT ID_PRODUCTO) FROM productos`
- **Stock Bajo**: Compara `CANTIDAD_DISPONIBLE < STOCK_MINIMO` desde `inventario`
- **Actividad Reciente**: `SELECT * FROM movimientos ORDER BY FECHA DESC LIMIT 5`

## Notas de Implementación

- Registrar fecha, producto, cantidad, ubicación y referencia de recepción
- Crear lotes para cada recepción (importante para rastrabilidad)
- Incrementar cantidad en tabla `inventario`
- Registrar movimiento automáticamente en tabla `movimientos`
- Validar que los productos existan en `productos`
- Validar que las ubicaciones existan en `ubicaciones`

## Datos Iniciales

Para ver el dashboard funcionando sin implementar este módulo primero, ejecutar `Datos_Iniciales.sql` que incluye movimientos de prueba.
