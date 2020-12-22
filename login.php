<?php
// -------------------------------------
// funciones
// -------------------------------------
/**
 * función para hacer login comprobando el nombre de usuario y su contraseña
 * @param string $usuario nombre de usuario
 * @param string $pass contraseña de usuario
 * @param object $mysqli instancia de la conexión a BD
 */
function hacerLogin($usuario, $pass, $mysqli)
{
    // comprobamos en la BD
    $resultado = loginUsuario($usuario, $pass, $mysqli);
    // si obtenemos el resultado
    if ($resultado) {
        // creamos la sesión con el id de usuario
        // nota: podemos guardar más datos pero como ejemplo nos sirve así
        $_SESSION['user1'] = $resultado['id'];
        // redireccionamos a index
        header('location: index.php');
    } else {
        // si hay un error en las credenciales mostramos un aviso!
        return "Error en las credenciales";
    }

}

// si hay sesión iniciada redireccionamos al index
$accion = isset($_GET['act']) ? $_GET['act'] : "";
$errorCredenciales = null;

// comprobamos si estamos haciendo login
$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : null;
$pass = isset($_POST['pass']) ? $_POST['pass'] : null;

// comprobamos que está todo correcto para hacer la llamada a BD
if ($accion === "login" && $usuario && $pass) {
    // hacemos login
    $errorCredenciales = hacerLogin($usuario, $pass, $mysqli);
}
?>
<!--
  ZONA HTML
 -->
<div class="login-page">
  <div class="form">
    <form class="login-form" method="POST" action="index.php?page=login&act=login">
      <input type="text" name="usuario" placeholder="Usuario" value="<?=$usuario;?>" required />
      <input type="password" name="pass" placeholder="Contraseña" value="<?=$pass;?>" required />
      <button type="submit">Acceder</button>
      <p class="message"><?=$errorCredenciales;?></p>
    </form>
  </div>
</div>