<?php
// -------------------------------------
// EJERCICIO 8
//  Menú de navegación con las distintas opciones
//
// -------------------------------------

// comprobamos que el usuario tiene sesión iniciada
$existeSesion = isset($_SESSION['user1']) ? $_SESSION['user1'] : null;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-purple">
  <a class="navbar-brand" href="index.php">DWES M07</a>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <!-- items fijos -->
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Inicio <span class="sr-only">(current)</span></a>
      </li>
      <!-- items dinámicos -->
      <?php
      if ($existeSesion) {
        // si estamos identificados mostramos todas las opciones de menú a excepción de login
        echo '
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=usu-list">Usuarios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=noti-list">Noticias</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=usu-form">Crear Usuario</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=noti-form">Crear Noticia</a>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item float-right">
            <a class="nav-link" href="index.php?page=salir">Salir</a>
          </li>
        </ul>';
      } else {
        // si no estamos identificados mostramos el acceso a login
        echo '
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item float-right">
            <a class="nav-link" href="index.php?page=login">Identificarse</a>
          </li>
        </ul>';
      }
      ?>

  </div>
</nav>