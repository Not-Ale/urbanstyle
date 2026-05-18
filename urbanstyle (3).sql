-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-05-2026 a las 07:16:06
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
-- Base de datos: `urbanstyle`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `activa`, `created_at`) VALUES
(1, 'Bolsos', 'bolsos', NULL, 1, '2026-05-18 04:03:05'),
(2, 'Carteras', 'carteras', NULL, 1, '2026-05-18 04:03:05'),
(3, 'Relojes', 'relojes', NULL, 1, '2026-05-18 04:03:05'),
(4, 'Gorras', 'gorras', NULL, 1, '2026-05-18 04:03:05'),
(5, 'Lentes', 'lentes', NULL, 1, '2026-05-18 04:03:05'),
(6, 'Collares', 'collares', NULL, 1, '2026-05-18 04:03:05'),
(7, 'Cinturones', 'cinturones', NULL, 1, '2026-05-18 04:03:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `correo` varchar(160) NOT NULL,
  `mensaje` text NOT NULL,
  `leido` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contacto`
--

INSERT INTO `contacto` (`id`, `nombre`, `correo`, `mensaje`, `leido`, `created_at`) VALUES
(1, 'Patricia Vargas', 'patricia@gmail.com', '¿Tienen envíos a San Pedro Sula?', 0, '2026-05-18 04:03:05'),
(2, 'René Alvarado', 'rene.a@outlook.com', 'Quiero información sobre el reloj Rose Gold.', 0, '2026-05-18 04:03:05'),
(3, 'Claudia Méndez', 'claudia@gmail.com', 'Mi pedido fue cancelado sin aviso, necesito ayuda.', 0, '2026-05-18 04:03:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id` int(10) UNSIGNED NOT NULL,
  `pedido_id` int(10) UNSIGNED NOT NULL,
  `producto_id` int(10) UNSIGNED NOT NULL,
  `cantidad` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `precio_unit` decimal(10,2) NOT NULL COMMENT 'Precio al momento de la compra'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unit`) VALUES
(1, 1001, 1, 1, 89.00),
(2, 1001, 12, 1, 45.00),
(3, 1002, 1, 1, 89.00),
(4, 1003, 6, 1, 139.00),
(5, 1003, 13, 1, 32.00),
(6, 1003, 12, 1, 45.00),
(7, 1004, 2, 1, 55.00),
(8, 1005, 3, 1, 38.00),
(9, 1006, 6, 1, 139.00),
(10, 1006, 7, 1, 95.00),
(11, 1006, 4, 1, 42.00),
(12, 1007, 10, 1, 60.00),
(13, 1007, 9, 1, 24.00),
(14, 1008, 11, 1, 75.00),
(15, 1008, 1, 1, 89.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(10) UNSIGNED NOT NULL,
  `pedido_id` int(10) UNSIGNED NOT NULL,
  `metodo` enum('visa','paypal','transferencia') NOT NULL DEFAULT 'visa',
  `monto` decimal(10,2) NOT NULL,
  `referencia` varchar(200) DEFAULT NULL COMMENT 'ID transacción, número de transferencia, etc.',
  `nombre_titular` varchar(160) DEFAULT NULL,
  `estado_pago` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `pedido_id`, `metodo`, `monto`, `referencia`, `nombre_titular`, `estado_pago`, `created_at`) VALUES
(1, 1001, 'visa', 145.00, '4111xxxx1111', 'María López', 'aprobado', '2026-05-18 04:03:05'),
(2, 1002, 'transferencia', 89.00, 'TRF-20250517-002', 'Carlos Mejía', 'pendiente', '2026-05-18 04:03:05'),
(3, 1003, 'paypal', 220.50, 'PAYID-ABC123', 'Ana Torres', 'aprobado', '2026-05-18 04:03:05'),
(4, 1004, 'visa', 55.00, '4111xxxx2222', 'Juan Rodas', 'rechazado', '2026-05-18 04:03:05'),
(5, 1005, 'transferencia', 38.00, 'TRF-20250516-005', 'Sofía Cruz', 'aprobado', '2026-05-18 04:03:05'),
(6, 1006, 'paypal', 310.00, 'PAYID-DEF456', 'Luis Paz', 'pendiente', '2026-05-18 04:03:05'),
(7, 1007, 'visa', 72.00, '4111xxxx3333', 'Valeria Ramos', 'aprobado', '2026-05-18 04:03:05'),
(8, 1008, 'transferencia', 190.00, 'TRF-20250514-008', 'Diego Flores', 'aprobado', '2026-05-18 04:03:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'NULL = compra como invitado',
  `nombre` varchar(120) NOT NULL DEFAULT '',
  `correo` varchar(160) NOT NULL DEFAULT '',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `envio` decimal(10,2) NOT NULL DEFAULT 5.00,
  `estado` enum('pendiente','completada','cancelada','reembolsada') NOT NULL DEFAULT 'pendiente',
  `notas` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `nombre`, `correo`, `total`, `envio`, `estado`, `notas`, `created_at`, `updated_at`) VALUES
(1001, NULL, '', '', 145.00, 5.00, 'completada', NULL, '2025-05-17 16:32:00', '2026-05-18 04:03:05'),
(1002, NULL, '', '', 89.00, 5.00, 'pendiente', NULL, '2025-05-17 15:15:00', '2026-05-18 04:03:05'),
(1003, NULL, '', '', 220.50, 5.00, 'completada', NULL, '2025-05-17 00:44:00', '2026-05-18 04:03:05'),
(1004, NULL, '', '', 55.00, 5.00, 'cancelada', NULL, '2025-05-16 20:20:00', '2026-05-18 04:03:05'),
(1005, NULL, '', '', 38.00, 5.00, 'completada', NULL, '2025-05-16 17:05:00', '2026-05-18 04:03:05'),
(1006, NULL, '', '', 310.00, 5.00, 'pendiente', NULL, '2025-05-16 02:18:00', '2026-05-18 04:03:05'),
(1007, NULL, '', '', 72.00, 5.00, 'completada', NULL, '2025-05-15 22:30:00', '2026-05-18 04:03:05'),
(1008, NULL, '', '', 190.00, 5.00, 'completada', NULL, '2025-05-14 15:50:00', '2026-05-18 04:03:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(10) UNSIGNED NOT NULL,
  `categoria_id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(160) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `precio_antes` decimal(10,2) DEFAULT NULL COMMENT 'Precio tachado (oferta)',
  `imagen_url` varchar(500) DEFAULT NULL,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `badge` enum('','nuevo','oferta') NOT NULL DEFAULT '',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `descripcion`, `precio`, `precio_antes`, `imagen_url`, `stock`, `badge`, `activo`, `created_at`) VALUES
(1, 1, 'Bolso Tote Premium', 'Cuero genuino, interior forrado, asa doble y correa ajustable.', 89.00, 120.00, 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80', 25, 'nuevo', 1, '2026-05-18 04:03:05'),
(2, 1, 'Bolso Crossbody Urban', 'Diseño compacto con múltiples compartimentos y cierre magnético.', 55.00, NULL, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80', 18, '', 1, '2026-05-18 04:03:05'),
(3, 1, 'Mini Bag Gold Edition', 'Bolso pequeño de noche con cadena dorada y cierre zip.', 38.00, 60.00, 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&q=80', 12, 'oferta', 1, '2026-05-18 04:03:05'),
(4, 2, 'Cartera Slim Classic', 'Cuero legítimo, 6 compartimentos para tarjetas, RFID blocking.', 42.00, NULL, 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=600&q=80', 30, 'nuevo', 1, '2026-05-18 04:03:05'),
(5, 2, 'Cartera Bifold Urban', 'Diseño minimalista, cuero italiano, 8 ranuras.', 35.00, 55.00, 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80', 20, 'oferta', 1, '2026-05-18 04:03:05'),
(6, 3, 'Reloj Rose Gold Luxe', 'Caja de acero, correa de cuero marrón, cristal zafiro.', 139.00, NULL, 'https://images.unsplash.com/photo-1509048191080-d2984bad6ae5?w=600&q=80', 10, 'nuevo', 1, '2026-05-18 04:03:05'),
(7, 3, 'Reloj Chrono Sport', 'Movimiento japonés, resistente al agua 50m, correa silicona.', 95.00, NULL, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80', 8, '', 1, '2026-05-18 04:03:05'),
(8, 4, 'Gorra Snapback Urban', 'Visera plana, bordado lateral, talla única ajustable.', 28.00, 40.00, 'https://images.unsplash.com/photo-1521369909029-2afed882baaa?w=600&q=80', 50, 'oferta', 1, '2026-05-18 04:03:05'),
(9, 4, 'Gorra Dad Hat Classic', 'Algodón 100%, fit relajado, logo bordado frontal.', 24.00, NULL, 'https://images.unsplash.com/photo-1534215754734-18e55d13e346?w=600&q=80', 40, '', 1, '2026-05-18 04:03:05'),
(10, 5, 'Lentes Retro Round', 'Montura acetato, lente UV400, estilo años 70.', 60.00, NULL, 'https://images.unsplash.com/photo-1577803645773-f96470509666?w=600&q=80', 22, 'nuevo', 1, '2026-05-18 04:03:05'),
(11, 5, 'Lentes Aviator Gold', 'Montura dorada, lente degradado verde, protección UV400.', 75.00, 95.00, 'https://images.unsplash.com/photo-1473496169904-658ba7574b0d?w=600&q=80', 15, 'oferta', 1, '2026-05-18 04:03:05'),
(12, 6, 'Collar Premium', 'Acero inoxidable dorado, largo 50 cm, dije artesanal.', 45.00, NULL, 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&q=80', 35, 'nuevo', 1, '2026-05-18 04:03:05'),
(13, 6, 'Aretes Luxury Drop', 'Plata 925, diseño gota, acabado brillante.', 32.00, 50.00, 'https://images.unsplash.com/photo-1630019852942-f89202989a59?w=600&q=80', 28, 'oferta', 1, '2026-05-18 04:03:05'),
(14, 7, 'Cinturón Cuero Clásico', 'Cuero genuino, hebilla plateada, 3 cm ancho, tallas S–XL.', 32.00, NULL, 'https://images.unsplash.com/photo-1553531384-397c80973a0b?w=600&q=80', 45, 'nuevo', 1, '2026-05-18 04:03:05'),
(15, 7, 'Cinturón Reversible Urban', 'Dos colores, hebilla giratoria de acero, piel italiana.', 40.00, NULL, 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=600&q=80', 20, '', 1, '2026-05-18 04:03:05'),
(16, 7, 'Cinturón Elástico Sport', 'Elástico trenzado, hebilla automática de zinc, casual.', 22.00, 35.00, 'https://images.unsplash.com/photo-1603400521630-9f2de124b33b?w=600&q=80', 60, 'oferta', 1, '2026-05-18 04:03:05'),
(17, 7, 'Cinturón Gold Buckle', 'Piel vegana, hebilla dorada de diseño, ancho 4 cm, unisex.', 38.00, NULL, 'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=600&q=80', 25, '', 1, '2026-05-18 04:03:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `correo` varchar(160) NOT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `telefono` varchar(25) DEFAULT NULL,
  `tipo_cuenta` enum('cliente','vendedor','admin') NOT NULL DEFAULT 'cliente',
  `google_id` varchar(120) DEFAULT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `telefono`, `tipo_cuenta`, `google_id`, `avatar`, `fecha_registro`) VALUES
(1, 'Admin Urban Style', 'admin@urbanstyle.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', NULL, NULL, '2026-05-18 04:03:05'),
(2, 'Geovany', 'geovanytest@gmail.com', '$2y$10$pGoVogJGVXMSqraqTFznzOOkkoI7iljhXW5pmk9xVnOgjwghg/Rmu', NULL, 'vendedor', NULL, NULL, '2026-05-18 04:04:49'),
(3, 'Admin Urban', 'admin.urban@gmail.com', NULL, NULL, 'cliente', 'demo_google_002', 'https://ui-avatars.com/api/?name=Admin+Urban&background=EA4335&color=fff&size=200', '2026-05-18 04:05:23');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_ordenes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_ordenes` (
`id` int(10) unsigned
,`cliente` varchar(120)
,`correo` varchar(160)
,`total` decimal(10,2)
,`estado` enum('pendiente','completada','cancelada','reembolsada')
,`metodo_pago` enum('visa','paypal','transferencia')
,`referencia` varchar(200)
,`fecha` timestamp
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_top_productos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_top_productos` (
`id` int(10) unsigned
,`nombre` varchar(160)
,`categoria` varchar(80)
,`total_vendidos` decimal(25,0)
,`ingresos_totales` decimal(35,2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_ventas_diarias`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_ventas_diarias` (
`dia` date
,`num_pedidos` bigint(21)
,`ingresos` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `v_ordenes`
--
DROP TABLE IF EXISTS `v_ordenes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_ordenes`  AS SELECT `p`.`id` AS `id`, coalesce(`u`.`nombre`,`p`.`nombre`,'Invitado') AS `cliente`, `p`.`correo` AS `correo`, `p`.`total` AS `total`, `p`.`estado` AS `estado`, `pg`.`metodo` AS `metodo_pago`, `pg`.`referencia` AS `referencia`, `p`.`created_at` AS `fecha` FROM ((`pedidos` `p` left join `usuarios` `u` on(`u`.`id` = `p`.`usuario_id`)) left join `pagos` `pg` on(`pg`.`pedido_id` = `p`.`id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_top_productos`
--
DROP TABLE IF EXISTS `v_top_productos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_top_productos`  AS SELECT `pr`.`id` AS `id`, `pr`.`nombre` AS `nombre`, `c`.`nombre` AS `categoria`, sum(`dp`.`cantidad`) AS `total_vendidos`, sum(`dp`.`cantidad` * `dp`.`precio_unit`) AS `ingresos_totales` FROM (((`detalle_pedidos` `dp` join `productos` `pr` on(`pr`.`id` = `dp`.`producto_id`)) join `categorias` `c` on(`c`.`id` = `pr`.`categoria_id`)) join `pedidos` `pe` on(`pe`.`id` = `dp`.`pedido_id`)) WHERE `pe`.`estado` <> 'cancelada' GROUP BY `pr`.`id`, `pr`.`nombre`, `c`.`nombre` ORDER BY sum(`dp`.`cantidad`) DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_ventas_diarias`
--
DROP TABLE IF EXISTS `v_ventas_diarias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_ventas_diarias`  AS SELECT cast(`pedidos`.`created_at` as date) AS `dia`, count(0) AS `num_pedidos`, sum(`pedidos`.`total`) AS `ingresos` FROM `pedidos` WHERE `pedidos`.`estado` <> 'cancelada' AND `pedidos`.`created_at` >= curdate() - interval 30 day GROUP BY cast(`pedidos`.`created_at` as date) ORDER BY cast(`pedidos`.`created_at` as date) ASC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_det_ped` (`pedido_id`),
  ADD KEY `fk_det_prod` (`producto_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pago_ped` (`pedido_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ped_usr` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_prod_cat` (`categoria_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `google_id` (`google_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1009;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD CONSTRAINT `fk_det_ped` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_det_prod` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pago_ped` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_ped_usr` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_prod_cat` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
