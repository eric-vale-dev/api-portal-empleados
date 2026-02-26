<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once 'conexion.php';

$conexion = new Conexion();
$db = $conexion->obtenerConexion();

// Atrapamos el JSON que nos manda Angular
$data = json_decode(file_get_contents("php://input"));

// Verificamos que no venga vacío
if (
    !empty($data->nombre) &&
    !empty($data->correo) &&
    !empty($data->departamento_id) &&
    !empty($data->puesto_id)
) {
    // Preparamos el INSERT INTO (Sin poner el ID ni la fecha, MySQL los pone solos)
    $query = "INSERT INTO empleados (nombre, correo, departamento_id, puesto_id) 
              VALUES (:nombre, :correo, :departamento_id, :puesto_id)";

    $stmt = $db->prepare($query);

    // Limpiamos los datos por seguridad y los inyectamos con bindValue
    $stmt->bindValue(":nombre", htmlspecialchars(strip_tags($data->nombre)));
    $stmt->bindValue(":correo", htmlspecialchars(strip_tags($data->correo)));
    $stmt->bindValue(":departamento_id", $data->departamento_id);
    $stmt->bindValue(":puesto_id", $data->puesto_id);

    // Intentamos ejecutar la consulta y atrapamos posibles errores de duplicados
    try {
        if ($stmt->execute()) {
            http_response_code(201); 
            echo json_encode(["mensaje" => "Empleado creado exitosamente."]);
        }
    } catch (PDOException $e) {
        // El código 23000 significa violación de restricción (ej. correo duplicado)
        if ($e->getCode() == 23000) {
            http_response_code(400); 
            echo json_encode(["mensaje" => "Error: El correo electrónico ya está registrado."]);
        } else {
            http_response_code(503); 
            echo json_encode(["mensaje" => "No se pudo crear el empleado. Error en la base de datos."]);
        }
    }
} else {
    // Si faltan datos, rechazamos la petición
    http_response_code(400); 
    echo json_encode(["mensaje" => "Datos incompletos. Faltan campos obligatorios."]);
}
?>