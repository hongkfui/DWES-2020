<?php

/*
ejercicio 6
crear archivo que muestre una lista de usuarios incluida en index.php
mostrará el listado de todos los usuarios, contendrá botones para crear, modificar o eliminar usuarios
 */

// -------------------------------------
// funciones
// -------------------------------------
/**
 * función para mostrar un título
 * @return string - Cadena de texto mostrando un aviso
 */
function htmlTitulo()
{
    return "
      <div class='row mt-5'>
        <div class='col-12'>
          <h4>Listado de Usuarios Registrados
            <a href='index.php?page=usu-form' class='btn btn-outline-primary float-right'>
            Nuevo Usuario
            </a>
          </h4>
        </div>
      </div>
      ";
}

/**
 * función para generar una tabla mostrando la lista de usuarios y sus acciones
 * @param object $datos Resultado de la consulta SQL
 * @return string Cadena de texto con la tabla montada dinámicamente
 */
function htmlTablaUsuarios($datos)
{
    $filas = "";
    // iteramos por los registros
    while ($f = $datos->fetch_assoc()) {
        // tratamos el género
        $genero = ($f['genero'] === 'H') ? 'Hombre' : ' Mujer';
        // fecha nacimiento
        $fechaNac = strtotime($f['fecha_nacimiento']);
        $fechaNac = date("d/m/Y", $fechaNac);
        // creamos la fila correspondiente al registro actual
        $filas .= "<tr>";
        $filas .= "<td class='text-right'>" . $f['id'] . "</td>";
        $filas .= "<td>" . $f['nombre'] . "</td>";
        $filas .= "<td>" . $f['email'] . "</td>";
        $filas .= "<td>" . $fechaNac . "</td>";
        $filas .= "<td>" . $f['edad'] . "</td>";
        $filas .= "<td>" . $f['provincia'] . "</td>";
        $filas .= "<td>" . $genero . "</td>";
        // botones de acción
        $filas .= "<td class='text-center'>";
        $filas .= "<a href='index.php?page=usu-form&act=editar&id=" . $f['id'] . "' class='btn btn-sm btn-outline-primary'>editar</a>";
        $filas .= "<a href='index.php?page=usu-form&act=eliminar&id=" . $f['id'] . "' class='btn btn-sm btn-outline-danger ml-1'>eliminar</a>";
        $filas .= "</td>";
        $filas .= "</tr>";
    }

    $tabla = "
            <div class='row mt-5'>
              <div class='col-md-12'>
                <div class='table-responsive'>
                  <table class='table table-sm table-striped'>
                    <thead class='bg-dark-purple text-white'>
                      <tr>
                        <th scope='col' width='35'>#</th>
                        <th scope='col'>Nombre</th>
                        <th scope='col'>eMail</th>
                        <th scope='col'>F. Nacimiento</th>
                        <th scope='col'>Edad</th>
                        <th scope='col'>Provincia</th>
                        <th scope='col'>Genero</th>
                        <th scope='col' width='160'>Acciones</th>
                      </tr>
                    </thead>
                      <tbody> $filas </tbody>
                    </table>
                </div>
              </div>
            </div>";

    return $tabla;

}

// mostramos titulo del archivo
echo htmlTitulo();

// recopilamos de la BD los usuarios
$usuarios = listarUsuarios($mysqli);

// comprobamos si hay usuarios en la tabla
if ($usuarios->num_rows === 0) {
    // si no existen usuarios mostramos un aviso
    echo htmlMensaje("No hay registrado ningún usuario!", "alert-info");
} else {
    // si hay datos llamamos la función para mostrar la tabla de usuarios
    echo htmlTablaUsuarios($usuarios);
}