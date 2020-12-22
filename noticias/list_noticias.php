<?php
// -------------------------------------
// EJERCICIO 7
//  Mostrar las noticias con un botón like
// almacenar datos en cookies y actualizar el campo en la tabla
// -------------------------------------

// -------------------------------------
// funciones
// -------------------------------------
/**
 * función para hacer like a una noticia
 * @param int $noticiaId id de la noticia a hacer like
 * @param object $mysqli instancia de la conexión a BD
 */
function hacerLike($noticiaId, $mysqli)
{
    // insertamos en la BD un like
    nuevoLike($noticiaId, $mysqli);

    //comprobamos si tenemos ya los datos en la cookie o no
    $cookie = (isset($_COOKIE['usuLikes'])) ? json_decode($_COOKIE['usuLikes']) : [];
    /*
    añadimos al array la cookie con los datos relativos al usuario y a la noticia
    para mayor funcionalidad creamos un array de objectos para manejar los datos más eficientemente
    Seteamos el usuario en base a si está logeado o no
     */
    $cookie[] = [
        "usuario" => isset($_SESSION['user1']) ? $_SESSION['user1'] : "anonimo",
        "noticiaId" => $noticiaId,
    ];
    // finalmente actualizamos la cookie con los datos actuales
    setcookie('usuLikes', json_encode($cookie));

    // redireccionamos a la propia página sin los parámetros get para evitar nuevos likes ante una posible recarga
    header('location: index.php?page=noti-list');

}

/**
 * funcion para generar dinámicamente una tarjeta con los datos de las noticias
 * @param object $datos Objeto de datos de una noticia
 * @return string Html preparado para mostrar las noticias
 */
function htmlNoticias($datos)
{
    // generamos el título
    $html = "
        <div class='row my-3'>
            <div class='col-12'>
                <h4>
                Todas las Noticias
                <a class='btn btn-sm btn-outline-primary float-right' href='index.php?page=noti-form'>Nueva Noticia</a>
                </h4>
            </div>
        </div>
    ";

    // iteramos por los registros
    while ($f = $datos->fetch_assoc()) {

        // generar el html de la tarjeta con los datos necesarios
        $ftitulo = $f['titulo'];
        $contenido = $f['contenido'];
        $likes = $f['likes'];
        $id = $f['id'];
        $autor = $f['autor'];
        $fecha = strtotime($f['fecha_creacion']);
        $fecha = date("d/m/Y", $fecha);

        $html .= "
        <div class='row my-2'>
            <div class='col-md-12'>
                <div class='card'>
                    <div class='card-header text-white bg-dark-purple'>
                    <h5> $ftitulo</h5>
                    </div>
                    <div class='card-body'>
                        <h5 class='card-title'></h5>
                        <p class='card-text'> $contenido </p>
                        <hr class='mt-5 mb-1' />
                        <div class='text-muted small mb-1 mt-0'>Autor: $autor, Fecha: $fecha </div>
                        <a class='btn btn-sm btn-outline-success' href='index.php?page=noti-list&act=like&lid=$id'>Likes $likes</a>
                        <a class='btn btn-sm btn-outline-danger ml-1 float-right' href='index.php?page=noti-form&act=eliminar&id=$id'>Eliminar</a>
                        <a class='btn btn-sm btn-outline-primary float-right' href='index.php?page=noti-form&act=editar&id=$id'>Editar</a>
                    </div>
                </div>
            </div>
        </div>
      ";
    }
    // retornar html preparado
    return $html;
}

// -------------------------------------
// variables
// -------------------------------------

// comprobamos los parámetros por si hay que hacer un like
$accion = isset($_GET['act']) ? $_GET['act'] : null;
$likeId = isset($_GET['lid']) ? $_GET['lid'] : null;

// si la accion es like
if ($accion === "like" && $likeId) {
    // llamamos a la función para crear un like
    $resultado = hacerLike($likeId, $mysqli);
}

// realizamos la consulta a la base de datos para traer las noticias
$noticias = listarNoticias($mysqli, null, 0, 0);

// si no hay noticias, mostramos una alerta
if ($noticias->num_rows === 0) {
    // añadimos un boton para volver al inicio en caso que no hayan registros
    $btn = "<a class='btn btn-sm btn-outline-dark float-right my-0' href='index.php'>volver</a>";
    echo htmlMensaje("No hay Noticias que mostrar....$btn", "alert-info");
} else {
    // mostramos las noticias a modo de tarjetas
    echo htmlNoticias($noticias);
}