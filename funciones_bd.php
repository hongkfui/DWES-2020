<?php
/*
EJERCICIO 5
Contiene las distintas funciones que realizan las acciones desde los formularios con la BD
Resumen de las funciones:
listarUsuarios($mysqli, $id = null) -> devuelve un objeto mysqli para iterar por los usuarios
crearUsuario($datos, $mysqli) -> función para crear un usuario nuevo
actualizarUsuario($datos, $mysqli) -> functión para editar un usuario existente
eliminarUsuario($usuarioId, $mysqli) -> función para eliminar un usuario existente
listarNoticias($mysqli, $id = null, $limite = 0, $pagina = 0, $orden = null) -> función que devuelve un objeto de noticias preparado para iterar
crearNoticia($datos, $mysqli) -> función para crear una noticia nueva
actualizarNoticia($datos, $mysqli) -> función para actualizar una noticia existente
eliminarNoticia($noticiaId, $mysqli) -> función para eliminar una noticia existente
nuevoLike($noticiaId, $mysqli) -> función para añadir un like a una noticia
loginUsuario($usuario, $pass, $mysqli) -> función para comprobar las credenciales de un usuario al hacer login
cerrarConexion($mysqli) -> función para cerrar la la conexión actual
mostrarError($error, $mysqli) -> función que se encarga de mostrar un error si existe

documentación mysqli -> https://www.php.net/manual/es/mysqli-stmt.bind-param.php

 */

/**
 * función para solicitar todos los usuarios registrados a la BD
 * @param object $mysqli instancia de la conexión a la BD
 * @param int Opcional id de usuario a editar
 * @return object $result resultado de la consulta preparado para iterar
 */
function listarUsuarios($mysqli, $id = null)
{
    // creamos la sentencia select SQL
    $sql = "SELECT * FROM usuarios";
    if ($id) {
        $sql .= " WHERE id = $id";
    }
    // ejecutamos la consulta
    $result = $mysqli->query($sql);
    // si hay un error lo capturamos y mostramos el error
    if (!$result) {
        mostrarError($mysqli->error, $mysqli);
    }
    // devolvemos el resultado preparado para iterar
    return $result;
}

/**
 * funcion para crear un nuevo usuario
 * @param array - arreglo de datos enviados desde el formulario
 * @param object $mysqli - Instancia de la conexión a BD
 * @return int - id del registro creado
 */
function crearUsuario($datos, $mysqli)
{
    // preparar la cadena de inserción de usuario
    $sqlPrep = "INSERT INTO usuarios (nombre, contrasena, email, edad, fecha_nacimiento, direccion, cod_postal, provincia, genero)  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    // preparar la cadena SQL
    $stmt = $mysqli->prepare($sqlPrep);
    // comprobamos si la cadena SQL es correcta
    if (!$stmt) {
        mostrarError($mysqli->error, $mysqli);
    }
    // agregar los datos a insert preparado
    $stmt->bind_param(
        'sssisssss',
        $datos['nombre'],
        $datos['contrasena'],
        $datos['email'],
        $datos['edad'],
        $datos['fecha_nacimiento'],
        $datos['direccion'],
        $datos['cod_postal'],
        $datos['provincia'],
        $datos['genero']);

    // ejecutar la cadena preparada
    // si hay un error lo capturamos y mostramos el error
    if (!$stmt->execute()) {
        mostrarError($mysqli->error, $mysqli);
    }
    // devolver el id del registro creado
    $id = $stmt->insert_id;
    //cerrar
    $stmt->close();
    // devolvemos la id del registro insertado
    return $id;

}

/**
 * función para editar un usuario existente
 * @param array $datos Arreglo de datos provinientes del form usuario
 * @param object $mysqli Instancia de la conexión a BD
 * @return int $numFilas Num filas afectadas
 */
function actualizarUsuario($datos, $mysqli)
{
    // cadena de actualizacion del usuario
    $sqlPrep = "UPDATE usuarios SET
                nombre = ?,
                contrasena = ?,
                email = ?,
                edad = ?,
                direccion = ?,
                cod_postal = ?,
                provincia = ?,
                genero = ?,
                fecha_nacimiento = ?
                WHERE
                id = ?";

    $stmt = $mysqli->prepare($sqlPrep);
    // comprobamos si la cadena SQL es correcta
    if (!$stmt) {
        mostrarError($mysqli->error, $mysqli);
    }
    // agregar los datos a insert preparado
    $stmt->bind_param(
        'sssisssssi',
        $datos['nombre'],
        $datos['contrasena'],
        $datos['email'],
        $datos['edad'],
        $datos['direccion'],
        $datos['cod_postal'],
        $datos['provincia'],
        $datos['genero'],
        $datos['fecha_nacimiento'],
        $datos['id']);

    // ejecutar la cadena preparada
    // si hay un error lo capturamos y mostramos el error
    if (!$stmt->execute()) {
        mostrarError($mysqli->error, $mysqli);
    }
    // devolver el número de filas afectadas
    $filas = $stmt->affected_rows;
    //cerrar
    $stmt->close();
    // devolver resultado
    return $filas;

}

/**
 * función para eliminar un usuario dada su id
 * @param int $usuarioId - identificador del usuario
 * @param object $mysqli Instancia actual de la conexión a la BD
 * @return int Número de filas afectadas
 */
function eliminarUsuario($usuarioId, $mysqli)
{
    // SQL para eliminar el registro
    $sql = "DELETE FROM usuarios WHERE id=$usuarioId";
    // ejecutamos la consulta
    $result = $mysqli->query($sql);
    // si hay un error lo capturamos y mostramos el error
    if (!$result) {
        mostrarError($mysqli->error, $mysqli);
    }
    // retornamos el número de filas afectadas debe ser 1
    return $mysqli->affected_rows;
}

/**
 * funcion para recuperar las noticias
 * @param object $mysqli Instancia actual de la conexión a la BD
 * @param int $id Opcional id de noticia a recuperar, sin id devuelve todas
 * @param int $limite Opcional límite de noticias a recuperar si es 0 no pagina
 * @param int $pagina Opcional página actual - paginación
 * @return object $query Objeto de datos preparado para iterar
 */
function listarNoticias($mysqli, $id = null, $limite = 0, $pagina = 0, $orden = null)
{

    // calculamos los límites
    $lInferior = $limite * $pagina;
    $lSuperior = $lInferior + $limite;
    $sql = "SELECT * FROM noticias";
    // si hay un id concatenamos los datos
    if ($id) {
        $sql .= " WHERE id = $id";
    }
    // si hay orden
    if ($orden) {
        $sql .= " ORDER BY $orden ";
    }
    // si hay límite paginamos
    if ($limite > 0) {
        $sql .= " LIMIT $lInferior, $lSuperior";
    }
    // ejecutamos la consulta
    $result = $mysqli->query($sql);
    // si hay un error lo capturamos y mostramos el error
    if (!$result) {
        mostrarError($mysqli->error, $mysqli);
    }
    // devolvemos el resultado preparado para iterar
    return $result;
}

/**
 * método para crear una noticia
 * @param array - Datos del formulario noticia
 * @param mysqli - Instancia de la conexión de BD
 * @return int - Retorna el id de la notica creada.
 */
function crearNoticia($datos, $mysqli)
{
    // preparar la cadena de inserción de noticia
    $sqlPrep = "INSERT INTO
                noticias (titulo, contenido, autor)
                VALUES (?, ?, ?)";
    // preparar la cadena SQL
    $stmt = $mysqli->prepare($sqlPrep);
    // comprobamos si la cadena SQL es correcta
    if (!$stmt) {
        mostrarError($mysqli->error, $mysqli);
    }
    // agregar los datos a insert preparado
    $stmt->bind_param(
        'sss',
        $datos['titulo'],
        $datos['contenido'],
        $datos['autor']);

    // ejecutar la cadena preparada
    // si hay un error lo capturamos y mostramos el error
    if (!$stmt->execute()) {
        mostrarError($mysqli->error, $mysqli);
    }
    // devolver el id de la noticia creada
    $id = $stmt->insert_id;
    //cerrar
    $stmt->close();
    // devolver resultado
    return $id;
}

/**
 * función para editar una noticia
 * @param array - Arreglo de datos del formulario noticia
 * @param object $mysqli Instancia de la conexión a BD
 * @return int $numFilas Num filas actualizadas
 */
function actualizarNoticia($datos, $mysqli)
{
    // preparar la cadena de actualizacion de noticia
    $sqlPrep = "UPDATE noticias SET
                titulo = ?,
                contenido = ?,
                autor = ?
                WHERE
                id = ?";
    // preparar la cadena SQL
    $stmt = $mysqli->prepare($sqlPrep);
    // comprobamos si la cadena SQL es correcta
    if (!$stmt) {
        mostrarError($mysqli->error, $mysqli);
    }
    // agregar los datos a insert preparado
    $stmt->bind_param(
        'sssi',
        $datos['titulo'],
        $datos['contenido'],
        $datos['autor'],
        $datos['id']);

    // ejecutar la cadena preparada
    // si hay un error lo capturamos y mostramos el error
    if (!$stmt->execute()) {
        mostrarError($mysqli->error, $mysqli);
    }
    // devolver el número de filas afectadas
    $filas = $stmt->affected_rows;
    //cerrar
    $stmt->close();
    // devolver resultado
    return $filas;
}

/**
 * función para eliminar una noticia dada su id
 * @param int $noticiaId - identificador del usuario
 * @param object $mysqli Instancia actual de la conexión a la BD
 * @return int Número de filas afectadas
 */
function eliminarNoticia($noticiaId, $mysqli)
{
    // cadena SQL para eliminar la noticia
    $sql = "DELETE FROM noticias WHERE id=$noticiaId";
    // ejecutamos la consulta
    $result = $mysqli->query($sql);
    // si hay un error lo capturamos y mostramos el error
    if (!$result) {
        mostrarError($mysqli->error, $mysqli);
    }
    // retornamos el número de filas afectadas debe ser 1
    return $mysqli->affected_rows;
}

/**
 * función para añadir un like a una noticia
 * @param int $noticiaId de noticia a añadir like
 * @param object $mysqli Instancia de la conexión a BD
 * @return int Numero de likes total
 */
function nuevoLike($noticiaId, $mysqli)
{
    // preparar la cadena de actualizacion de noticia
    $sqlPrep = "UPDATE noticias
      SET likes = likes + 1
      WHERE
      id = ?";
    // preparar la cadena SQL
    $stmt = $mysqli->prepare($sqlPrep);
    // comprobamos si la cadena SQL es correcta
    if (!$stmt) {
        mostrarError($mysqli->error, $mysqli);
    }

    // agregar los datos a insert preparado
    $stmt->bind_param('i', $noticiaId);

    // ejecutar la cadena preparada
    // si hay un error lo capturamos y mostramos el error
    if (!$stmt->execute()) {
        mostrarError($mysqli->error, $mysqli);
    }
    // devolver el número de filas afectadas
    $filas = $stmt->affected_rows;
    //cerrar
    $stmt->close();
    // devolver resultado
    return $filas;
}

/**
 * función para comprobar las credenciales de un usuario
 * @param string $usuario Nombre de usuario
 * @param object $pass clave del usuario
 * @param object $mysqli instancia de la conexión a la BD
 * @return array Resultado de la consulta modo asociativo
 */
function loginUsuario($usuario, $pass, $mysqli)
{
    // cadena SQL para comprobar las credenciales
    $sql = "SELECT *
            FROM usuarios
            WHERE
            nombre = '$usuario' AND
            contrasena = '$pass'
            LIMIT 1";

    // ejecutamos la consulta
    $result = $mysqli->query($sql);
    // si hay un error lo capturamos y mostramos el error
    if (!$result) {
        mostrarError($mysqli->error, $mysqli);
    }
    // retornamos el número de coincidencias
    return $result->fetch_assoc();
}
/**
 * functión para cerrar la conexión mysqli
 * @param object $mysqli instancia de la conexión a la BD
 */
function cerrarConexion($mysqli)
{
    // cerramos la instancia de la conexión actual
    mysqli_close($mysqli);
}

/**
 * funcion para mostar un mensaje si existe error en la consulta sql
 * @param string $error Error reportado por msyqli
 * @param object $mysqli instancia de la conexión a la BD
 */
function mostrarError($error, $mysqli)
{
    // creamos las cadenas de texto
    $msg = "Error Reportado MySQL: <b>$error</b>";
    $mensajeClase = "alert-danger";
    echo htmlMensaje($msg, $mensajeClase);
    // si mostramos el error cerramos la conexión
    cerrarConexion($mysqli);
    // cancelamos la ejecución de la aplicación
    die();
}