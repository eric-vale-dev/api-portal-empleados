<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once'conexion.php';

$conexion = new Conexion();
$db = $conexion->obtenerConexion();

if($db === null){
    echo json_encode(["mensaje" => "Error de conexión"]);
    exit;
}

// Obtenemos el ID que Angular nos va mandar por la URL 
$id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(["ID no proporciondado"]));

// Baja lógica, hacemos un UPDATE y no un DELETE
$query = "UPDATE empleados SET estatus_activo = 0 WHERE id = id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);

if($stmt->execute()) {
    echo json_encode(["mensaje" => "Empleado dado de baja correctamente"]);
} else{
    echo json_encode(["mensaje" => "Error al intentar dar de baja"]);
}
?>
