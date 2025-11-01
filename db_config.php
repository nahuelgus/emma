<?php
// ==========================================================
// Archivo: db_config.php
// Propósito: Contiene la configuración de la conexión a MySQL
// ==========================================================

// Credenciales de la Base de Datos
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'c2801297_emma'); 
define('DB_PASSWORD', 'gi75KInufo'); 
define('DB_NAME', 'c2801297_emma'); 

// Intentar conexión a la base de datos
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if($link === false){
    // Este mensaje de error es solo para desarrolladores. 
    // En producción, se debe mostrar un mensaje más genérico.
    die("ERROR: No se pudo conectar a la base de datos. " . mysqli_connect_error());
}
?>