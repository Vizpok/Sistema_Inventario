# interface/reception

Módulo de recepción de almacén.

## Estado

Este módulo está **implementado**. Incluye formulario de recepción y procesamiento de entradas.

## Archivos Implementados

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
- Validar que el producto existe en la base de datos
- Verificar que la ubicación esté disponible
- Generar automáticamente el ID de movimiento
- Actualizar el stock disponible en la tabla `inventario`
- Registrar el movimiento en la tabla `movimientos` con tipo 'RECEPCION'

## Estructura de Archivos

- `recepcion.php`: Interfaz principal para registrar recepciones
- `recepcion_procesar.php`: Script de procesamiento que maneja la lógica de inserción y actualización

## Flujo de Trabajo

1. Usuario accede a `recepcion.php`
2. Selecciona producto, cantidad, ubicación y proveedor
3. Envía el formulario a `recepcion_procesar.php`
4. Se valida la información
5. Se inserta en `movimientos` y se actualiza `inventario`
6. Redirección con mensaje de éxito o error

## Validaciones Requeridas

- Producto debe existir
- Cantidad debe ser positiva
- Ubicación debe ser válida
- Referencia de recepción no debe estar duplicada

## Integración con Otros Módulos

- **Productos**: Obtiene lista de productos disponibles
- **Ubicaciones**: Lista ubicaciones para asignar stock
- **Proveedores**: Asocia recepción con proveedor
- **Dashboard**: Actualiza métricas automáticamente
- Crear lotes para cada recepción (importante para rastrabilidad)
- Incrementar cantidad en tabla `inventario`
- Registrar movimiento automáticamente en tabla `movimientos`
- Validar que los productos existan en `productos`
- Validar que las ubicaciones existan en `ubicaciones`

## Datos Iniciales

Para ver el dashboard funcionando sin implementar este módulo primero, ejecutar `Datos_Iniciales.sql` que incluye movimientos de prueba.
