# interface/movements

Módulo de movimientos.

## Estado

Este módulo está **pendiente de implementación**. Actualmente solo existe esta guía.

## Alcance previsto

- Registro de movimientos internos (entradas, salidas, ajustes)
- Flujo de ventas y salida de stock
- Historial y trazabilidad de operaciones

## Sugerencia de archivos al implementarlo

- `movimientos.php`, `movimiento_nuevo.php`, `movimiento_procesar.php`
- `ventas.php`, `venta_nueva.php`, `venta_procesar.php`

## Notas

- Registrar cambios de ubicación, ajustes y salidas por venta.
- `venta_procesar.php` debe descontar stock reservado/confirmado según flujo definido.
