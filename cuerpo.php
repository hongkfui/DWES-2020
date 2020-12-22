<?php
// EJERCICIO 3 - Mostrar las 5 últimas noticias ordenadas por fecha desc
// -------------------------------------
// funciones
// -------------------------------------
/**
 * funcion para generar dinámicamente una tarjeta con los datos de las noticias
 * @param object $datos Objeto de datos de una noticia
 * @return string Html preparado para mostrar las noticias
 */
function htmlNoticias($datos)
{
    // generamos el título
    $html = '
    <div class="row my-3">
        <div class="col-12">
            <h4>Últimas Noticias</h4>
        </div>
    </div>
    ';

    // iteramos por los registros
    while ($f = $datos->fetch_assoc()) {
        // generamos la fecha
        $fecha = strtotime($f['fecha_creacion']);
        $fecha = date("d/m/Y", $fecha);
        // generar el html de la tarjeta con los datos necesarios
        $html .= "
        <div class='row my-2'>
            <div class='col-md-12'>
                <div class='card'>
                    <div class='card-header bg-purple text-white'>
                    <h5>" . $f['titulo'] . "</h5>
                    </div>
                    <div class='card-body'>
                        <h5 class='card-title'></h5>
                        <p class='card-text'> " . $f['contenido'] . "</p>
                        <hr class='mt-5' />
                        <span class='text-muted small'>Autor: " . $f['autor'] . ", Fecha: $fecha</span>
                        <button class='btn btn-sm btn-outline-secondary float-right' disabled>Likes " . $f['likes'] . "</button>
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
// límite de páginas a mostrar
$limite = 5;
// NOTA:paginado deshabilitado, se puede probar pasando por parámetro el numero de pagina p=0
// la primera página es 0, p=1 <- segunda página
// comprobamos si tenemos el parametro paginado
$pagina = isset($_GET['p']) ? $_GET['p'] : 0;
// si el paginado es menor 0 lo forzamos a 0
if ($pagina < 0) {
    $pagina = 0;
}

// realizamos la consulta a la base de datos
$noticias = listarNoticias($mysqli, null, $limite, $pagina, "fecha_creacion DESC");

// si no hay noticias, mostramos una alerta
if ($noticias->num_rows === 0) {
    // añadimos un boton para volver al inicio en caso que no hayan registros
    $btn = "<a class='btn btn-sm btn-outline-dark float-right my-0' href='index.php'>volver</a>";
    // mostramos la alerta indicando que no hay registros que mostrar
    echo htmlMensaje("No hay Noticias que mostrar....$btn", "alert-info");
} else {
    // mostramos las noticias a modo de tarjetas
    echo htmlNoticias($noticias);

}