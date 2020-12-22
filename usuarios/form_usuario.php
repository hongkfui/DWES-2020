<?php
// -------------------------------------
// EJERCICIO 4.1
// Formulario para crear, editar y eliminar usuarios
// -------------------------------------

// -------------------------------------
// variables
// -------------------------------------
$mensaje = null;
$mensajeClase = null;
$accion = "nuevo";

/*
variables del formulario en caso que haya datos en el post las inicializamos con el post o a null
 */
$id = isset($_POST['id']) && $_POST['id'] !== "" ? $_POST['id'] : null;
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
$contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : null;
$edad = isset($_POST['edad']) ? $_POST['edad'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null;
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : null;
$cod_postal = isset($_POST['cod_postal']) ? $_POST['cod_postal'] : null;
$provincia = isset($_POST['provincia']) ? $_POST['provincia'] : null;
$genero = isset($_POST['genero']) ? $_POST['genero'] : null;

// comprobamos si está en parámetro act e id de usuario para editarlo
$act = isset($_GET['act']) ? $_GET['act'] : null;
$usuId = isset($_GET['id']) ? $_GET['id'] : null;

// comprobamos si están todos los parámetros correctos
if ($act === "editar" && $usuId) {
    $accion = "editar";
} else if ($act === "eliminar" && $usuId) {
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

switch ($accion) {
    case 'guardar-nuevo':
        // llamamos a la funcion para realizar el INSERT en BD tabla usuarios
        $id = crearUsuario($_POST, $mysqli);
        // finalmente mostramos un mensaje dependiendo del resultado
        if ($id > 0) {
            $mensaje = "Creado un nuevo registro correctamente";
            $mensajeClase .= "alert-success";
        } else {
            $mensaje = "Ha habido un error al tratar de crear el registro";
            $mensajeClase .= "alert-danger";
        }
        break;
    case 'guardar-editar':
        // llamamos a la función para realizar el UPDATE en la BD
        $res = actualizarUsuario($_POST, $mysqli);
        // finalmente mostramos un mensaje dependiendo de la acción realizada
        if ($res === 1) {
            $mensaje = "Actualizado el registro correctamente";
            $mensajeClase .= "alert-success";
        } else if ($res === 0) {
            $mensaje = "No ha habido cambios por tanto no ha sido necesario actualizar el registro";
            $mensajeClase .= "alert-primary";
        } else {
            $mensaje = "Ha habido un error al tratar de actualizar el registro id:$res";
            $mensajeClase .= "alert-danger";
        }
        break;
    case 'editar':
        // recuperamos el registro de BD
        $tmp = listarUsuarios($mysqli, $usuId);
        $usu = $tmp->fetch_array(MYSQLI_ASSOC);
        // seteamos las variables
        $id = $usu['id'];
        $nombre = $usu['nombre'];
        $contrasena = $usu['contrasena'];
        $edad = $usu['edad'];
        $email = $usu['email'];
        $fecha_nacimiento = $usu['fecha_nacimiento'];
        $direccion = $usu['direccion'];
        $cod_postal = $usu['cod_postal'];
        $provincia = $usu['provincia'];
        $genero = $usu['genero'];
        break;
    case "eliminar":
        $res = eliminarUsuario($usuId, $mysqli);
        $btn = "<a class='btn btn-sm btn-outline-secondary float-right' href='index.php?page=usu-list'>volver</a>";
        $mensajeClase = "alert-success";
        $mensaje = "";

        // si se ha eliminado el usuario
        if ($res === 1) {
            $mensaje = "Eliminado el usuario correctamente..." . $btn;

        } else {
            $mensaje = "Ha habido un error al tratar de eliminar el registro, probablemente no exista..." . $btn;
            $mensajeClase = "alert-danger";
        }
        // mostramos un mensaje al finalizar
        echo htmlMensaje($mensaje, $mensajeClase);
        // hacemos return para no seguir cargando el formulario!
        return;
        break;
}

?>


<!-- zona html -->
<div class="card mt-5 shadow">
  <div class="card-header bg-dark text-white">
    Formulario de Usuario
  </div>
  <div class="card-body">
    <!-- mensaje al guardar o editar un registro -->
    <?php
if ($mensaje) {
    echo htmlMensaje($mensaje, $mensajeClase);
}
?>
    <form class="m-4" method="POST" action="index.php?page=usu-form" accept-charset="UTF-8">
      <input type="hidden" name="id" value="<?=$id;?>">
      <div class="row">
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="inputNombre">Nombre</label>
            <input type="text" class="form-control" id="inputNombre" name="nombre" required value="<?=$nombre;?>">
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="inputContrasena">Contraseña</label>
            <input type="password" class="form-control" id="inputContrasena" name="contrasena" required
              value="<?=$contrasena;?>">
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="inputEmail">eMail</label>
            <input type="email" class="form-control" id="inputEmail" name="email" required value="<?=$email;?>">
          </div>
        </div>
      </div> <!-- ./row -->
      <div class="row">
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="inpuitFechaNac">Fecha Nacimiento</label>
            <input type="date" class="form-control" id="inpuitFechaNac" name="fecha_nacimiento"
              value="<?=$fecha_nacimiento;?>" required>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="inputEdad">Edad</label>
            <input type="number" class="form-control" id="inputEdad" min="1" max="120" name="edad" value="<?=$edad;?>">
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="inputGenero">Genero</label>
            <select class="form-control" id="inputGenero" name="genero" required>
              <option value="">Seleccionar...</option>
              <option value="H" <?=$genero === 'H' ? 'selected' : '';?>>Hombre</option>
              <option value="M" <?=$genero === 'M' ? 'selected' : '';?>>Mujer</option>
            </select>
          </div>
        </div>
      </div> <!-- ./row -->
      <div class="row">
        <div class="col-12">
          <div class="form-group">
            <label for="inputDireccion">Dirección</label>
            <input type="text" class="form-control" id="inputDireccion" name="direccion" value="<?=$direccion;?>">
          </div>
        </div>
      </div> <!-- ./row -->

      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="inputCodPostal">Cod. Postal</label>
            <input type="number" class="form-control" id="inputCodPostal" min="1" pattern="/d{5}" max="99999" name="cod_postal" maxlength="5"
              value="<?=$cod_postal;?>">
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="inputProvincia">Provincia</label>
            <input type="text" class="form-control" id="inputProvincia" name="provincia" value="<?=$provincia;?>">
          </div>
        </div>
      </div> <!-- ./row -->

      <hr />

      <button type="submit" class="btn btn-outline-primary float-right">GUARDAR</button>
      <a class="btn btn-outline-warning mr-1 float-right" href="index.php">CANCELAR</a>

  </div> <!-- ./card-body -->
  </form>
</div> <!-- ./card -->