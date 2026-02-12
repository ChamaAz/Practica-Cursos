-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-02-2026 a las 21:54:40
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cursoscp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `usuario` varchar(20) NOT NULL,
  `dni` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`usuario`, `dni`) VALUES
('admin', 'B1234567S'),
('profesor', 'A1111111C'),
('profesor', 'X7654321Y'),
('profesor', 'x5564343T');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `codigo` decimal(6,0) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `abierto` tinyint(1) NOT NULL,
  `numeroplazas` int(2) NOT NULL,
  `plazoinscripcion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`codigo`, `nombre`, `abierto`, `numeroplazas`, `plazoinscripcion`) VALUES
(2, 'Programación en Java desde cero', 1, 10, '2025-03-15'),
(3, 'Ciberseguridad básica para docentes', 0, 6, '2025-02-28'),
(4, 'Gamificación y aprendizaje basado en juegos', 1, 8, '2025-04-01'),
(5, 'Diseño de actividades con Canva educativo', 1, 12, '2025-03-22'),
(6, 'Evaluación por competencias y rúbricas digitales', 0, 7, '2025-02-20'),
(7, 'Uso avanzado de hojas de cálculo en educación', 1, 9, '2025-03-30'),
(8, 'Introducción a SQL para análisis de datos', 1, 6, '2025-04-05'),
(12, 'Despliegue web', 0, 4, '2026-01-26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitantes`
--

CREATE TABLE `solicitantes` (
  `dni` varchar(9) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `telefono` varchar(12) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `codigocentro` varchar(8) NOT NULL,
  `coordinadortic` tinyint(1) NOT NULL,
  `grupotic` tinyint(1) NOT NULL,
  `nombregrupo` varchar(25) NOT NULL,
  `pbilin` tinyint(1) NOT NULL,
  `cargo` tinyint(1) NOT NULL,
  `nombrecargo` varchar(50) NOT NULL,
  `situacion` set('activo','inactivo') NOT NULL,
  `antiguedad` int(2) NOT NULL,
  `especialidad` varchar(50) NOT NULL,
  `puntos` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitantes`
--

INSERT INTO `solicitantes` (`dni`, `apellidos`, `nombre`, `telefono`, `correo`, `codigocentro`, `coordinadortic`, `grupotic`, `nombregrupo`, `pbilin`, `cargo`, `nombrecargo`, `situacion`, `antiguedad`, `especialidad`, `puntos`) VALUES
('A1324234S', 'Gutierrez', 'Eleonor', '693214578', 'laura@scarlatti.com', '12', 0, 1, 'Talis Group', 0, 1, 'jefe de estudios', 'activo', 14, 'profesora historia', 0),
('C1234567L', 'Candenas', 'David', '627952917', 'juan@scarlatti.com', '55', 0, 1, 'Samar', 0, 1, 'jefe de departamento', 'activo', 16, 'coordinador', 0),
('D142536FF', 'AJJAR', 'SALMA', '624836087', 'taghyolt@scarlatti.es', '28300', 1, 0, 'ASIR', 1, 0, 'aama', 'activo', 0, 'Informatica', 2),
('J1234567X', 'Manzanares', 'Julia', '663987124', 'carmen@scarlatti.com', '36', 0, 0, '', 1, 1, 'secretario', 'inactivo', 20, 'ingles', 0),
('x5564343T', 'Felix', 'Mariano', '699854712', 'ana@scarlatti.com', '21', 0, 1, 'LeriGroup', 1, 1, 'secretario', 'inactivo', 6, 'profesor de matematicas', 0),
('Y3625417U', 'EL MOURABIT ZARBOUK', 'MOHAMED SAID', '624836087', 'ana@scarlatti.com', '28300', 0, 1, 'infoDAW', 1, 0, 'ALE', 'activo', 2, 'INFORMATICA', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `dni` varchar(9) NOT NULL,
  `codigocurso` decimal(6,0) NOT NULL,
  `fechasolicitud` date NOT NULL,
  `admitido` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`dni`, `codigocurso`, `fechasolicitud`, `admitido`) VALUES
('A1324234S', 1, '2026-02-11', 0),
('A1324234S', 2, '2026-02-11', 0),
('A1324234S', 4, '2026-02-11', 0),
('A1324234S', 8, '2026-02-11', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `solicitantes`
--
ALTER TABLE `solicitantes`
  ADD PRIMARY KEY (`dni`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`dni`,`codigocurso`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
