<?php
/*
EJERCICIO 2 - conexion.php
Generar la conexión a la BD
 */

// ------------------------------------
// constantes para la conexión de la BD
// ------------------------------------
define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "");
define("DB", "m07");
define("PORT", "3306");
    
// creamos la instancia de la conexión
$mysqli = new mysqli(HOST, USER, PASSWORD, DB, PORT);

// comprobación de errores
if (!$mysqli || $mysqli->connect_error) {
    // mostramos mensaje de error
    echo "<div class='alert alert-danger mx-2 mt-2'>
            No se ha podido conectar a la Base de Datos, Error No: " . $mysqli->connect_errno  . " <br/>
            Error de depuración: " .  $mysqli->connect_error. "
            </div>";
    // Al tener un error finalizamos la ejecución
    die();
}

/* cambiamos por defecto el conjunto de caracteres a utf8 */
$mysqli->set_charset("utf8");

