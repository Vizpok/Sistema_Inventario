-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-03-2026 a las 22:16:25
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `ID_CATEGORIA` int(11) NOT NULL,
  `NOMBRE` varchar(50) NOT NULL,
  `CODIGO_PREFIJO` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `ID_CLIENTE` int(11) NOT NULL,
  `NOMBRE_CLIENTE` varchar(150) NOT NULL,
  `CORREO_CLIENTE` varchar(100) DEFAULT NULL,
  `DIRECCION_CLIENTE` varchar(200) DEFAULT NULL,
  `RFC_CLIENTE` varchar(20) DEFAULT NULL,
  `CONTACTO_CLIENTE` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_orden`
--

CREATE TABLE `detalle_orden` (
  `ID_DETALLE` int(11) NOT NULL,
  `ID_ORDEN` int(11) NOT NULL,
  `ID_PRODUCTO` int(11) NOT NULL,
  `CANTIDAD_SOLICITADA` int(11) NOT NULL,
  `PRECIO_PACTADO` decimal(10,2) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `ID_INVENTARIO` int(11) NOT NULL,
  `ID_PRODUCTO` int(11) NOT NULL,
  `ID_LOTE` int(11) NOT NULL,
  `ID_UBICACION` int(11) NOT NULL,
  `CANTIDAD_TOTAL` int(11) NOT NULL DEFAULT 0,
  `CANTIDAD_RESERVADA` int(11) NOT NULL DEFAULT 0,
  `CANTIDAD_DISPONIBLE` int(11) GENERATED ALWAYS AS (`CANTIDAD_TOTAL` - `CANTIDAD_RESERVADA`) STORED
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotes`
--

CREATE TABLE `lotes` (
  `ID_LOTE` int(11) NOT NULL,
  `ID_PRODUCTO` int(11) NOT NULL,
  `ID_PROVEEDOR` int(11) NOT NULL,
  `CODIGO_LOTE` varchar(50) NOT NULL,
  `PRECIO_COMPRA` decimal(10,2) NOT NULL DEFAULT 0.00,
  `FECHA_VENCIMIENTO` date DEFAULT NULL,
  `FECHA_RECEPCION` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `ID_MOVIMIENTO` int(11) NOT NULL,
  `ID_PRODUCTO` int(11) NOT NULL,
  `ID_LOTE` int(11) NOT NULL,
  `ID_USUARIO` int(11) NOT NULL,
  `ID_UBICACION_ORIGEN` int(11) DEFAULT NULL,
  `ID_UBICACION_DESTINO` int(11) DEFAULT NULL,
  `TIPO_MOVIMIENTO` varchar(20) NOT NULL,
  `CANTIDAD` int(11) NOT NULL,
  `FECHA` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_venta`
--

CREATE TABLE `ordenes_venta` (
  `ID_ORDEN` int(11) NOT NULL,
  `ID_USUARIO` int(11) NOT NULL,
  `ID_CLIENTE` int(11) NOT NULL,
  `FECHA_CREACION` datetime DEFAULT current_timestamp(),
  `ESTADO` varchar(20) DEFAULT 'PENDIENTE'
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `ID_PRODUCTO` int(11) NOT NULL,
  `ID_CATEGORIA` int(11) NOT NULL,
  `SKU` varchar(50) NOT NULL,
  `CODIGO_BARRAS` varchar(50) DEFAULT NULL,
  `NOMBRE` varchar(200) DEFAULT NULL,
  `PRECIO` decimal(10,2) NOT NULL,
  `STOCK_MINIMO` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `ID_PROVEEDOR` int(11) NOT NULL,
  `NOMBRE` varchar(150) NOT NULL,
  `RFC` varchar(20) DEFAULT NULL,
  `CONTACTO` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

CREATE TABLE `ubicaciones` (
  `ID_UBICACION` int(11) NOT NULL,
  `PASILLO` varchar(10) NOT NULL,
  `ESTANTE` varchar(10) NOT NULL,
  `NIVEL` varchar(10) NOT NULL,
  `CODIGO_UBICACION` varchar(50) GENERATED ALWAYS AS (concat(`PASILLO`,'-',`ESTANTE`,'-',`NIVEL`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_USUARIO` int(11) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `PASSWORD_USUARIO` varchar(256) NOT NULL,
  `ROL` varchar(20) NOT NULL DEFAULT 'OPERADOR',
  `ACTIVO` tinyint(1) DEFAULT 1
) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`ID_CATEGORIA`),
  ADD UNIQUE KEY `UQ_CATEGORIAS_NOMBRE` (`NOMBRE`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`ID_CLIENTE`),
  ADD UNIQUE KEY `UQ_RFC_CLIENTE` (`RFC_CLIENTE`);

--
-- Indices de la tabla `detalle_orden`
--
ALTER TABLE `detalle_orden`
  ADD PRIMARY KEY (`ID_DETALLE`),
  ADD UNIQUE KEY `UQ_DETALLE_ORDEN` (`ID_ORDEN`,`ID_PRODUCTO`),
  ADD KEY `FK_DETALLE_PRODUCTO` (`ID_PRODUCTO`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`ID_INVENTARIO`),
  ADD UNIQUE KEY `UQ_INVENTARIO_LOGICO` (`ID_PRODUCTO`,`ID_LOTE`,`ID_UBICACION`),
  ADD KEY `FK_INVENTARIO_LOTES` (`ID_LOTE`),
  ADD KEY `FK_INVENTARIO_UBICACIONES` (`ID_UBICACION`);

--
-- Indices de la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD PRIMARY KEY (`ID_LOTE`),
  ADD UNIQUE KEY `UQ_LOTES_CODIGO` (`ID_PRODUCTO`,`CODIGO_LOTE`),
  ADD KEY `FK_LOTES_PROVEEDORES` (`ID_PROVEEDOR`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`ID_MOVIMIENTO`),
  ADD KEY `FK_MOV_PRODUCTO` (`ID_PRODUCTO`),
  ADD KEY `FK_MOV_LOTE` (`ID_LOTE`),
  ADD KEY `FK_MOV_ORIGEN` (`ID_UBICACION_ORIGEN`),
  ADD KEY `FK_MOV_DESTINO` (`ID_UBICACION_DESTINO`),
  ADD KEY `FK_MOV_USUARIO` (`ID_USUARIO`);

--
-- Indices de la tabla `ordenes_venta`
--
ALTER TABLE `ordenes_venta`
  ADD PRIMARY KEY (`ID_ORDEN`),
  ADD KEY `FK_ORDENES_USUARIO` (`ID_USUARIO`),
  ADD KEY `FK_ORDENES_CLIENTE` (`ID_CLIENTE`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`ID_PRODUCTO`),
  ADD UNIQUE KEY `UQ_PRODUCTOS_SKU` (`SKU`),
  ADD KEY `FK_PRODUCTOS_CATEGORIAS` (`ID_CATEGORIA`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`ID_PROVEEDOR`),
  ADD UNIQUE KEY `UQ_PROVEEDORES_RFC` (`RFC`);

--
-- Indices de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`ID_UBICACION`),
  ADD UNIQUE KEY `UQ_UBICACIONES_FISICA` (`PASILLO`,`ESTANTE`,`NIVEL`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_USUARIO`),
  ADD UNIQUE KEY `UQ_USUARIOS_EMAIL` (`EMAIL`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `ID_CATEGORIA` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `ID_CLIENTE` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_orden`
--
ALTER TABLE `detalle_orden`
  MODIFY `ID_DETALLE` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `ID_INVENTARIO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lotes`
--
ALTER TABLE `lotes`
  MODIFY `ID_LOTE` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `ID_MOVIMIENTO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ordenes_venta`
--
ALTER TABLE `ordenes_venta`
  MODIFY `ID_ORDEN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `ID_PRODUCTO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `ID_PROVEEDOR` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `ID_UBICACION` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_USUARIO` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_orden`
--
ALTER TABLE `detalle_orden`
  ADD CONSTRAINT `FK_DETALLE_ORDEN` FOREIGN KEY (`ID_ORDEN`) REFERENCES `ordenes_venta` (`ID_ORDEN`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_DETALLE_PRODUCTO` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `FK_INVENTARIO_LOTES` FOREIGN KEY (`ID_LOTE`) REFERENCES `lotes` (`ID_LOTE`),
  ADD CONSTRAINT `FK_INVENTARIO_PRODUCTOS` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`),
  ADD CONSTRAINT `FK_INVENTARIO_UBICACIONES` FOREIGN KEY (`ID_UBICACION`) REFERENCES `ubicaciones` (`ID_UBICACION`);

--
-- Filtros para la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD CONSTRAINT `FK_LOTES_PRODUCTOS` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`),
  ADD CONSTRAINT `FK_LOTES_PROVEEDORES` FOREIGN KEY (`ID_PROVEEDOR`) REFERENCES `proveedores` (`ID_PROVEEDOR`);

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `FK_MOV_DESTINO` FOREIGN KEY (`ID_UBICACION_DESTINO`) REFERENCES `ubicaciones` (`ID_UBICACION`),
  ADD CONSTRAINT `FK_MOV_LOTE` FOREIGN KEY (`ID_LOTE`) REFERENCES `lotes` (`ID_LOTE`),
  ADD CONSTRAINT `FK_MOV_ORIGEN` FOREIGN KEY (`ID_UBICACION_ORIGEN`) REFERENCES `ubicaciones` (`ID_UBICACION`),
  ADD CONSTRAINT `FK_MOV_PRODUCTO` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `productos` (`ID_PRODUCTO`),
  ADD CONSTRAINT `FK_MOV_USUARIO` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuarios` (`ID_USUARIO`);

--
-- Filtros para la tabla `ordenes_venta`
--
ALTER TABLE `ordenes_venta`
  ADD CONSTRAINT `FK_ORDENES_CLIENTE` FOREIGN KEY (`ID_CLIENTE`) REFERENCES `clientes` (`ID_CLIENTE`),
  ADD CONSTRAINT `FK_ORDENES_USUARIO` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuarios` (`ID_USUARIO`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `FK_PRODUCTOS_CATEGORIAS` FOREIGN KEY (`ID_CATEGORIA`) REFERENCES `categorias` (`ID_CATEGORIA`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
