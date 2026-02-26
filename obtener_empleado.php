<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once 'conexion.php';

$conexion = new Conexion();
$db = $conexion->obtenerConexion();

// Recibimos el ID por la URL (ej. obtener_empleado.php?id=1)
$id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(["mensaje" => "ID no proporcionado"]));

// Buscamos solo los campos que necesitamos para llenar el formulario
$query = "SELECT id, nombre, correo, departamento_id, puesto_id FROM empleados WHERE id = :id LIMIT 0,1";

$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row) {
    // Si lo encuentra, lo manda en formato JSON
    echo json_encode($row);
} else {
    http_response_code(404);
    echo json_encode(["mensaje" => "Empleado no encontrado."]);
}
?>