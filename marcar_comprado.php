<?php
// ==========================================================
// Archivo: marcar_comprado.php
// Propósito: Recibe el ID del regalo y lo inserta en la DB
// ==========================================================

// Asegurarse de que el script devuelva JSON
header('Content-Type: application/json');

// Incluir la configuración de la base de datos
require_once 'db_config.php';

// Verificar que se haya enviado el ID del regalo por POST
if (!isset($_POST['regalo_id']) || empty($_POST['regalo_id'])) {
    echo json_encode(["success" => false, "message" => "ID de regalo no proporcionado."]);
    exit;
}

// Limpiar y escapar la entrada para prevenir inyección SQL
$regalo_id = mysqli_real_escape_string($link, $_POST['regalo_id']);

// Preparar la sentencia SQL para insertar el regalo
$sql = "INSERT INTO regalos_comprados (regalo_id) VALUES (?)";

if ($stmt = mysqli_prepare($link, $sql)) {
    // Vincular la variable al parámetro
    mysqli_stmt_bind_param($stmt, "s", $param_regalo_id);
    
    // Asignar el parámetro
    $param_regalo_id = $regalo_id;
    
    // Intentar ejecutar la sentencia
    if (mysqli_stmt_execute($stmt)) {
        // Éxito en la inserción
        echo json_encode(["success" => true, "message" => "Regalo marcado como comprado."]);
    } else {
        // Fallo en la ejecución (posiblemente duplicado)
        // El error 1062 es por entrada duplicada
        if (mysqli_errno($link) == 1062) {
             echo json_encode(["success" => false, "message" => "El regalo ya había sido marcado como comprado."]);
        } else {
             echo json_encode(["success" => false, "message" => "Error de base de datos: " . mysqli_error($link)]);
        }
    }
    // Cerrar la sentencia
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["success" => false, "message" => "Error de preparación de sentencia."]);
}

// Cerrar la conexión
mysqli_close($link);
?>