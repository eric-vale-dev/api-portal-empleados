<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once'conexion.php';

$conexion = new Conexion();
$db =  $conexion->obtenerConexion();

if($db === null){
    echo json_encode(["error" => "Error de conexión"]);
    exit;
}

// Obtenemos los departamentos 
$queryDept = "SELECT id, nombre FROM departamentos";
$stmtDept = $db->prepare($queryDept);
$stmtDept->execute();
$departamentos = $stmtDept->fetchAll(PDO::FETCH_ASSOC);

// Obtenemos los puestos
$queryPuestos = "SELECT id, nombre FROM puestos";
$stmtPuestos = $db->prepare($queryPuestos);
$stmtPuestos->execute();
$puestos = $stmtPuestos->fetchAll(PDO::FETCH_ASSOC);

// Enviamos ambos en un solo JSON
echo json_encode([
    "departamentos" => $departamentos,
    "puestos" => $puestos
]);
?>