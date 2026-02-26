<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once 'conexion.php';

$conexion = new Conexion();
$db = $conexion->obtenerConexion();

$id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(["mensaje" => "ID no proporcionado"]));
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->nombre) &&
    !empty($data->correo) &&
    !empty($data->departamento_id) &&
    !empty($data->puesto_id)
) {
    // La consulta mágica de actualización
    $query = "UPDATE empleados 
              SET nombre = :nombre, correo = :correo, departamento_id = :departamento_id, puesto_id = :puesto_id 
              WHERE id = :id";

    $stmt = $db->prepare($query);

    $stmt->bindValue(":nombre", htmlspecialchars(strip_tags($data->nombre)));
    $stmt->bindValue(":correo", htmlspecialchars(strip_tags($data->correo)));
    $stmt->bindValue(":departamento_id", $data->departamento_id);
    $stmt->bindValue(":puesto_id", $data->puesto_id);
    $stmt->bindValue(":id", $id);

    try {
        if ($stmt->execute()) {
            http_response_code(200); 
            echo json_encode(["mensaje" => "Empleado actualizado exitosamente."]);
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            http_response_code(400); 
            echo json_encode(["mensaje" => "Error: El correo electrónico ya está registrado por otro usuario."]);
        } else {
            http_response_code(503); 
            echo json_encode(["mensaje" => "No se pudo actualizar el empleado."]);
        }
    }
} else {
    http_response_code(400); 
    echo json_encode(["mensaje" => "Datos incompletos."]);
}
?>