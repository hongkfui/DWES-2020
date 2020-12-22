# DWES-2020
Ejercicio desarrollo DWES 2020  - php

# Requerimientos:
 - Servidor web apache 2.4
 - Versión de php 7.4
 - Servidor MySQL 5.7

Ejercicio de desarrollo de la asignatura de des. entorno servidor.

Para la consecución del ejercicio se ha utilizado php sin frameworks

Para la mejora de la visualización se ha utilizado bootstrap 4.*

# Instalación

Descargar y descomprimir el proyecto dentro de una carpeta cualquiera dentro del servidor web.
Ejecutar el fichero script_mysql.sql para generar las tablas y algunos registros de pruebas.

# Configuración

Editar el fichero de la raiz conexion.php incluyendo datos de conexión válidos a la BD

```php

define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "");
define("DB", "m07");
define("PORT", "3306");

```
