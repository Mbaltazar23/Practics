-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2023 a las 04:11:35
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `practi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `guia_id` int(11) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `especialidad_id` int(11) NOT NULL,
  `fono` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fono02` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `alumno`
--


INSERT INTO `alumno` (`id`, `persona_id`, `guia_id`, `profesor_id`, `curso_id`, `especialidad_id`, `fono`, `fono02`) VALUES
(1, 9, 1, 3, 2, 2, '+56954363463', '+569'),
(2, 10, 2, 4, 4, 1, '+56953545454', '+56934353534'),
(3, 11, 2, 1, 4, 1, '+56954353464', '+569'),
(4, 12, 2, 3, 1, 3, '+56934534534', '+56945346346'),
(5, 19, 3, 5, 6, 5, '+56991234567', '+569');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_plan`
--

CREATE TABLE `alumno_plan` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `detalleplan` text COLLATE utf8mb4_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `alumno_plan`

--

INSERT INTO `alumno_plan` (`id`, `alumno_id`, `plan_id`, `detalleplan`) VALUES
(1, 1, 1, 'Soy Parte De Este Plan Y Me Quiero Qued'),
(3, 3, 1, 'fdsgd'),
(4, 5, 2, '.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL,
  `texto` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `tarea_id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `bitacora`
--
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificacion`
--

CREATE TABLE `calificacion` (
  `id` int(11) NOT NULL,
  `nota` decimal(6,1) DEFAULT NULL,
  `documento_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colegio`
--

CREATE TABLE `colegio` (
  `id` int(11) NOT NULL,
  `rut` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `colegio`
--

INSERT INTO `colegio` (`id`, `rut`, `nombre`, `telefono`, `direccion`, `status`, `created_at`, `updated_at`) VALUES
(1, '15.535.345-5', 'San Marcos', '+56966546456', 'Se Ubica En La Direccion Del Corazon', 1, '2023-02-09 02:23:54', '2023-02-10 23:45:25'),
(2, '1.854.644-3', 'Santa Maria', '+56957745745', 'Gdgdgdfg', 1, '2023-02-10 23:46:44', '2023-02-11 22:23:31');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colegio_empresa`
--

CREATE TABLE `colegio_empresa` (
  `id` int(11) NOT NULL,
  `colegio_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `colegio_empresa`
--

INSERT INTO `colegio_empresa` (`id`, `colegio_id`, `empresa_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colegio_especialidad`
--

CREATE TABLE `colegio_especialidad` (
  `id` int(11) NOT NULL,
  `colegio_id` int(11) NOT NULL,
  `especialidad_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `colegio_especialidad`
--

INSERT INTO `colegio_especialidad` (`id`, `colegio_id`, `especialidad_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 5),
(6, 2, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `id` int(11) NOT NULL,
  `colegio_id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `curso`
--


INSERT INTO `curso` (`id`, `colegio_id`, `nombre`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '7° Grado', 1, '2023-02-09 02:29:55', '2023-02-09 02:29:55'),
(2, 1, '8° Grado', 1, '2023-02-09 02:29:55', '2023-02-09 02:29:55'),
(3, 1, '7° Grado', 1, '2023-02-09 02:29:55', '2023-02-09 02:29:55'),
(4, 1, '4° Grado', 1, '2023-02-09 02:29:55', '2023-02-09 02:29:55'),
(5, 1, '1ro Medio', 0, '2023-02-11 03:48:10', '2023-02-11 03:48:43'),
(6, 2, '4 Medio', 1, '2023-02-14 02:47:50', '2023-02-14 02:47:50'),
(7, 2, '3 Medio', 1, '2023-02-14 02:47:57', '2023-02-14 02:47:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `texto` text COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `alumno_plan_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `documento`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `rut` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `rubro` varchar(45) COLLATE utf8mb4_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `empresa`
--
INSERT INTO `empresa` (`id`, `rut`, `nombre`, `rubro`, `status`, `created_at`, `updated_at`) VALUES
(1, '14.354.646-4', 'Wallmart', 'Se encarga de suministrar a los colegios en e', 1, '2023-02-09 02:27:50', '2023-02-09 02:27:50'),
(2, '6.575.464-4', 'Chavata', 'Se encarga de suministrar el pan a los colegi', 1, '2023-02-09 02:27:50', '2023-02-09 02:27:50'),
(3, '77.123.456-7', 'Empresa Colun', 'Lechera y distribuidora', 1, '2023-02-14 02:41:52', '2023-02-14 02:41:52'),
(4, '77.123.456-7', 'Empresa Colun', 'Distribuidora', 1, '2023-02-14 02:49:24', '2023-02-14 02:49:24'),
(5, '77.123.456-7', 'Empresa Colun', 'Distribuidora', 1, '2023-02-14 02:49:25', '2023-02-14 02:49:25'),
(6, '77.123.456-7', 'Empresa Colun', 'Distribuidora', 1, '2023-02-14 02:49:26', '2023-02-14 02:49:26'),
(7, '77.123.456-7', 'Copefrut', 'Agricola', 1, '2023-02-16 10:44:57', '2023-02-16 10:44:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

CREATE TABLE `especialidad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `especialidad`
--


INSERT INTO `especialidad` (`id`, `nombre`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Matematicas', 1, '2023-02-09 02:26:45', '2023-02-09 02:26:45'),
(2, 'Lenguaje', 1, '2023-02-09 02:26:45', '2023-02-09 02:26:45'),
(3, 'Filosofia', 1, '2023-02-09 02:26:45', '2023-02-12 03:05:14'),
(4, 'Adoctrinar', 0, '2023-02-09 02:26:45', '2023-02-11 04:24:31'),
(5, 'Administración', 1, '2023-02-14 02:47:31', '2023-02-14 02:47:31'),
(6, 'Contabilidad', 1, '2023-02-14 02:47:39', '2023-02-14 02:47:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guia`
--

CREATE TABLE `guia` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `cargo` text COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `fono` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `guia`
--
INSERT INTO `guia` (`id`, `persona_id`, `empresa_id`, `cargo`, `fono`) VALUES
(1, 4, 1, 'Se encarga de administrar las cartas', '+56978645369'),
(2, 7, 2, 'Se encarga de sumistrar todo', '+56953345346'),
(3, 18, 7, 'Jefe de rrhh', '+56991234567');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagen`
--

CREATE TABLE `imagen` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `alumno_plan_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `imagen`
--
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota`
--

CREATE TABLE `nota` (
  `id` int(11) NOT NULL,
  `nota01` decimal(6,1) NOT NULL,
  `nota02` decimal(6,1) DEFAULT NULL,
  `nota03` decimal(6,1) DEFAULT NULL,
  `promedio` decimal(6,1) NOT NULL,
  `bitacora_id` int(11) NOT NULL,
  `comentario` text COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `nota`
--
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id` int(11) NOT NULL,
  `rut` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `avatar` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `session` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `rol` enum('Administrador','Alumno','Profesor','Guía','Administrador de Colegio') COLLATE utf8mb4_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id`, `rut`, `nombre`, `apellido`, `correo`, `password`, `avatar`, `direccion`, `session`, `rol`, `status`, `created_at`, `updated_at`) VALUES
(1, '14.567.246-6', 'Admin', 'Comunica', 'Admin@gmail.com', 'e66055e8e308770492a44bf16e875127', 'pro_12d0348ad4338084dda93b823473db4f.jpg', 'gddg', 'Activo', 'Administrador de Colegio', 2, '2023-02-08 23:36:47', '2023-02-20 01:45:20'),
(2, '5.346.364-6', 'Juan', 'Perez', 'Juan45@gmail.com', 'e66055e8e308770492a44bf16e875127', 'prf_b62cec59a1b615078cdbd65abf9f877f.jpg', '', 'Inactivo', 'Profesor', 1, '2023-02-08 23:36:47', '2023-02-08 23:36:47'),
(3, '6.454.647-5', 'Romeo', 'Alvarez', 'Romerin@gmail.com', 'd241f775d9b3dc5cc49c257ce71b5d05', '', '', 'Inactivo', 'Profesor', 1, '2023-02-08 23:36:47', '2023-02-10 04:28:35'),
(4, '5.463.634-9', 'Karlos', 'Alvarez', 'Karlo45@gmail.com', 'e66055e8e308770492a44bf16e875127', '', '', 'Activo', 'Guía', 1, '2023-02-08 23:36:47', '2023-02-14 03:04:45'),
(5, '3.534.543-6', 'Juani', 'Gui', 'Juan@gmail.com', 'c4263f9f0dd8976172f2d06a751d9039', '', '', 'Inactivo', 'Profesor', 1, '2023-02-08 23:36:47', '2023-02-08 23:36:47'),
(6, '4.354.543-6', 'Katheyeyy', 'Nuñez', 'Kahty@gmail.com', '7e0f19af348feff0422187e94bdf1ab1', '', '', 'Inactivo', 'Profesor', 1, '2023-02-08 23:36:47', '2023-02-08 23:36:47'),
(7, '4.636.436-4', 'Yan', 'Jua', 'Juanit@gmail.com', 'e66055e8e308770492a44bf16e875127', '', '', 'Inactivo', 'Guía', 1, '2023-02-08 23:36:47', '2023-02-11 16:36:11'),
(8, '5.436.346-3', 'Guilber', 'ñaves', 'Guilber@gmail.com', 'c4263f9f0dd8976172f2d06a751d9039', '', '', 'Inactivo', 'Alumno', 1, '2023-02-08 23:36:47', '2023-02-08 23:36:47'),
(9, '4.636.346-4', 'Yiro', 'Alvaree', 'Yiro@gmail.com', 'e66055e8e308770492a44bf16e875127', '', '', 'Activo', 'Alumno', 2, '2023-02-08 23:36:47', '2023-02-17 22:30:25'),
(10, '5.434.634-4', 'Steven', 'ñaves', 'Steven45@gmail.com', 'c4263f9f0dd8976172f2d06a751d9039', '', '', 'Inactivo', 'Alumno', 1, '2023-02-08 23:36:47', '2023-02-10 03:23:07'),
(11, '3.523.545-4', 'Haley', 'Juares', 'Haley@gmail.com', 'c4263f9f0dd8976172f2d06a751d9039', '', '', 'Inactivo', 'Alumno', 2, '2023-02-08 23:36:47', '2023-02-10 03:35:22'),
(12, '4.324.352-3', 'Hayder', 'Karlos', 'Hayder@gmail.com', '154f90a01bbd12328fe5f46002000ded', '', '', 'Activo', 'Alumno', 1, '2023-02-08 23:36:47', '2023-02-27 03:06:17'),
(13, '5.345.436-4', 'Juani', 'Alvare', 'Juan45@gmail.com', 'd241f775d9b3dc5cc49c257ce71b5d05', '', '', 'Inactivo', 'Alumno', 1, '2023-02-08 23:36:47', '2023-02-08 23:36:47'),
(14, '19.954.535-5', 'Luis', 'Falias', 'Luis@practis.com', 'e66055e8e308770492a44bf16e875127', '', '', 'Activo', 'Administrador', 1, '2023-02-09 03:09:17', '2023-02-12 03:29:53'),
(15, '6.436.353-4', 'Pepe', 'G', 'Pepe56@gmail.com', '4d66a794f78832ec32db80993af0bb4a', '', 'Gfdf', 'Activo', 'Administrador de Colegio', 2, '2023-02-11 00:03:46', '2023-02-20 01:45:26'),
(16, '14.123.456-7', 'Luis', 'Farah', 'Luis@practis.cl', 'b64d5ff0f9539937c8d189d5ff59f577', '', 'Av Camilo henríquez 100', 'Activo', 'Administrador de Colegio', 2, '2023-02-14 02:37:29', '2023-02-20 01:45:31'),
(17, '12.123.456-7', 'Jose', 'Araneda', 'Jose@practis.cl', '70483b6e100c9cebbffcdc62dea07eda', '', '', '', 'Profesor', 1, '2023-02-16 10:45:35', '2023-02-16 10:45:35'),
(18, '13.456.730-1', 'Jaime', 'Verdugo', 'Jaime@copefrut.cl', '967fc0ee903cd7fd9388cc6e9c3cc0e0', '', '', 'Activo', 'Guía', 1, '2023-02-16 10:46:52', '2023-02-17 22:28:11'),
(19, '22.123.456-7', 'Patricia', 'González', 'Patricia@practis.cl', '54a7b18f26374fc200ddedde0844f8ec', '', '', '', 'Alumno', 2, '2023-02-17 22:12:11', '2023-02-17 22:17:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona_colegio`
--

CREATE TABLE `persona_colegio` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `colegio_id` int(11) NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `persona_colegio`
--

INSERT INTO `persona_colegio` (`id`, `persona_id`, `colegio_id`, `telefono`) VALUES
(1, 1, 1, '+56964534523'),
(6, 10, 1, '+56953545454'),
(7, 9, 1, '+56954363463'),
(8, 12, 1, '+56934534534'),
(9, 11, 1, '+56954353464'),
(10, 4, 1, '+56978645369'),
(11, 7, 1, '+56953345346'),
(12, 15, 2, '+56987377347'),
(13, 2, 1, '+56945446656'),
(14, 3, 1, '+56954353453'),
(15, 5, 1, '+56965474576'),
(16, 6, 1, '+56935345436'),
(17, 16, 2, '+56991234567'),
(18, 17, 2, '+56991234567'),
(19, 18, 2, '+56991234567'),
(20, 19, 2, '+56991234567');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan`
--

CREATE TABLE `plan` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `colegio_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `plan`
--

INSERT INTO `plan` (`id`, `nombre`, `descripcion`, `colegio_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Plan De Contabilidad', 'Sera para los ajustes de contabilidad', 1, 1, '2023-02-09 05:32:27', '2023-02-09 05:32:27'),
(2, 'Plan De Adm', 'Tareas principales de administración, mención Recursos humanos', 2, 1, '2023-02-14 02:42:52', '2023-02-14 02:42:52');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

CREATE TABLE `profesor` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `fono` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `profesor`
--

INSERT INTO `profesor` (`id`, `persona_id`, `fono`) VALUES
(1, 2, '+56945446656'),
(2, 3, '+56954353453'),
(3, 5, '+56965474576'),
(4, 6, '+56935345436'),
(5, 17, '+56991234567');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea`
--

CREATE TABLE `tarea` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `colegio_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tarea`
--
INSERT INTO `tarea` (`id`, `nombre`, `status`, `colegio_id`, `created_at`, `updated_at`) VALUES
(1, 'Hacer Las Papas', 1, 1, '2023-02-09 05:34:52', '2023-02-09 05:34:52'),
(2, 'Comer', 1, 1, '2023-02-09 05:34:52', '2023-02-09 05:34:52'),
(3, 'Ecuaciones Diferenciales', 1, 1, '2023-02-09 05:34:52', '2023-02-09 05:34:52'),
(4, 'Urgar Hoteles', 1, 1, '2023-02-09 05:34:52', '2023-02-09 05:34:52'),
(5, 'Sumar Letras', 1, 1, '2023-02-09 05:34:52', '2023-02-09 05:34:52'),
(6, 'Gestiona Las Facturas De Compra', 1, 2, '2023-02-14 02:43:39', '2023-02-14 02:43:39'),
(7, 'Gestiona El Pago De Las Facturas A Proveedores', 1, 2, '2023-02-14 02:44:01', '2023-02-14 02:44:01'),
(8, 'Cobra Facturas De Clientes', 1, 2, '2023-02-14 02:44:12', '2023-02-14 02:44:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea_plan`
--

CREATE TABLE `tarea_plan` (
  `id` int(11) NOT NULL,
  `tarea_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tarea_plan`
--

INSERT INTO `tarea_plan` (`id`, `tarea_id`, `plan_id`, `status`) VALUES
(7, 2, 1, 2),
(8, 4, 1, 1),
(9, 3, 1, 1),
(10, 6, 2, 2),
(11, 7, 2, 2),
(12, 8, 2, 1),
(13, 5, 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `persona_id` (`persona_id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `especialidad_id` (`especialidad_id`),
  ADD KEY `guia_id` (`guia_id`),
  ADD KEY `profesor_id` (`profesor_id`);

--
-- Indices de la tabla `alumno_plan`
--
ALTER TABLE `alumno_plan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subida_alumno_idx` (`alumno_id`),
  ADD KEY `fk_subida_tarea1_idx` (`tarea_id`);

--
-- Indices de la tabla `calificacion`
--
ALTER TABLE `calificacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_calificacion_documento1_idx` (`documento_id`);

--
-- Indices de la tabla `colegio`
--
ALTER TABLE `colegio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `colegio_empresa`
--
ALTER TABLE `colegio_empresa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `colegio_id` (`colegio_id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Indices de la tabla `colegio_especialidad`
--
ALTER TABLE `colegio_especialidad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `colegio_id` (`colegio_id`),
  ADD KEY `especialidad_id` (`especialidad_id`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `colegio_id` (`colegio_id`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_documento_alumno_plan1_idx` (`alumno_plan_id`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `guia`
--
ALTER TABLE `guia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `persona_id` (`persona_id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Indices de la tabla `imagen`
--
ALTER TABLE `imagen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_imagen_alumno_plan1_idx` (`alumno_plan_id`);

--
-- Indices de la tabla `nota`
--
ALTER TABLE `nota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nota_bitacora1_idx` (`bitacora_id`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `persona_colegio`
--
ALTER TABLE `persona_colegio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `persona_id` (`persona_id`),
  ADD KEY `colegio_id` (`colegio_id`);

--
-- Indices de la tabla `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_plan_colegio1_idx` (`colegio_id`);

--
-- Indices de la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `persona_id` (`persona_id`);

--
-- Indices de la tabla `tarea`
--
ALTER TABLE `tarea`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tarea_colegio1_idx` (`colegio_id`);

--
-- Indices de la tabla `tarea_plan`
--
ALTER TABLE `tarea_plan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tarea_id` (`tarea_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `alumno_plan`
--
ALTER TABLE `alumno_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `calificacion`
--
ALTER TABLE `calificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `colegio`
--
ALTER TABLE `colegio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `colegio_empresa`
--
ALTER TABLE `colegio_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `colegio_especialidad`
--
ALTER TABLE `colegio_especialidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `guia`
--
ALTER TABLE `guia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `imagen`
--
ALTER TABLE `imagen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `nota`
--
ALTER TABLE `nota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `persona_colegio`
--
ALTER TABLE `persona_colegio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `plan`
--
ALTER TABLE `plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `profesor`
--
ALTER TABLE `profesor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tarea`
--
ALTER TABLE `tarea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tarea_plan`
--
ALTER TABLE `tarea_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `alumno_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alumno_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alumno_ibfk_3` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidad` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alumno_ibfk_4` FOREIGN KEY (`guia_id`) REFERENCES `guia` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alumno_ibfk_5` FOREIGN KEY (`profesor_id`) REFERENCES `profesor` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `alumno_plan`
--
ALTER TABLE `alumno_plan`
  ADD CONSTRAINT `alumno_plan_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumno` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alumno_plan_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `fk_subida_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumno` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_subida_tarea1` FOREIGN KEY (`tarea_id`) REFERENCES `tarea` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `calificacion`
--
ALTER TABLE `calificacion`
  ADD CONSTRAINT `fk_calificacion_documento1` FOREIGN KEY (`documento_id`) REFERENCES `documento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `colegio_empresa`
--
ALTER TABLE `colegio_empresa`
  ADD CONSTRAINT `colegio_empresa_ibfk_1` FOREIGN KEY (`colegio_id`) REFERENCES `colegio` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `colegio_empresa_ibfk_2` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `colegio_especialidad`
--
ALTER TABLE `colegio_especialidad`
  ADD CONSTRAINT `colegio_especialidad_ibfk_1` FOREIGN KEY (`colegio_id`) REFERENCES `colegio` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `colegio_especialidad_ibfk_2` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidad` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`colegio_id`) REFERENCES `colegio` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `fk_documento_alumno_plan1` FOREIGN KEY (`alumno_plan_id`) REFERENCES `alumno_plan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guia`
--
ALTER TABLE `guia`
  ADD CONSTRAINT `guia_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `guia_ibfk_2` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `imagen`
--
ALTER TABLE `imagen`
  ADD CONSTRAINT `fk_imagen_alumno_plan1` FOREIGN KEY (`alumno_plan_id`) REFERENCES `alumno_plan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `nota`
--
ALTER TABLE `nota`
  ADD CONSTRAINT `fk_nota_bitacora1` FOREIGN KEY (`bitacora_id`) REFERENCES `bitacora` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `persona_colegio`
--
ALTER TABLE `persona_colegio`
  ADD CONSTRAINT `persona_colegio_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `persona_colegio_ibfk_2` FOREIGN KEY (`colegio_id`) REFERENCES `colegio` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `plan`
--
ALTER TABLE `plan`
  ADD CONSTRAINT `fk_plan_colegio1` FOREIGN KEY (`colegio_id`) REFERENCES `colegio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD CONSTRAINT `profesor_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tarea`
--
ALTER TABLE `tarea`
  ADD CONSTRAINT `fk_tarea_colegio1` FOREIGN KEY (`colegio_id`) REFERENCES `colegio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tarea_plan`
--
ALTER TABLE `tarea_plan`
  ADD CONSTRAINT `tarea_plan_ibfk_1` FOREIGN KEY (`tarea_id`) REFERENCES `tarea` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tarea_plan_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
