SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS
= 0;


-- CREAMOS LA BASE DE DATOS SI NO EXISTE
CREATE DATABASE
IF NOT EXISTS `MP071`;

-- SELECCIONAMOS LA BD A USAR
USE `MP071`;

-- ELIMINAMOS LA TABLA NOTICIAS SI EXISTE
DROP TABLE IF EXISTS `noticias`;

-- CREAMOS LA TABLA NOTICIAS
CREATE TABLE `noticias`
(
  `id` int
(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'identificador de la noticia',
  `titulo` varchar
(100) CHARACTER
SET utf8mb4
COLLATE utf8mb4_general_ci NOT NULL COMMENT 'titulo de la noticia',
  `contenido` varchar
(300) CHARACTER
SET utf8mb4
COLLATE utf8mb4_general_ci NOT NULL COMMENT 'contenido de la noticia',
  `autor` varchar
(120) CHARACTER
SET utf8mb4
COLLATE utf8mb4_general_ci NOT NULL COMMENT 'identificador del autor - usuario',
  `fecha_creacion` timestamp
(0) NOT NULL DEFAULT CURRENT_TIMESTAMP
(0) COMMENT 'hora de la creacion',
  `likes` int
(10) UNSIGNED NULL DEFAULT 0 COMMENT 'numero de likes',
  PRIMARY KEY
(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET = utf8mb4
COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- SI EXISTE ELIMINAMOS LA TABLA USUARIOS
DROP TABLE IF EXISTS `usuarios`;

-- CREAMOS LA TABLA USUARIOS
CREATE TABLE `usuarios`
(
  `id` int
(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'identificador usuario',
  `nombre` varchar
(100) CHARACTER
SET utf8mb4
COLLATE utf8mb4_spanish_ci NOT NULL COMMENT 'Nombre del usuario',
  `contrasena` varchar
(50) CHARACTER
SET utf8mb4
COLLATE utf8mb4_general_ci NOT NULL COMMENT 'contraseña de usuario',
  `email` varchar
(50) CHARACTER
SET utf8mb4
COLLATE utf8mb4_general_ci NOT NULL COMMENT 'email del usuario',
  `edad` tinyint
(3) UNSIGNED NULL DEFAULT NULL COMMENT 'edad del usuario',
  `fecha_nacimiento` date NULL DEFAULT NULL COMMENT 'fecha nacimiento',
  `direccion` varchar
(120) CHARACTER
SET utf8mb4
COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'direccion del usuario',
  `cod_postal` varchar
(5) CHARACTER
SET utf8mb4
COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'codigo postal usuario',
  `provincia` varchar
(50) CHARACTER
SET utf8mb4
COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'provincia direccion',
  `genero` varchar
(1) CHARACTER
SET utf8mb4
COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'genero H/M',
  PRIMARY KEY
(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER
SET = utf8mb4
COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- AÑADIMOS UN USUARIO POR DEFECTO
INSERT INTO `usuarios` (`
nombre`,
`contrasena
`, `email`, `edad`, `fecha_nacimiento`, `direccion`, `cod_postal`, `provincia`, `genero`) VALUES
('admin', '1234', 'admin@test.es', 24, '2020-08-11', 'C/ La Luna, 3', '21001', 'Málaga', 'H');

-- AÑADIMOS UNAS NOTICIAS
INSERT INTO `
noticias`
VALUES
  (1, 'Mi primera noticia', 'Mi primer contenido...', 'Pep Match', '2020-12-07 14:06:49', 2);
INSERT INTO `
noticias`
VALUES
  (2, 'Segunda noticia', 'Contenido de la segunda noticia', 'administrador', '2020-12-07 14:28:08', 3);
INSERT INTO `
noticias`
VALUES
  (3, 'Tercera Noticia', 'Contenido de la tercera noticia', 'Usuario test', '2020-12-07 14:28:38', 1);
INSERT INTO `
noticias`
VALUES
  (4, 'Cuarta noticia', 'Contenido cuarta noticia', 'un autor', '2020-12-07 14:29:29', 0);
INSERT INTO `
noticias`
VALUES
  (5, 'Quinta Noticia', 'Contenido quinta noticia', 'Admin', '2020-12-08 08:58:31', 2);
INSERT INTO `
noticias`
VALUES
  (6, 'Sexta noticia', 'Contenido de la sexta noticia...', 'Jasper', '2020-12-08 09:57:02', 2);


SET FOREIGN_KEY_CHECKS
= 1;
