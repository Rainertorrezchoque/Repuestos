-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-11-2025 a las 00:58:36
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
-- Base de datos: `repuestos_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `target_table` varchar(100) DEFAULT NULL,
  `target_id` bigint(20) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `branches`
--

INSERT INTO `branches` (`id`, `code`, `name`, `address`, `phone`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'A', 'Sucursal Aquí', 'Av Principal 123', '+59170000000', 1, '2025-11-19 14:44:06', NULL),
(2, 'C', 'Sucursal Centro', 'Calle Centro 45', '+59170000001', 1, '2025-11-19 14:44:06', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cash_sessions`
--

CREATE TABLE `cash_sessions` (
  `id` bigint(20) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `opened_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `closed_at` timestamp NULL DEFAULT NULL,
  `opening_amount` decimal(12,2) DEFAULT 0.00,
  `closing_amount` decimal(12,2) DEFAULT NULL,
  `is_closed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`, `created_at`) VALUES
(1, 'Frenos', NULL, '2025-11-19 14:44:06'),
(2, 'Motor', NULL, '2025-11-19 14:44:06'),
(3, 'Suspensión y Dirección', NULL, '2025-11-20 21:45:37'),
(4, 'Sistema Eléctrico', NULL, '2025-11-20 21:45:37'),
(5, 'Transmisión y Embrague', NULL, '2025-11-20 21:45:37'),
(6, 'Refrigeración', NULL, '2025-11-20 21:45:37'),
(7, 'Filtros y Aceites', NULL, '2025-11-20 21:45:37'),
(8, 'Carrocería', NULL, '2025-11-20 21:45:37'),
(9, 'Encendido', NULL, '2025-11-20 21:45:37'),
(10, 'Accesorios', NULL, '2025-11-20 21:45:37'),
(11, 'Pastillas de Freno', 1, '2025-11-20 21:45:37'),
(12, 'Discos y Tambores', 1, '2025-11-20 21:45:37'),
(13, 'Cilindros Maestros', 1, '2025-11-20 21:45:37'),
(14, 'Líquido de Freno', 1, '2025-11-20 21:45:37'),
(15, 'Correas de Distribución', 2, '2025-11-20 21:45:37'),
(16, 'Empaquetaduras', 2, '2025-11-20 21:45:37'),
(17, 'Pistones y Anillos', 2, '2025-11-20 21:45:37'),
(18, 'Bombas de Aceite', 2, '2025-11-20 21:45:37'),
(19, 'Bujías', 2, '2025-11-20 21:45:37'),
(20, 'Amortiguadores', NULL, '2025-11-20 21:45:37'),
(21, 'Baterías', NULL, '2025-11-20 21:45:37'),
(22, 'Rodamientos', NULL, '2025-11-20 21:45:37'),
(23, 'Faros y Luces', NULL, '2025-11-20 21:45:37'),
(24, 'Llantas y Neumáticos', NULL, '2025-11-20 21:45:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventories`
--

CREATE TABLE `inventories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `location` varchar(100) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventories`
--

INSERT INTO `inventories` (`id`, `product_id`, `branch_id`, `quantity`, `location`, `last_updated`) VALUES
(1, 1, 1, -1, 'Estante 4B', '2025-11-20 22:22:40'),
(2, 1, 2, 2, 'Estante 4B', '2025-11-19 14:44:06'),
(3, 2, 1, 8, 'Estante 2A', '2025-11-20 23:01:32'),
(4, 2, 2, 0, 'Estante 2A', '2025-11-19 14:44:06'),
(5, 4, 2, 4, 'Estante 4D', '2025-11-20 22:34:09'),
(6, 4, 1, 1, 'Recepción', '2025-11-20 22:34:09');

--
-- Disparadores `inventories`
--
DELIMITER $$
CREATE TRIGGER `trg_inventory_update` AFTER UPDATE ON `inventories` FOR EACH ROW BEGIN
  DECLARE diff INT;
  SET diff = NEW.quantity - OLD.quantity;
  IF diff <> 0 THEN
    INSERT INTO stock_movements (product_id, branch_id,
      movement_type, quantity, note, created_by)
    VALUES (NEW.product_id, NEW.branch_id,
      IF(diff>0,'IN','OUT'), ABS(diff), CONCAT('Update inventory id=',NEW.id), NULL);
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_inventory_update_insert` AFTER INSERT ON `inventories` FOR EACH ROW BEGIN
  INSERT INTO stock_movements (product_id, branch_id, movement_type, quantity, note, created_by)
  VALUES (NEW.product_id, NEW.branch_id, 'IN', NEW.quantity, CONCAT('Initial stock/insert inventory id=',NEW.id), NULL);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `sku` varchar(80) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `cost` decimal(12,2) DEFAULT 0.00,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `min_stock` int(11) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `visible_public` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `sku`, `name`, `description`, `category_id`, `brand`, `cost`, `price`, `min_stock`, `is_active`, `visible_public`, `created_at`, `updated_at`) VALUES
(1, 'SKU-0001', 'Amortiguador Mazda 3', 'Amortiguador delantero derecho - genérico', 1, 'MarcaX', 250.00, 350.00, 2, 1, 1, '2025-11-19 14:44:06', '2025-11-20 20:49:21'),
(2, 'SKU-0002', 'Pastilla Freno Trasera', 'Pastilla freno trasera', 1, 'MarcaY', 40.00, 80.00, 5, 1, 1, '2025-11-19 14:44:06', NULL),
(4, 'SKU-0003', 'Luces delanteras', 'luces genéricas ', 23, 'Toyota', 180.00, 280.00, 2, 1, 0, '2025-11-20 21:53:39', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_reservations`
--

CREATE TABLE `product_reservations` (
  `id` bigint(20) NOT NULL,
  `product_id` int(11) NOT NULL,
  `from_branch_id` int(11) NOT NULL,
  `to_branch_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('PENDING','COMPLETED','CANCELLED') DEFAULT 'PENDING',
  `requested_by` int(11) DEFAULT NULL,
  `handled_by` int(11) DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `handled_at` timestamp NULL DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product_reservations`
--

INSERT INTO `product_reservations` (`id`, `product_id`, `from_branch_id`, `to_branch_id`, `quantity`, `status`, `requested_by`, `handled_by`, `requested_at`, `handled_at`, `note`) VALUES
(1, 4, 2, 1, 1, 'COMPLETED', 5, 7, '2025-11-20 21:57:36', '2025-11-20 22:34:09', NULL),
(2, 1, 2, 1, 1, 'PENDING', 5, NULL, '2025-11-20 23:00:53', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'owner', 'Dueño del sistema', '2025-11-19 14:44:06'),
(2, 'admin', 'Administrador', '2025-11-19 14:44:06'),
(3, 'cashier', 'Cajero / Vendedor', '2025-11-19 14:44:06'),
(4, 'viewer', 'Solo lectura', '2025-11-19 14:44:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) NOT NULL,
  `sale_number` varchar(50) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(12,2) DEFAULT 0.00,
  `payment_method` enum('CASH','CARD','MIX','ONLINE') DEFAULT 'CASH',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sales`
--

INSERT INTO `sales` (`id`, `sale_number`, `branch_id`, `customer_id`, `user_id`, `total_amount`, `total_cost`, `payment_method`, `created_at`, `notes`) VALUES
(1, 'V-1763677360', 1, NULL, 5, 350.00, 0.00, 'CASH', '2025-11-20 22:22:40', NULL),
(2, 'V-1763677382', 1, NULL, 5, 280.00, 0.00, 'CASH', '2025-11-20 22:23:02', NULL),
(3, 'V-1763679692', 1, NULL, 5, 160.00, 0.00, 'CASH', '2025-11-20 23:01:32', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) NOT NULL,
  `sale_id` bigint(20) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `unit_cost` decimal(12,2) DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `unit_price`, `unit_cost`, `subtotal`, `created_at`) VALUES
(1, 1, 1, 1, 350.00, 0.00, 350.00, '2025-11-20 22:22:40'),
(2, 2, 4, 1, 280.00, 0.00, 280.00, '2025-11-20 22:23:02'),
(3, 3, 2, 2, 80.00, 0.00, 160.00, '2025-11-20 23:01:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) NOT NULL,
  `product_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `movement_type` enum('IN','OUT','TRANSFER_IN','TRANSFER_OUT','ADJUST') NOT NULL,
  `quantity` int(11) NOT NULL,
  `reference_table` varchar(50) DEFAULT NULL,
  `reference_id` bigint(20) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `branch_id`, `movement_type`, `quantity`, `reference_table`, `reference_id`, `note`, `created_by`, `created_at`) VALUES
(1, 1, 1, 'IN', 0, NULL, NULL, 'Initial stock/insert inventory id=1', NULL, '2025-11-19 14:44:06'),
(2, 1, 2, 'IN', 2, NULL, NULL, 'Initial stock/insert inventory id=2', NULL, '2025-11-19 14:44:06'),
(3, 2, 1, 'IN', 10, NULL, NULL, 'Initial stock/insert inventory id=3', NULL, '2025-11-19 14:44:06'),
(4, 2, 2, 'IN', 0, NULL, NULL, 'Initial stock/insert inventory id=4', NULL, '2025-11-19 14:44:06'),
(5, 4, 2, 'IN', 5, NULL, NULL, 'Initial stock/insert inventory id=5', NULL, '2025-11-20 21:53:39'),
(6, 1, 1, 'OUT', 1, NULL, NULL, 'Update inventory id=1', NULL, '2025-11-20 22:22:40'),
(7, 1, 1, 'OUT', 1, 'sales', 1, 'Venta realizada', 5, '2025-11-20 22:22:40'),
(8, 4, 1, 'OUT', 1, 'sales', 2, 'Venta realizada', 5, '2025-11-20 22:23:02'),
(9, 4, 2, 'OUT', 1, NULL, NULL, 'Update inventory id=5', NULL, '2025-11-20 22:34:09'),
(10, 4, 2, 'TRANSFER_OUT', 1, 'product_reservations', 1, 'Reserva de stock', 7, '2025-11-20 22:34:09'),
(11, 4, 1, 'IN', 1, NULL, NULL, 'Initial stock/insert inventory id=6', NULL, '2025-11-20 22:34:09'),
(12, 4, 1, 'TRANSFER_IN', 1, 'product_reservations', 1, 'Reserva de stock', 7, '2025-11-20 22:34:09'),
(13, 2, 1, 'OUT', 2, NULL, NULL, 'Update inventory id=3', NULL, '2025-11-20 23:01:32'),
(14, 2, 1, 'OUT', 2, 'sales', 3, 'Venta realizada', 5, '2025-11-20 23:01:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT 3,
  `branch_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `full_name`, `email`, `role_id`, `branch_id`, `is_active`, `created_at`, `last_login`) VALUES
(1, 'owner', '$2y$10$examplehash', 'Dueño', 'owner@example.com', 1, NULL, 1, '2025-11-19 14:44:06', NULL),
(2, 'cajero1', '$2y$10$examplehash', 'Cajero Uno', 'cajero1@example.com', 3, 1, 1, '2025-11-19 14:44:06', NULL),
(5, 'admin', '$2y$10$NUy9yKyPed8A1qnLLG2yTuOlzeu7kCwX9IUJsfkTW7Oaxp9Q5lO1u', 'Administrador General', 'admin@example.com', 2, 1, 1, '2025-11-19 19:38:59', NULL),
(6, 'vendedor_centro', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vendedor Centro', 'centro@taller.com', 3, 2, 1, '2025-11-20 22:04:50', NULL),
(7, 'david1', '$2y$10$5wy9roDC3l4ofJWgr2o8aem9von.8qzCUb.KcWHzsZ4fvB3CMWUja', 'David Martinez', 'david@example.com', 3, 2, 1, '2025-11-20 22:33:37', NULL);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_product_stock`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_product_stock` (
`product_id` int(11)
,`sku` varchar(80)
,`name` varchar(255)
,`total_stock` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_product_stock`
--
DROP TABLE IF EXISTS `vw_product_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_product_stock`  AS SELECT `p`.`id` AS `product_id`, `p`.`sku` AS `sku`, `p`.`name` AS `name`, coalesce(sum(`i`.`quantity`),0) AS `total_stock` FROM (`products` `p` left join `inventories` `i` on(`i`.`product_id` = `p`.`id`)) GROUP BY `p`.`id` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `cash_sessions`
--
ALTER TABLE `cash_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indices de la tabla `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ux_product_branch` (`product_id`,`branch_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `category_id` (`category_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `ft_products_name_descr` (`name`,`description`);

--
-- Indices de la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `product_reservations`
--
ALTER TABLE `product_reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `from_branch_id` (`from_branch_id`),
  ADD KEY `to_branch_id` (`to_branch_id`),
  ADD KEY `requested_by` (`requested_by`),
  ADD KEY `handled_by` (`handled_by`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sale_number` (`sale_number`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`name`);

--
-- Indices de la tabla `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cash_sessions`
--
ALTER TABLE `cash_sessions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_reservations`
--
ALTER TABLE `product_reservations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `cash_sessions`
--
ALTER TABLE `cash_sessions`
  ADD CONSTRAINT `cash_sessions_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_sessions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventories_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product_reservations`
--
ALTER TABLE `product_reservations`
  ADD CONSTRAINT `product_reservations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reservations_ibfk_2` FOREIGN KEY (`from_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reservations_ibfk_3` FOREIGN KEY (`to_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reservations_ibfk_4` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_reservations_ibfk_5` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Filtros para la tabla `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
