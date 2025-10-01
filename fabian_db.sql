-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 01, 2025 at 03:14 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fabian_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `imagen` text NOT NULL,
  `estado` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `imagen`, `estado`) VALUES
(1, 'Lapiceros de plástico', 'lapiceros-plastico.jpg', 1),
(2, 'Tarjeteros metálicos', 'tarjeteros-metalicos.jpg', 1),
(3, 'Resaltadores', 'resaltadores.jpg', 1),
(4, 'Antiestrés', 'antiestres.jpg', 1),
(5, 'Llaveros', 'llaveros.jpg', 1),
(6, 'Escritorio', 'escritorio.jpg', 1),
(7, 'Ecológico', 'ecologico.jpg', 1),
(8, 'Memorias USB', 'memorias-usb.jpg', 1),
(9, 'Tomatodos', 'tomatodos.jpg', 1),
(10, 'Tazas', 'tazas.jpg', 1),
(11, 'Mugs', 'mugs.jpg', 1),
(12, 'Personalizado', 'personalizado.jpg', 1),
(13, 'Canguros', 'canguros.jpg', 1),
(14, 'Bolsos', 'bolsos.jpg', 1),
(15, 'Mochila', 'mochila.jpg', 1),
(16, 'Textil', 'textil.jpg', 1),
(17, 'Loncheras', 'loncheras.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `estado` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apellidos`, `email`, `telefono`, `direccion`, `password`, `fecha_registro`, `foto_perfil`, `estado`) VALUES
(1, 'Juan', 'Pérez García', 'juan.perez@email.com', '+51 999 888 777', 'Av. Principal 123, Lima', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2023-01-15 10:30:00', NULL, 1),
(2, 'María', 'González López', 'maria.gonzalez@email.com', '+51 987 654 321', 'Jr. Los Olivos 456, Lima', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2023-02-20 14:15:00', NULL, 1),
(3, 'Alexis Josue', 'Lopez Salinas', 'lopez.salinas.alexis02@gmail.com', '942308812', 'Calle avelardo lote 12 mz34', '$2y$10$cwX7fkSSroV76434e5dlju56QQczK0nxuR6P4MJiZkUsrOs2UAUAu', '2025-09-06 03:23:50', NULL, 1),
(4, 'Alexis Josue', 'Lopez Salinas', 'alexisjosuelopezsalinas77@gmail.com', '942308812', 'Calle avelardo lote 12 mz34', '$2y$10$hV61MDuz6Ml3Lplj5JQBnuv9tmPOahmBK27uv87ccFvabqLT8uvJa', '2025-10-01 02:16:22', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id_detalle_venta` int NOT NULL,
  `id_venta` int NOT NULL,
  `id_producto` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detalle_venta`
--

INSERT INTO `detalle_venta` (`id_detalle_venta`, `id_venta`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(5, 6, 24, 2, 25.00, 50.00),
(6, 6, 23, 1, 55.00, 55.00),
(7, 7, 20, 1, 65.00, 65.00);

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id_producto` int NOT NULL,
  `id_categoria` int NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `imagen` varchar(255) DEFAULT 'default.jpg',
  `destacado` int NOT NULL DEFAULT '0',
  `estado` int NOT NULL DEFAULT '1',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id_producto`, `id_categoria`, `nombre`, `descripcion`, `precio`, `stock`, `imagen`, `destacado`, `estado`, `fecha_creacion`) VALUES
(9, 1, 'Lapicero Promocional Azul', 'Lapicero de plástico con logo personalizable', 2.50, 500, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(10, 2, 'Tarjetero Metálico Ejecutivo', 'Tarjetero de aluminio con grabado láser', 25.00, 100, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(11, 3, 'Set Resaltadores 4 Colores', 'Pack de resaltadores fluorescentes', 8.90, 200, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(12, 4, 'Pelota Antiestrés', 'Pelota de goma para aliviar el estrés', 5.50, 150, 'assets/lapiceros.png', 0, 1, '2025-09-30 21:09:02'),
(13, 5, 'Llavero Metálico Personalizado', 'Llavero con grabado personalizado', 12.00, 300, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(14, 6, 'Set de Escritorio Ejecutivo', 'Organizador de escritorio con accesorios', 45.00, 50, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(15, 7, 'Bolsa Ecológica de Algodón', 'Bolsa reutilizable 100% algodón', 15.00, 200, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(16, 8, 'Memoria USB 16GB', 'Memoria USB personalizable con logo', 35.00, 100, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(17, 9, 'Tomatodo Deportivo 750ml', 'Botella deportiva con logo personalizado', 18.50, 150, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(18, 10, 'Taza Cerámica Blanca', 'Taza de cerámica para sublimación', 12.00, 200, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(19, 11, 'Mug Térmico Acero', 'Mug térmico de acero inoxidable', 28.00, 80, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(20, 12, 'Kit Personalización Premium', 'Kit completo para personalización', 65.00, 30, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(21, 13, 'Canguro Deportivo', 'Canguro con múltiples compartimentos', 22.00, 120, 'assets/lapiceros.png', 0, 1, '2025-09-30 21:09:02'),
(22, 14, 'Bolso Ejecutivo', 'Bolso de cuero sintético para laptop', 85.00, 40, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(23, 15, 'Mochila Promocional', 'Mochila con logo bordado', 55.00, 100, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02'),
(24, 16, 'Polo Textil Personalizado', 'Polo de algodón con estampado', 25.00, 20, 'assets/lapiceros.png', 0, 1, '2025-09-30 21:09:02'),
(25, 17, 'Lonchera Térmica', 'Lonchera con aislamiento térmico', 32.00, 80, 'assets/lapiceros.png', 1, 1, '2025-09-30 21:09:02');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','usuario','subadmin') NOT NULL DEFAULT 'usuario',
  `permisos` text,
  `estado` int NOT NULL DEFAULT '1',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `nombre`, `direccion`, `telefono`, `email`, `password`, `rol`, `permisos`, `estado`, `fecha_creacion`) VALUES
(1, 'Administrador', 'Oficina Principal', '+51 999 888 777', 'admin@miniplus.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 1, '2023-01-01 15:00:00'),
(2, 'Alexis Josue Lopez Salinas', '', '942308812', 'springrandalf@gmail.com', '$2y$10$UufPhFzQ2EXJD.5/jw/vPOMe7.NXTJIy4.G.2DhyUdLZuzF.hUKpa', 'admin', NULL, 1, '2025-09-05 22:15:37'),
(3, 'Rafael Eduardo', '', '', 'alexisjosuelopezsalinas77@gmail.com', '$2y$10$a.XVvA7ZAO9mdqamcAesmO.k2iH4bwAN1tnX.KDM/IocT9Q4hrtiG', 'subadmin', '{\"productos\":1,\"categorias\":0,\"clientes\":0,\"ventas\":1,\"reportes\":1,\"configuracion\":0}', 1, '2025-09-05 22:16:02'),
(4, 'Luis Alvarado Gomez', '', '', 'alvaradogomez23@gmail.com', '$2y$10$uctzJskL/P17FTzWKq.6X.f./3y7pcsDy9nMDbmBAk85QxcYYVsTO', 'admin', NULL, 1, '2025-09-30 21:30:02');

-- --------------------------------------------------------

--
-- Table structure for table `venta`
--

CREATE TABLE `venta` (
  `id_venta` int NOT NULL,
  `id_cliente` int NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','completada','cancelada') NOT NULL DEFAULT 'completada',
  `metodo_pago` varchar(50) DEFAULT 'efectivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `venta`
--

INSERT INTO `venta` (`id_venta`, `id_cliente`, `fecha`, `total`, `estado`, `metodo_pago`) VALUES
(1, 1, '2023-03-15 16:30:00', 13.90, 'completada', 'efectivo'),
(2, 2, '2023-03-16 10:45:00', 17.90, 'completada', 'tarjeta'),
(3, 1, '2023-03-17 14:20:00', 25.50, 'completada', 'efectivo'),
(4, 4, '2025-10-01 03:19:30', 105.00, 'completada', 'yape'),
(5, 4, '2025-10-01 03:19:33', 105.00, 'completada', 'yape'),
(6, 4, '2025-10-01 03:21:11', 105.00, 'completada', 'yape'),
(7, 4, '2025-10-01 19:34:15', 75.00, 'completada', 'yape');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id_detalle_venta`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id_detalle_venta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `venta`
--
ALTER TABLE `venta`
  MODIFY `id_venta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE;

--
-- Constraints for table `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
