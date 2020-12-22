<?php
// inciamos la sesión
session_start();

// En producción descactivar las notificaciones
// 0 - para no mostrar
// -1 para mostrarlas
error_reporting(-1);

// -------------------------------------
// funciones generales del desarrollo
// -------------------------------------
/**
 * función para mostrar un alert al tratar de guardar o editar un registro
 * @param string $mensaje texto a mostrar
 * @param string $mensajeClase Clase bootstrap para dar color a la alerta
 * @return string HTML para insertar en el documento
 */
function htmlMensaje($mensaje, $mensajeClase)
{
  $clase = "alert " . $mensajeClase;
  return "
      <div class='row mt-2'>
        <div class='col-12'>
          <div class='$clase'>$mensaje</div>
        </div>
      </div>";
}

/**
 * función para comprobar si el usuario tiene la sesión iniciada y en caso contrario redireccionamos al index por defecto
 */
function comprobarLogeado()
{
  if (!isset($_SESSION['user1'])) {
    header('location: index.php');
  }
}

// recoger página a mostar por parámetro get
$pagina = isset($_GET['page']) ? $_GET['page'] : null;
// si el acceso es a un página comprobamos si está logeado si no solo damos acceso al index y login
if ($pagina && $pagina !== "login") {
  comprobarLogeado();
}

// requires
require 'conexion.php';
require 'funciones_bd.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ejercicio DWES</title>
  <!-- css personalizado -->
  <link rel="stylesheet" href="assets/css/custom.css" />
  <!-- importamos bootstrap desde su CDN para dar estilo al desarrollo -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
    integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body class="d-flex flex-column min-vh-100">
  <!-- cabecera -->
  <?php include "comun/cabecera.php"; ?>
  <!-- body -->
  <div class="wrapper flex-grow-1">
    <div class="container-fluid mt-5">
      <?php

      // controlar mediante switch que página o páginas se debe mostrar
      switch ($pagina) {
        case 'usu-form': // formulario de usuarios
          require "usuarios/form_usuario.php";
          break;
        case 'usu-list': // lista de usuarios
          require "usuarios/list_usuarios.php";
          break;
        case 'noti-form': // formulario de noticias
          require "noticias/form_noticias.php";
          break;
        case 'noti-list': // lista de noticias
          require "noticias/list_noticias.php";
          break;
        case 'login': // página login
          require "login.php";
          break;
        case 'salir': // página salir y cerrar la sesión
          session_destroy();
          header('location: index.php');
          break;
        default: // página por defecto
          include "cuerpo.php";
          break;
      }

      // cerramos la conexión a BD
      cerrarConexion($mysqli);

      ?>
    </div>
  </div>
  <!-- footer -->
  <?php include "comun/footer.php"; ?>
</body>

</html>