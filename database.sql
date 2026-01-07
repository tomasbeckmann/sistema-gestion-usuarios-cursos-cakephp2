-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-01-2026 a las 22:01:19
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
-- Base de datos: `mantenedor_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `model` varchar(50) NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model`, `model_id`, `description`, `ip_address`, `created`) VALUES
(1, 1, 'delete', 'User', 5, 'Eliminó el usuario: TEST dasdasd (asdadasda@gha.cl)', '127.0.0.1', '2026-01-07 21:04:07'),
(2, 1, 'create', 'Course', 7, 'Creó el curso: a2eadd', '127.0.0.1', '2026-01-07 21:05:23'),
(3, 1, 'delete', 'Course', 7, 'Eliminó el curso: a2eadd', '127.0.0.1', '2026-01-07 21:05:25'),
(4, 1, 'deactivate', 'Course', 4, 'Curso desactivado: Inglés Intermedio', '127.0.0.1', '2026-01-07 21:09:50'),
(5, 1, 'create', 'User', 6, 'Creó el usuario: Maria Caceres (maria@mail.cl)', '127.0.0.1', '2026-01-07 21:54:17'),
(6, 1, 'create', 'User', 7, 'Creó el usuario: Ignacio Alarcon (ignacio@mail.cl)', '127.0.0.1', '2026-01-07 21:54:50'),
(7, 1, 'deactivate', 'User', 7, 'Usuario desactivado: Ignacio Alarcon', '127.0.0.1', '2026-01-07 21:54:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `cupo_maximo` int(11) DEFAULT 50,
  `active` tinyint(1) DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `courses`
--

INSERT INTO `courses` (`id`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `cupo_maximo`, `active`, `created`, `modified`) VALUES
(1, 'Matemáticas Avanzadas', 'Curso de cálculo diferencial e integral', '2026-02-01', '2026-06-30', 50, 1, '2026-01-06 12:04:23', '2026-01-06 16:45:57'),
(2, 'Programación en Python', 'Introducción a Python y desarrollo web', '2026-02-15', '2026-07-15', 50, 1, '2026-01-06 12:04:23', '2026-01-06 12:04:23'),
(3, 'Historia del Arte', 'Recorrido por la historia del arte universal', '2026-03-01', '2026-08-01', 50, 1, '2026-01-06 12:04:23', '2026-01-06 12:04:23'),
(4, 'Inglés Intermedio', 'Curso de inglés nivel B1-B2', '2026-02-10', '2026-06-20', 50, 0, '2026-01-06 12:04:23', '2026-01-07 21:09:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `courses_users`
--

CREATE TABLE `courses_users` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `courses_users`
--

INSERT INTO `courses_users` (`id`, `course_id`, `user_id`, `created`) VALUES
(2, 2, 2, '2026-01-06 12:04:34'),
(3, 2, 3, '2026-01-06 12:04:34'),
(4, 4, 3, '2026-01-06 12:04:34'),
(9, 1, 2, '2026-01-06 16:54:15'),
(10, 1, 3, '2026-01-06 16:54:15'),
(12, 1, 4, '2026-01-06 16:58:58'),
(13, 3, 2, '2026-01-06 20:09:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `active` tinyint(1) DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `nombre`, `apellido`, `role`, `active`, `created`, `modified`) VALUES
(1, 'admin@test.com', '$2y$10$Y3qE6LrEJcwzuwGSyL6Qau50KHUkBN5yvMiKlJEZ0KsO2NT.e1mwi', 'Admin', 'Sistema', 'admin', 1, '2026-01-05 17:36:37', '2026-01-05 17:36:37'),
(2, 'usuario1@test.com', '$2y$10$0uJzCmZYK3Ly5UH7kHMWcuKSJ350uUlo0KJ3caBM88w4qvC0dj9hi', 'Juan', 'Pérez', 'user', 1, '2026-01-05 17:36:37', '2026-01-05 17:36:37'),
(3, 'usuario2@test.com', '$2y$10$0uJzCmZYK3Ly5UH7kHMWcuKSJ350uUlo0KJ3caBM88w4qvC0dj9hi', 'María', 'González', 'user', 1, '2026-01-05 17:36:37', '2026-01-05 17:36:37'),
(4, 'pedro@mail.com', '$2a$10$3MfdxyuCuvuOylwy7Nr0hezNF0il2DLSXN3MyQLE4ZMKMUGyoFMVS', 'Pedro', 'Picapiedra', 'user', 1, '2026-01-06 02:25:38', '2026-01-06 02:25:38'),
(6, 'maria@mail.cl', '$2a$10$/W5CDefht1aYrRtQgnH/x.hcDIlfqE7WfQi7skblrHRXSQzB5U0Su', 'Maria', 'Caceres', 'user', 1, '2026-01-07 21:54:17', '2026-01-07 21:54:17'),
(7, 'ignacio@mail.cl', '$2a$10$fPoa8UVRIHEAOJ1HxkpI8eQNbvesuiqJwDQXgkvDfgskBJ4I5evPK', 'Ignacio', 'Alarcon', 'user', 0, '2026-01-07 21:54:50', '2026-01-07 21:54:54');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_model` (`model`,`model_id`),
  ADD KEY `idx_created` (`created`);

--
-- Indices de la tabla `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `courses_users`
--
ALTER TABLE `courses_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_course_user` (`course_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `courses_users`
--
ALTER TABLE `courses_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `courses_users`
--
ALTER TABLE `courses_users`
  ADD CONSTRAINT `courses_users_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `courses_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
