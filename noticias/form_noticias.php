<?php
// -------------------------------------
// EJERCICIO 4.2
// Formulario para crear, editar y eliminar noticias
// -------------------------------------

// -------------------------------------
// variables
// -------------------------------------
$mensaje = null;
$mensajeClase = null;
$accion = "nuevo";

// declaramos las variables del formulario
$id = isset($_POST['id']) && $_POST['id'] !== "" ? $_POST['id'] : null;
$titulo = isset($_POST['titulo']) && $_POST['titulo'] !== "" ? $_POST['titulo'] : null;
$autor = isset($_POST['autor']) && $_POST['autor'] !== "" ? $_POST['autor'] : null;
$contenido = isset($_POST['contenido']) && $_POST['contenido'] !== "" ? $_POST['contenido'] : null;

// comprobamos si está en parámetro id de noticia para editarlo
$act = isset($_GET['act']) ? $_GET['act'] : null;
$notiId = isset($_GET['id']) ? $_GET['id'] : null;

// comprobamos si están todos los parámetros correctos
if ($act === "editar" && $notiId) {
    $accion = "editar";
} else if ($act === "eliminar" && $notiId) {
    $accion = "eliminar";
}

// si hay datos en POST estamos guardando
if ($_POST) {
    // comprobamos si guardamos un registro nuevo o existente
    if ($_POST['id'] && is_numeric($_POST['id'])) {
        $accion = "guardar-editar";
    } else {
        $accion = "guardar-nuevo";
    }
}
// dependiendo de la peticion seleccionamos acciones distintas, insert, update, delete
switch ($accion) {
    case 'guardar-nuevo':
        // llamamos a la funcion para realizar el INSERT en BD tabla usuarios
        $id = crearNoticia($_POST, $mysqli);
        // finalmente mostramos un mensaje dependiendo del resultado
        if ($id > 0) {
            $mensaje = "Creada una nueva noticia correctamente";
            $mensajeClase .= "alert-success";
        } else {
            $mensaje = "Ha habido un error al tratar de crear el registro";
            $mensajeClase .= "alert-danger";
        }
        break;
    case 'guardar-editar':
        // llamamos a la función para realizar el UPDATE en la BD
        $res = actualizarNoticia($_POST, $mysqli);
        // finalmente mostramos un mensaje dependiendo de la acción realizada
        if ($res === 1) {
            $mensaje = "Actualizado el registro correctamente";
            $mensajeClase .= "alert-success";
        } else if ($res === 0) {
            $mensaje = "No ha habido cambios por tanto no es necesario actualizar el registro";
            $mensajeClase .= "alert-primary";
        } else {
            $mensaje = "Ha habido un error al tratar de actualizar el registro id:$res";
            $mensajeClase .= "alert-danger";
        }
        break;
    case 'editar':
        // recuperamos el registro de BD
        $tmp = listarNoticias($mysqli, $notiId);
        // actualizamos las variables a los datos de respuesta
        $noti = $tmp->fetch_array(MYSQLI_ASSOC);
        $id = $noti['id'];
        $titulo = $noti['titulo'];
        $autor = $noti['autor'];
        $contenido = $noti['contenido'];
        break;
    case 'eliminar':
        // llamamos a la función eliminarNoticia
        $res = eliminarNoticia($notiId, $mysqli);
        $btn = "<a class='btn btn-sm btn-outline-secondary float-right' href='index.php?page=noti-list'>volver</a>";
        $mensajeClase = "alert-success";
        $mensaje = "Eliminada la Noticia correctamente..." . $btn;
        // mostramos un mensaje por pantalla, tanto si ha habido un error o no
        // si ha habido algún error
        if ($res !== 1) {
            $mensaje = "Ha habido un error al tratar de eliminar el registro, probablemente no exista..." . $btn;
            $mensajeClase = "alert-danger";
        }
        echo htmlMensaje($mensaje, $mensajeClase);
        // hacemos return para no seguir cargando el formulario!
        return;
        break;
}

?>

<!--
  zona html
-->
<div class="card mt-5 shadow">
  <div class="card-header bg-dark text-white">
    Formulario de Noticias
  </div>
  <div class="card-body">
    <?php
if ($mensaje) {
    echo htmlMensaje($mensaje, $mensajeClase);
}
?>
    <form class="m-4" method="POST" action="index.php?page=noti-form" accept-charset="UTF-8">
      <input type="hidden" value="<?=$id;?>" name="id" />
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="inputTitulo">Título</label>
            <input type="text" class="form-control" id="inputTitulo" name="titulo" required value="<?=$titulo;?>">
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="inputAutor">Autor</label>
            <input type="text" class="form-control" id="inputAutor" name="autor" required value="<?=$autor;?>">
          </div>
        </div>
      </div> <!-- ./row -->
      <div class="row">

      </div> <!-- ./row -->
      <div class="row">
        <div class="col-12">
          <label for="inputContenido">Contenido</label>
          <textarea class="form-control" id="inputContenido" rows="5" required name="contenido"
            maxlength="300"><?=$contenido;?></textarea>
        </div>
      </div> <!-- ./row -->

      <hr />

      <button type="submit" class="btn btn-outline-primary float-right">GUARDAR</button>
      <a class="btn btn-outline-warning mr-1 float-right" href="index.php">CANCELAR</a>

  </div> <!-- ./card-body -->
  </form>
</div> <!-- ./card -->