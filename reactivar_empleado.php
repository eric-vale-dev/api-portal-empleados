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

// regresamos el estatus a 1 (activo)
$query = "UPDATE empleados SET estatus_activo = 1 WHERE id = :id";

$stmt = $db->prepare($query);
$stmt->bindParam(":id", $id);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(["mensaje" => "Empleado reactivado exitosamente."]);
} else {
    http_response_code(503);
    echo json_encode(["mensaje" => "No se pudo reactivar al empleado."]);
}
?>