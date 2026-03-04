# interface/movements

Módulo de movimientos internos y salidas.

## Estado

Este módulo está **pendiente de implementación**. Actualmente solo existe esta guía.

## Alcance previsto

- Registro de movimientos internos (entradas, salidas, ajustes, transferencias)
- Flujo de ventas y salida de stock
- Historial y trazabilidad de operaciones
- Transferencias entre ubicaciones

## Dependencia con el Dashboard

Este módulo genera movimientos registrados en la tabla `movimientos`, que es consultada por el Dashboard para mostrar:

- **Actividad Reciente**: Últimos 5 movimientos por tipo (RECEPCION, TRANSFERENCIA, SALIDA)
- **Stock Bajo**: Afecta la cantidad disponible cuando hay SALIDA
- **Valor Total**: Se reduce cuando se registra una SALIDA

```
movimientos.php (interfaz)
    ↓
movimiento_procesar.php (lógica: TRANSFERENCIA, SALIDA)
venta_procesar.php (lógica: SALIDA por venta)
    ↓
INSERT INTO movimientos (TIPO: 'TRANSFERENCIA' o 'SALIDA')
    ↓
UPDATE inventario (disminuye CANTIDAD_DISPONIBLE)
    ↓
Dashboard actualiza métricas automáticamente
```

## Tipos de Movimientos

1. **RECEPCION** - Entrada desde proveedor (módulo reception)
2. **TRANSFERENCIA** - Movimiento entre ubicaciones (este módulo)
3. **SALIDA** - Producto vendido o removido (este módulo)

## Sugerencia de archivos al implementarlo

- `movimientos.php` - Vista del historial
- `movimiento_nuevo.php` - Formulario para transferencias
- `movimiento_procesar.php` - Procesa transferencias y ajustes
- `ventas.php` - Historial de ventas
- `venta_nueva.php` - Nueva salida/venta
- `venta_procesar.php` - Procesa salidas por venta

## Notas de Implementación

- Registrar cambios de ubicación (origen → destino)
- Registrar ajustes y salidas por venta
- Validar que hay cantidad disponible antes de una salida
- `venta_procesar.php` debe descontar del campo CANTIDAD_DISPONIBLE
- Todos los movimientos deben tener registro en tabla `movimientos` para auditoría
- Usuario actual (`$_SESSION['ID_USUARIO']`) debe registrarse en cada movimiento

## Datos Iniciales

El archivo `Datos_Iniciales.sql` incluye 20 movimientos de ejemplo (recepciones, transferencias y salidas) para ver el dashboard funcionando.
