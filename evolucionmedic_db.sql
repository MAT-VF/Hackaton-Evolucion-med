-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-05-2026 a las 04:38:43
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
-- Base de datos: `evolucionmedic_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`, `descripcion`) VALUES
(1, 'Instrumental Médico', 'Equipamiento médico'),
(2, 'Odontológico', 'Instrumental odontológico'),
(3, 'Quirúrgico', 'Instrumental quirúrgico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL,
  `fecha_compra` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `estado_compra` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id_detalle_compra` int(11) NOT NULL,
  `id_compra` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id_movimiento` int(11) NOT NULL,
  `tipo_movimiento` varchar(50) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `observacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `stock_minimo` int(11) DEFAULT 5,
  `codigo_barras` varchar(100) DEFAULT NULL,
  `lote` varchar(100) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio_compra`, `precio_venta`, `stock`, `stock_minimo`, `codigo_barras`, `lote`, `id_categoria`, `id_proveedor`) VALUES
(1, 'Pinzas Kelly', 'Instrumental quirúrgico', 80.00, 120.00, 20, 5, '10001', 'LT001', 3, 1),
(2, 'Porta Agujas Matiu', 'Instrumental odontológico', 100.00, 150.00, 15, 5, '10002', 'LT002', 2, 2),
(3, 'Tijera de Mayo', 'Cirugía', 120.00, 180.00, 10, 3, '10003', 'LT003', 3, 1),
(4, 'Bandejas Médicas', 'Acero inoxidable', 90.00, 140.00, 12, 4, '10004', 'LT004', 1, 3),
(5, 'Pinza Mosquito Recta', 'Instrumental quirúrgico de acero inoxidable', 45.00, 80.00, 25, 5, '20001', 'MOS001', 3, 1),
(6, 'Pinza Kelly Curva', 'Pinza quirúrgica para procedimientos médicos', 55.00, 95.00, 18, 5, '20002', 'KEL001', 3, 1),
(7, 'Porta Agujas Mayo Hegar', 'Instrumental para sutura quirúrgica', 90.00, 140.00, 12, 3, '20003', 'MAY001', 3, 1),
(8, 'Tijera Lister', 'Tijera médica para vendajes', 60.00, 100.00, 15, 4, '20004', 'LIS001', 1, 1),
(9, 'Bisturí Mango #3', 'Instrumental quirúrgico de precisión', 35.00, 70.00, 30, 5, '20005', 'BIS001', 3, 1),
(10, 'Riñonera Acero Inoxidable', 'Contenedor médico quirúrgico', 75.00, 120.00, 10, 2, '20006', 'RIN001', 1, 3),
(11, 'Bandeja Quirúrgica', 'Bandeja de acero inoxidable médica', 110.00, 180.00, 8, 2, '20007', 'BAN001', 1, 3),
(12, 'Tambor Quirúrgico', 'Tambor médico para esterilización', 250.00, 350.00, 5, 1, '20008', 'TAM001', 1, 3),
(13, 'Equipo Dental Básico', 'Kit odontológico profesional', 600.00, 850.00, 6, 2, '20009', 'DEN001', 2, 2),
(14, 'Sillón Odontológico', 'Equipo odontológico completo', 4500.00, 6200.00, 2, 1, '20010', 'SIL001', 2, 2),
(15, 'Lámpara Cialítica LED', 'Iluminación quirúrgica profesional', 1800.00, 2600.00, 4, 1, '20011', 'LAMP001', 1, 1),
(16, 'Autoclave Médico', 'Equipo de esterilización médica', 3200.00, 4500.00, 3, 1, '20012', 'AUTO001', 1, 1),
(17, 'Monitor de Signos Vitales', 'Equipo de monitoreo médico', 2800.00, 3900.00, 4, 1, '20013', 'MON001', 1, 1),
(18, 'Oxímetro de Pulso', 'Dispositivo médico portátil', 120.00, 220.00, 20, 5, '20014', 'OXI001', 1, 1),
(19, 'Nebulizador Profesional', 'Equipo respiratorio médico', 280.00, 420.00, 9, 2, '20015', 'NEB001', 1, 1),
(20, 'Camilla Hospitalaria', 'Camilla médica ajustable', 2500.00, 3400.00, 3, 1, '20016', 'CAM001', 1, 1),
(21, 'Guantes Quirúrgicos', 'Caja de guantes médicos estériles', 40.00, 75.00, 50, 10, '20017', 'GUA001', 1, 3),
(22, 'Mascarillas KN95', 'Protección médica respiratoria', 25.00, 50.00, 80, 15, '20018', 'MAS001', 1, 3),
(23, 'Jeringas Desechables', 'Insumo médico descartable', 15.00, 35.00, 100, 20, '20019', 'JER001', 1, 3),
(24, 'Set de Suturas', 'Kit médico quirúrgico', 90.00, 150.00, 14, 3, '20020', 'SUT001', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre_empresa` varchar(100) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre_empresa`, `telefono`, `direccion`, `correo`) VALUES
(1, 'BioMedical Bolivia', '77777777', 'La Paz', 'bio@gmail.com'),
(2, 'Dental Pro', '66666666', 'La Paz', 'dental@gmail.com'),
(3, 'EvolucionMedic', '76543210', 'La Paz', 'evolucion@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `rol` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `usuario`, `contraseña`, `rol`, `estado`) VALUES
(1, 'Administrador', 'admin', '123456', 'ADMIN', 1),
(2, 'Administrador', 'admin', '123456', 'ADMIN', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id_detalle_compra`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`),
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
