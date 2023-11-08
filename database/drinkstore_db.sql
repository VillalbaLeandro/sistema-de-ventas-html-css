-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-11-2023 a las 22:27:41
-- Versión del servidor: 8.0.30
-- Versión de PHP: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `drinkstore_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`) VALUES
(1, 'cerveza'),
(2, 'vino'),
(3, 'whisky'),
(4, 'licor'),
(5, 'vodka'),
(6, 'gaseosa'),
(7, 'agua'),
(8, 'agua saborizada'),
(9, 'snack'),
(10, 'cigarrillo'),
(11, 'hielo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_cliente`
--

CREATE TABLE `categoria_cliente` (
  `id` int NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categoria_cliente`
--

INSERT INTO `categoria_cliente` (`id`, `nombre`) VALUES
(1, 'Responsable Inscripto'),
(2, 'Responsable NO Inscripto'),
(3, 'Monotributista'),
(4, 'Exento'),
(5, 'Consumidor Final');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `direccion` varchar(45) NOT NULL,
  `fechaNacimiento` datetime NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) NOT NULL,
  `cuil_cuit` varchar(45) NOT NULL,
  `dni` varchar(45) NOT NULL,
  `categoria_cliente_id` int NOT NULL,
  `pass` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `nombre`, `apellido`, `direccion`, `fechaNacimiento`, `email`, `telefono`, `cuil_cuit`, `dni`, `categoria_cliente_id`, `pass`) VALUES
(1, 'leandro', 'villalba', 'Av. Blas parera 6155', '1993-12-02 00:00:00', 'exemprary@hotmail.com', '03764219829', '20375838037', '37583803', 2, '12345'),
(3, 'jorge', 'josemaria', 'Herrera 4278', '2023-08-24 00:00:00', 'pruebamail@gmail.com', '2556332568', '23445556667', '24465183', 4, '12345'),
(4, 'ruben', 'balanda', 'Av. Blas parera 6155', '2023-10-14 00:00:00', 'pruebamail@gmail.com', '03764219829', '20993334447', '24465183', 3, '123456'),
(8, 'pepe', 'vera', 'collar de las cruces', '2016-02-12 00:00:00', 'exemprary@gmail.com', '123456789', '20-565658-8888', '12345678', 5, '1234'),
(9, 'Caraca', 'roberto', 'Herrera 4278', '1980-02-02 00:00:00', 'exemprary@gmail.com', '(376) 421-9829', '20-565658-8888', '37583803', 5, '1234'),
(10, 'Carmen', 'Diaz', 'Herrera 4278', '1995-09-10 00:00:00', 'exemprary@gmail.com', '123456789', '20-565658-8888', '37583803', 4, '1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id` int NOT NULL,
  `fecha` datetime NOT NULL,
  `proveedor_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `fecha`, `proveedor_id`) VALUES
(6, '2023-11-05 21:45:17', 1),
(7, '2023-11-05 21:46:07', 1),
(17, '2023-11-06 18:29:58', 1),
(18, '2023-11-06 18:56:20', 1),
(19, '2023-11-07 11:54:43', 1),
(20, '2023-11-07 19:15:07', 1),
(21, '2023-11-07 19:16:45', 1),
(22, '2023-11-08 18:29:10', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `compra_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`compra_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(6, 1, 20, 1000.00),
(7, 1, 20, 1000.00),
(17, 12, 100, 350.00),
(18, 1, 50, 1200.00),
(19, 1, 20, 2000.00),
(20, 1, 10, 2100.00),
(21, 1, 10, 2100.00),
(22, 1, 20, 2500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `venta_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`venta_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(4, 1, 1, 1000.00),
(5, 1, 2, 1000.00),
(6, 1, 1, 1000.00),
(7, 1, 1, 1000.00),
(8, 1, 1, 1000.00),
(9, 1, 1, 1000.00),
(10, 1, 1, 1000.00),
(11, 1, 1, 1000.00),
(12, 1, 1, 1000.00),
(13, 1, 1, 1000.00),
(14, 1, 1, 1000.00),
(14, 10, 3, 1100.00),
(15, 1, 1, 1000.00),
(16, 1, 1, 1000.00),
(17, 1, 1, 1000.00),
(18, 1, 1, 1000.00),
(19, 1, 1, 1000.00),
(20, 1, 1, 1000.00),
(21, 1, 1, 1000.00),
(22, 1, 1, 1000.00),
(23, 10, 1, 1100.00),
(24, 1, 2, 1000.00),
(24, 10, 1, 1100.00),
(24, 11, 1, 123.00),
(25, 1, 1, 1000.00),
(26, 1, 1, 1000.00),
(27, 10, 1, 1100.00),
(28, 1, 1, 1000.00),
(29, 1, 1, 1000.00),
(30, 1, 1, 1000.00),
(31, 12, 1, 700.00),
(32, 1, 1, 3800.00),
(33, 1, 2, 3800.00),
(34, 1, 1, 3900.00),
(35, 1, 1, 3900.00),
(36, 1, 1, 3900.00),
(37, 1, 1, 3900.00),
(38, 1, 1, 3900.00),
(39, 1, 1, 4000.00),
(40, 1, 1, 4000.00),
(41, 1, 2, 4000.00),
(42, 1, 3, 4000.00),
(43, 1, 1, 4000.00),
(44, 1, 4, 4000.00),
(45, 1, 2, 4000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `efectivocaja`
--

CREATE TABLE `efectivocaja` (
  `id` int NOT NULL,
  `fecha` datetime NOT NULL,
  `monto` decimal(10,0) NOT NULL,
  `descripcion` varchar(45) DEFAULT 'sin detallar',
  `entrada_salida_id` int NOT NULL,
  `venta_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada_salida`
--

CREATE TABLE `entrada_salida` (
  `id` int NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iva`
--

CREATE TABLE `iva` (
  `id` int UNSIGNED NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `iva`
--

INSERT INTO `iva` (`id`, `nombre`) VALUES
(1, '21%'),
(2, 'Sin iva');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mediopago`
--

CREATE TABLE `mediopago` (
  `id` int NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `mediopago`
--

INSERT INTO `mediopago` (`id`, `nombre`) VALUES
(1, 'efectivo'),
(2, 'tarjeta'),
(3, 'mercado pago'),
(4, 'cheque'),
(5, 'Tarjeta Credito'),
(6, 'Tarjeta Debito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_producto`
--

CREATE TABLE `movimiento_producto` (
  `id` int NOT NULL,
  `producto_id` int NOT NULL,
  `tipo` enum('Compra','Venta') NOT NULL,
  `compra_id` int DEFAULT NULL,
  `venta_id` int DEFAULT NULL,
  `cantidad` int NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `movimiento_producto`
--

INSERT INTO `movimiento_producto` (`id`, `producto_id`, `tipo`, `compra_id`, `venta_id`, `cantidad`, `fecha`) VALUES
(1, 1, 'Compra', 18, NULL, 50, '2023-11-06 21:58:05'),
(2, 13, 'Compra', 18, NULL, 20, '2023-11-07 14:35:12'),
(3, 1, 'Compra', 18, NULL, 100, '2023-11-07 14:37:03'),
(4, 1, 'Compra', 18, NULL, 20, '2023-11-07 14:54:43'),
(5, 12, 'Venta', NULL, 31, 1, '2023-11-07 15:10:38'),
(6, 1, 'Venta', NULL, 33, 2, '2023-11-07 15:22:59'),
(7, 1, 'Compra', 19, NULL, 10, '2023-11-07 22:15:07'),
(8, 1, 'Compra', 20, NULL, 10, '2023-11-07 22:16:45'),
(9, 1, 'Venta', NULL, 35, 1, '2023-11-08 14:04:22'),
(10, 1, 'Venta', NULL, 37, 1, '2023-11-08 21:22:01'),
(11, 1, 'Compra', 21, NULL, 20, '2023-11-08 21:29:10'),
(12, 1, 'Venta', NULL, 39, 1, '2023-11-08 21:29:30'),
(13, 1, 'Venta', NULL, 41, 2, '2023-11-08 21:30:14'),
(14, 1, 'Venta', NULL, 42, 3, '2023-11-08 21:30:41'),
(15, 1, 'Venta', NULL, 43, 1, '2023-11-08 21:31:39'),
(16, 1, 'Venta', NULL, 44, 4, '2023-11-08 21:39:26'),
(17, 1, 'Venta', NULL, 45, 2, '2023-11-08 18:50:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `precio_venta` decimal(10,0) NOT NULL,
  `precio_costo` decimal(10,0) NOT NULL,
  `stock` int NOT NULL,
  `stock_minimo` varchar(45) DEFAULT '2',
  `categoria_id` int NOT NULL,
  `codigo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `descripcion`, `precio_venta`, `precio_costo`, `stock`, `stock_minimo`, `categoria_id`, `codigo`) VALUES
(1, 'Brahma', 'brahma 1 litro retornable', 4000, 2500, 111, '5', 1, '2020'),
(10, 'quilmes', 'quilmes de 1l', 1600, 900, 24, '2', 1, '2054'),
(11, 'vino tinto malbek tercera edicion', 'botella de vidrio 750 cosecha tardia', 123, 23, 32, '2', 2, '1212'),
(12, 'Eight', 'Eight comun x20', 700, 350, 129, '2', 10, '2322'),
(13, 'Sky - frutilla', 'Vodka sky 1lt', 2200, 1000, 20, '2', 5, '8095');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id` int NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `direccion` varchar(45) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id`, `nombre`, `direccion`, `telefono`, `email`) VALUES
(1, 'Manaos', 'ruta 12', '123456', 'manaos@manaos.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'cajero'),
(3, 'vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `tel` varchar(45) NOT NULL,
  `direccion` varchar(45) DEFAULT 'desconocida',
  `rol_id` int NOT NULL DEFAULT '3',
  `email` varchar(45) DEFAULT NULL,
  `pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `tel`, `direccion`, `rol_id`, `email`, `pass`) VALUES
(1, 'admin', 'admina', '0111111', '0000', 1, 'exe@gmail.com', '$2y$10$Cu3XmVtwuLO0WU2IhrFmmuzg3y1LoVEPnAudjmc/vcWTNq.g7YsHC'),
(11, 'leandroAAA', 'Villalba', '55855856', 'Herrera 4278', 3, 'exemprary@gmail.com', '$2y$10$0Wo.NR5cs.VbGvgdAG39r.QdzcDv44LhdIhgYEAaE8O8fKmPxMhc6'),
(14, 'vendedor', 'ApeVendedor', '123456789', 'Av. Blas parera 6155', 3, 'exemprary@gmail.com', '$2y$10$F2AXBcRv/uBsMkXYjuc20ORdTczCmiR5TIe1EJZ46LXy9Fy9fwdEG'),
(15, 'leandro', 'Villalba', '123456', 'Herrera 4278', 3, 'exemprary@gmail.com', '$2y$10$WfWCHIXBscSl16k8ZGQDneuw6wv8XbdmYXvwK1pHwahbnlnIM58GK'),
(16, 'vendedor3', 'Villalbaaaaa', '11111111122222', 'Herrera 4278', 3, 'exemprary@gmail.com', '$2y$10$2dBmgV5tNTLRF62K.LbRRupVhh.mFwmEZa0ql0t6T/c86k9jbLPX.'),
(17, 'vendedor4', 'Villalba', '12222222', 'Herrera 4278', 3, 'exemprary@gmail.com', '$2y$10$/mH.k.bnOfE.KVTDyzpSpOY./2L4x5YqPT.YVyUUah2Vj7k/ciNRe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id` int NOT NULL,
  `fecha` datetime NOT NULL,
  `medioPago_id` int NOT NULL DEFAULT '1',
  `cliente_id` int NOT NULL,
  `iva_id` int UNSIGNED NOT NULL DEFAULT '2',
  `usuario_id` int NOT NULL,
  `total` decimal(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id`, `fecha`, `medioPago_id`, `cliente_id`, `iva_id`, `usuario_id`, `total`) VALUES
(1, '2023-10-05 20:00:56', 1, 1, 2, 1, 1000.00),
(2, '2023-10-05 20:01:37', 1, 1, 2, 1, 1000.00),
(3, '2023-10-05 20:02:45', 1, 1, 2, 1, 1000.00),
(4, '2023-10-05 20:03:07', 1, 1, 2, 1, 1000.00),
(5, '2023-10-05 20:03:29', 1, 1, 2, 1, 2000.00),
(6, '2023-10-05 20:03:44', 1, 1, 2, 1, 1000.00),
(7, '2023-10-06 07:25:10', 1, 3, 2, 1, 1000.00),
(8, '2023-10-06 07:25:55', 1, 1, 2, 1, 1000.00),
(9, '2023-10-06 07:27:08', 1, 1, 2, 1, 1000.00),
(10, '2023-10-06 07:27:42', 1, 1, 2, 1, 1000.00),
(11, '2023-10-06 07:51:51', 1, 1, 2, 1, 1000.00),
(12, '2023-10-06 07:54:00', 1, 4, 2, 1, 2560.00),
(13, '2023-10-06 10:47:02', 1, 1, 2, 1, 1000.00),
(14, '2023-10-06 10:49:47', 1, 4, 2, 1, 4300.00),
(15, '2023-10-09 20:01:51', 1, 4, 2, 1, 1000.00),
(16, '2023-10-12 11:50:51', 1, 3, 2, 1, 1000.00),
(17, '2023-10-12 11:51:07', 1, 4, 2, 1, 1000.00),
(18, '2023-10-12 11:51:21', 1, 1, 2, 1, 1000.00),
(19, '2023-10-12 11:51:29', 1, 1, 2, 1, 1000.00),
(20, '2023-10-12 14:37:49', 1, 3, 2, 14, 1000.00),
(21, '2023-10-12 14:41:15', 1, 8, 2, 14, 1000.00),
(22, '2023-10-12 14:41:32', 1, 9, 2, 14, 1000.00),
(23, '2023-10-12 14:41:56', 1, 10, 2, 14, 1100.00),
(24, '2023-10-12 15:44:19', 1, 3, 2, 14, 3223.00),
(25, '2023-10-12 16:57:19', 1, 3, 2, 14, 1000.00),
(26, '2023-10-14 01:36:23', 1, 1, 2, 14, 1000.00),
(27, '2023-10-14 01:37:12', 1, 8, 2, 16, 1100.00),
(28, '2023-10-14 01:37:55', 1, 1, 2, 17, 1000.00),
(29, '2023-10-14 01:38:37', 1, 9, 2, 15, 1000.00),
(30, '2023-10-14 01:41:52', 1, 8, 2, 11, 1000.00),
(31, '2023-11-07 12:10:38', 1, 3, 2, 1, 700.00),
(32, '2023-11-07 12:22:25', 1, 9, 2, 1, 3800.00),
(33, '2023-11-07 12:22:59', 1, 1, 2, 1, 7600.00),
(34, '2023-11-08 11:02:52', 1, 3, 2, 1, 3900.00),
(35, '2023-11-08 11:04:22', 1, 4, 2, 1, 3900.00),
(36, '2023-11-08 11:28:48', 1, 9, 2, 1, 3900.00),
(37, '2023-11-08 18:22:01', 1, 8, 2, 1, 3900.00),
(38, '2023-11-08 18:27:26', 1, 10, 2, 1, 3900.00),
(39, '2023-11-08 18:29:30', 1, 3, 2, 1, 4000.00),
(40, '2023-11-08 18:29:51', 1, 3, 2, 1, 4000.00),
(41, '2023-11-08 18:30:14', 1, 1, 2, 1, 8000.00),
(42, '2023-11-08 18:30:41', 1, 8, 2, 1, 12000.00),
(43, '2023-11-08 18:31:39', 1, 8, 2, 1, 4000.00),
(44, '2023-11-08 18:39:26', 1, 9, 2, 1, 16000.00),
(45, '2023-11-08 18:50:34', 1, 1, 2, 1, 8000.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categoria_cliente`
--
ALTER TABLE `categoria_cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cliente_categoria_cliente1_idx` (`categoria_cliente_id`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_compra_proveedor_idx` (`proveedor_id`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`compra_id`,`producto_id`),
  ADD KEY `fk_detalle_compra_compra1_idx` (`compra_id`),
  ADD KEY `fk_detalle_compra_producto1_idx` (`producto_id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`venta_id`,`producto_id`),
  ADD KEY `fk_venta_has_producto_producto1_idx` (`producto_id`),
  ADD KEY `fk_venta_has_producto_venta1_idx` (`venta_id`);

--
-- Indices de la tabla `efectivocaja`
--
ALTER TABLE `efectivocaja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_efectivoCaja_entrada_salida1_idx` (`entrada_salida_id`),
  ADD KEY `fk_efectivocaja_venta1_idx` (`venta_id`);

--
-- Indices de la tabla `entrada_salida`
--
ALTER TABLE `entrada_salida`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `iva`
--
ALTER TABLE `iva`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mediopago`
--
ALTER TABLE `mediopago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimiento_producto`
--
ALTER TABLE `movimiento_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_movimiento_producto_producto1_idx` (`producto_id`),
  ADD KEY `fk_movimiento_producto_compra1_idx` (`compra_id`),
  ADD KEY `fk_movimiento_producto_venta1_idx` (`venta_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_producto_categoria1_idx` (`categoria_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario_rol1_idx` (`rol_id`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_venta_medioPago_idx` (`medioPago_id`),
  ADD KEY `fk_venta_cliente1_idx` (`cliente_id`),
  ADD KEY `fk_venta_iva1_idx` (`iva_id`),
  ADD KEY `fk_venta_usuario1_idx` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `categoria_cliente`
--
ALTER TABLE `categoria_cliente`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `efectivocaja`
--
ALTER TABLE `efectivocaja`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entrada_salida`
--
ALTER TABLE `entrada_salida`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `iva`
--
ALTER TABLE `iva`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mediopago`
--
ALTER TABLE `mediopago`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `movimiento_producto`
--
ALTER TABLE `movimiento_producto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_categoria_cliente1` FOREIGN KEY (`categoria_cliente_id`) REFERENCES `categoria_cliente` (`id`);

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `fk_compra_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`id`);

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `fk_detalle_compra_compra1` FOREIGN KEY (`compra_id`) REFERENCES `compra` (`id`),
  ADD CONSTRAINT `fk_detalle_compra_producto1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_venta_has_producto_producto1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_venta_has_producto_venta1` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `efectivocaja`
--
ALTER TABLE `efectivocaja`
  ADD CONSTRAINT `fk_efectivoCaja_entrada_salida1` FOREIGN KEY (`entrada_salida_id`) REFERENCES `entrada_salida` (`id`),
  ADD CONSTRAINT `fk_efectivocaja_venta1` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`id`);

--
-- Filtros para la tabla `movimiento_producto`
--
ALTER TABLE `movimiento_producto`
  ADD CONSTRAINT `fk_movimiento_producto_compra1` FOREIGN KEY (`compra_id`) REFERENCES `compra` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_movimiento_producto_producto1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `fk_movimiento_producto_venta1` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_categoria1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_rol1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_venta_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `fk_venta_iva1` FOREIGN KEY (`iva_id`) REFERENCES `iva` (`id`),
  ADD CONSTRAINT `fk_venta_medioPago` FOREIGN KEY (`medioPago_id`) REFERENCES `mediopago` (`id`),
  ADD CONSTRAINT `fk_venta_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
