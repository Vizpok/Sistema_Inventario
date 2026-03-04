-- Agregando productos con stock bajo para probar el card de dashboard "Productos con Stock Bajo"
INSERT INTO `productos` (`ID_PRODUCTO`, `ID_CATEGORIA`, `SKU`, `CODIGO_BARRAS`, `NOMBRE`, `PRECIO`, `STOCK_MINIMO`) VALUES
(11, 1, 'SKU-011', '7501234567900', 'Monitor LED 27 pulgadas', 5500.00, 8),
(12, 2, 'SKU-012', '7501234567901', 'Cable Ethernet Cat6 5m', 120.00, 15),
(13, 3, 'SKU-013', '7501234567902', 'Llave Inglesa Ajustable', 85.00, 10);

-- Agregando lotes para los nuevos productos
INSERT INTO `lotes` (`ID_LOTE`, `ID_PRODUCTO`, `ID_PROVEEDOR`, `CODIGO_LOTE`, `PRECIO_COMPRA`, `FECHA_VENCIMIENTO`, `FECHA_RECEPCION`) VALUES
(11, 11, 1, 'LOTE-2026-011', 3800.00, '2028-03-04', '2026-02-15 10:00:00'),
(12, 12, 2, 'LOTE-2026-012', 80.00, NULL, '2026-02-20 14:00:00'),
(13, 13, 3, 'LOTE-2026-013', 60.00, NULL, '2026-02-28 09:30:00');

-- Agregando inventario CON STOCK BAJO (cantidad < stock mínimo)
INSERT INTO `inventario` (`ID_INVENTARIO`, `ID_PRODUCTO`, `ID_LOTE`, `ID_UBICACION`, `CANTIDAD_TOTAL`, `CANTIDAD_RESERVADA`) VALUES
(11, 11, 11, 1, 5, 0),   -- Stock mínimo: 8, Disponible: 5
(12, 12, 12, 2, 10, 2),  -- Stock mínimo: 15, Disponible: 8
(13, 13, 13, 3, 6, 1);   -- Stock mínimo: 10, Disponible: 5

-- Agregando movimientos de recepción para que aparezcan en actividad reciente
INSERT INTO `movimientos` (`ID_MOVIMIENTO`, `ID_PRODUCTO`, `ID_LOTE`, `ID_USUARIO`, `ID_UBICACION_ORIGEN`, `ID_UBICACION_DESTINO`, `TIPO_MOVIMIENTO`, `CANTIDAD`, `FECHA`) VALUES
(21, 11, 11, 3, NULL, 1, 'RECEPCION', 5, '2026-03-04 10:15:00'),
(22, 12, 12, 4, NULL, 2, 'RECEPCION', 12, '2026-03-03 14:30:00'),
(23, 13, 13, 3, NULL, 3, 'RECEPCION', 7, '2026-03-02 09:45:00');

-- Borrar movimientos
DELETE FROM `movimientos` WHERE `ID_MOVIMIENTO` IN (21, 22, 23);

-- Borrar inventario
DELETE FROM `inventario` WHERE `ID_INVENTARIO` IN (11, 12, 13);

-- Borrar lotes
DELETE FROM `lotes` WHERE `ID_LOTE` IN (11, 12, 13);

-- Borrar productos
DELETE FROM `productos` WHERE `ID_PRODUCTO` IN (11, 12, 13);