-- phpMyAdmin SQL Dump
-- Datos iniciales para el Sistema de Inventario
-- Fecha: 03-03-2026

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario`
--

-- --------------------------------------------------------
-- Limpieza previa (para poder re-ejecutar este script)
-- --------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM `detalle_orden`;
DELETE FROM `ordenes_venta`;
DELETE FROM `movimientos`;
DELETE FROM `inventario`;
DELETE FROM `lotes`;
DELETE FROM `ubicaciones`;
DELETE FROM `productos`;
DELETE FROM `categorias`;
DELETE FROM `proveedores`;
DELETE FROM `clientes`;
DELETE FROM `usuarios`;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- Insertar datos en la tabla `usuarios`
-- --------------------------------------------------------
-- NOTA: Contraseñas en texto plano solo para desarrollo local.
INSERT INTO `usuarios` (`ID_USUARIO`, `NOMBRE`, `EMAIL`, `PASSWORD_USUARIO`, `ROL`, `ACTIVO`) VALUES
(1, 'Administrador Sistema', 'admin@inventario.com', 'ADMIN', 'ADMINISTRADOR', 1),
(2, 'Juan García López', 'juan.garcia@inventario.com', 'gerente123', 'GERENTE', 1),
(3, 'María Rodríguez Pérez', 'maria.rodriguez@inventario.com', 'operador123', 'OPERADOR', 1),
(4, 'Carlos Mendez Silva', 'carlos.mendez@inventario.com', 'operador456', 'OPERADOR', 1),
(5, 'Laura Jiménez Ruiz', 'laura.jimenez@inventario.com', 'supervisor123', 'SUPERVISOR', 1);

-- --------------------------------------------------------
-- Insertar datos en la tabla `categorias`
-- --------------------------------------------------------
INSERT INTO `categorias` (`ID_CATEGORIA`, `NOMBRE`, `CODIGO_PREFIJO`) VALUES
(1, 'Electrónica', 'ELEC'),
(2, 'Accesorios', 'ACC'),
(3, 'Herramientas', 'HER'),
(4, 'Materiales', 'MAT'),
(5, 'Equipos', 'EQU');

-- --------------------------------------------------------
-- Insertar datos en la tabla `proveedores`
-- --------------------------------------------------------
INSERT INTO `proveedores` (`ID_PROVEEDOR`, `NOMBRE`, `RFC`, `CONTACTO`) VALUES
(1, 'TechSupplies México S.A.', 'TECH850101ABC', '(55) 5555-1111'),
(2, 'Industrial Components Ltd', 'INDL900215DEF', '(55) 5555-2222'),
(3, 'GlobalTrade Importadora', 'GLOB800310GHI', '(55) 5555-3333'),
(4, 'MegaDistribuidor Nacional', 'MEGA750425JKL', '(55) 5555-4444');

-- --------------------------------------------------------
-- Insertar datos en la tabla `productos`
-- --------------------------------------------------------
INSERT INTO `productos` (`ID_PRODUCTO`, `ID_CATEGORIA`, `SKU`, `CODIGO_BARRAS`, `NOMBRE`, `PRECIO`, `STOCK_MINIMO`) VALUES
(1, 1, 'SKU-001', '7501234567890', 'Monitor LED 24 pulgadas', 3500.00, 5),
(2, 1, 'SKU-002', '7501234567891', 'Teclado Mecánico RGB', 1200.00, 10),
(3, 1, 'SKU-003', '7501234567892', 'Mouse Óptico Inalámbrico', 350.00, 20),
(4, 2, 'SKU-004', '7501234567893', 'Cable HDMI 3 metros', 85.00, 30),
(5, 2, 'SKU-005', '7501234567894', 'Adaptador USB-C', 150.00, 25),
(6, 3, 'SKU-006', '7501234567895', 'Destornillador de Precisión', 45.00, 15),
(7, 3, 'SKU-007', '7501234567896', 'Juego de Herramientas 12 piezas', 299.00, 8),
(8, 4, 'SKU-008', '7501234567897', 'Papel Térmico Rollo', 25.00, 50),
(9, 5, 'SKU-009', '7501234567898', 'Router WiFi 6 Banda Dual', 1800.00, 6),
(10, 5, 'SKU-010', '7501234567899', 'Switch POE 8 Puertos', 2500.00, 4);

-- --------------------------------------------------------
-- Insertar datos en la tabla `clientes`
-- --------------------------------------------------------
INSERT INTO `clientes` (`ID_CLIENTE`, `NOMBRE_CLIENTE`, `CORREO_CLIENTE`, `DIRECCION_CLIENTE`, `RFC_CLIENTE`, `CONTACTO_CLIENTE`) VALUES
(1, 'Empresa Acme S.A.', 'compras@acme.mx', 'Avenida Paseo de la Reforma 505, CDMX', 'ACME850101XYZ', 'Roberto López'),
(2, 'Tech Solutions Inc', 'ventas@techsolutions.com', 'Calle 25 de Agosto 120, Monterrey', 'TECH900215ABC', 'Francisco Cervantes'),
(3, 'Distribuidora Delta S.A.', 'logistica@delta.mx', 'Blvd. Naciones Unidas 3000, Guadalajara', 'DIST800310DEF', 'Ana Martínez'),
(4, 'Comercial Los Andes Ltda', 'pedidos@losandes.pe', 'Jr. Universitaria 1200, Lima', 'COM750425GHI', 'Miguel Suárez');

-- --------------------------------------------------------
-- Insertar datos en la tabla `ubicaciones`
-- --------------------------------------------------------
INSERT INTO `ubicaciones` (`ID_UBICACION`, `PASILLO`, `ESTANTE`, `NIVEL`) VALUES
(1, 'A', '1', '1'),
(2, 'A', '1', '2'),
(3, 'A', '2', '1'),
(4, 'A', '2', '2'),
(5, 'B', '1', '1'),
(6, 'B', '1', '2'),
(7, 'B', '2', '1'),
(8, 'B', '2', '2'),
(9, 'C', '1', '1'),
(10, 'C', '1', '2');

-- --------------------------------------------------------
-- Insertar datos en la tabla `lotes`
-- --------------------------------------------------------
INSERT INTO `lotes` (`ID_LOTE`, `ID_PRODUCTO`, `ID_PROVEEDOR`, `CODIGO_LOTE`, `PRECIO_COMPRA`, `FECHA_VENCIMIENTO`, `FECHA_RECEPCION`) VALUES
(1, 1, 1, 'LOTE-2026-001', 2500.00, '2028-03-03', '2026-01-15 10:30:00'),
(2, 2, 1, 'LOTE-2026-002', 800.00, NULL, '2026-01-20 14:00:00'),
(3, 3, 2, 'LOTE-2026-003', 250.00, NULL, '2026-01-25 09:15:00'),
(4, 4, 2, 'LOTE-2026-004', 50.00, NULL, '2026-02-01 11:45:00'),
(5, 5, 1, 'LOTE-2026-005', 100.00, NULL, '2026-02-05 15:20:00'),
(6, 6, 3, 'LOTE-2026-006', 30.00, NULL, '2026-02-10 08:30:00'),
(7, 7, 3, 'LOTE-2026-007', 200.00, NULL, '2026-02-15 13:00:00'),
(8, 8, 4, 'LOTE-2026-008', 15.00, '2027-03-03', '2026-02-20 10:00:00'),
(9, 9, 1, 'LOTE-2026-009', 1200.00, NULL, '2026-02-25 16:30:00'),
(10, 10, 2, 'LOTE-2026-010', 1800.00, NULL, '2026-03-01 12:00:00');

-- --------------------------------------------------------
-- Insertar datos en la tabla `inventario`
-- --------------------------------------------------------
INSERT INTO `inventario` (`ID_INVENTARIO`, `ID_PRODUCTO`, `ID_LOTE`, `ID_UBICACION`, `CANTIDAD_TOTAL`, `CANTIDAD_RESERVADA`) VALUES
(1, 1, 1, 1, 8, 2),
(2, 2, 2, 2, 15, 5),
(3, 3, 3, 3, 45, 10),
(4, 4, 4, 4, 60, 15),
(5, 5, 5, 5, 35, 8),
(6, 6, 6, 6, 25, 5),
(7, 7, 7, 7, 12, 3),
(8, 8, 8, 8, 120, 30),
(9, 9, 9, 9, 10, 2),
(10, 10, 10, 10, 6, 1);

-- --------------------------------------------------------
-- Insertar datos en la tabla `movimientos`
-- --------------------------------------------------------
INSERT INTO `movimientos` (`ID_MOVIMIENTO`, `ID_PRODUCTO`, `ID_LOTE`, `ID_USUARIO`, `ID_UBICACION_ORIGEN`, `ID_UBICACION_DESTINO`, `TIPO_MOVIMIENTO`, `CANTIDAD`, `FECHA`) VALUES
(1, 1, 1, 3, NULL, 1, 'RECEPCION', 10, '2026-01-15 10:45:00'),
(2, 2, 2, 3, NULL, 2, 'RECEPCION', 20, '2026-01-20 14:30:00'),
(3, 3, 3, 4, NULL, 3, 'RECEPCION', 50, '2026-01-25 09:45:00'),
(4, 1, 1, 4, 1, 2, 'TRANSFERENCIA', 2, '2026-02-01 10:00:00'),
(5, 4, 4, 3, NULL, 4, 'RECEPCION', 75, '2026-02-01 15:00:00'),
(6, 5, 5, 3, NULL, 5, 'RECEPCION', 40, '2026-02-05 16:00:00'),
(7, 2, 2, 4, 2, 1, 'TRANSFERENCIA', 5, '2026-02-10 11:30:00'),
(8, 6, 6, 3, NULL, 6, 'RECEPCION', 30, '2026-02-10 09:00:00'),
(9, 3, 3, 4, 3, 5, 'TRANSFERENCIA', 5, '2026-02-15 14:20:00'),
(10, 8, 8, 3, NULL, 8, 'RECEPCION', 150, '2026-02-20 11:00:00'),
(11, 7, 7, 4, NULL, 7, 'RECEPCION', 15, '2026-02-22 13:45:00'),
(12, 9, 9, 3, NULL, 9, 'RECEPCION', 12, '2026-02-25 10:15:00'),
(13, 10, 10, 4, NULL, 10, 'RECEPCION', 8, '2026-03-01 15:30:00'),
(14, 1, 1, 3, 1, NULL, 'SALIDA', 2, '2026-02-25 09:30:00'),
(15, 2, 2, 4, 2, NULL, 'SALIDA', 3, '2026-02-26 14:00:00'),
(16, 3, 3, 3, 3, NULL, 'SALIDA', 10, '2026-03-01 11:15:00'),
(17, 4, 4, 4, 4, 5, 'TRANSFERENCIA', 15, '2026-03-02 08:45:00'),
(18, 5, 5, 3, 5, 6, 'TRANSFERENCIA', 8, '2026-03-02 16:20:00'),
(19, 6, 6, 4, 6, NULL, 'SALIDA', 5, '2026-03-02 10:30:00'),
(20, 9, 9, 3, 9, 10, 'TRANSFERENCIA', 2, '2026-03-03 09:00:00');


-- --------------------------------------------------------
-- Insertar datos en la tabla `ordenes_venta`
-- --------------------------------------------------------
INSERT INTO `ordenes_venta` (`ID_ORDEN`, `ID_USUARIO`, `ID_CLIENTE`, `FECHA_CREACION`, `ESTADO`) VALUES
(1, 2, 1, '2026-02-25 09:00:00', 'COMPLETADA'),
(2, 3, 2, '2026-02-26 10:30:00', 'PENDIENTE'),
(3, 2, 3, '2026-03-01 14:15:00', 'PROCESANDO'),
(4, 4, 1, '2026-03-02 11:45:00', 'PENDIENTE');

-- --------------------------------------------------------
-- Insertar datos en la tabla `detalle_orden`
-- --------------------------------------------------------
INSERT INTO `detalle_orden` (`ID_DETALLE`, `ID_ORDEN`, `ID_PRODUCTO`, `CANTIDAD_SOLICITADA`, `PRECIO_PACTADO`) VALUES
(1, 1, 1, 2, 3500.00),
(2, 1, 4, 5, 85.00),
(3, 2, 3, 10, 350.00),
(4, 2, 2, 3, 1200.00),
(5, 3, 9, 1, 1800.00),
(6, 3, 10, 2, 2500.00),
(7, 4, 6, 3, 45.00),
(8, 4, 8, 20, 25.00);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
