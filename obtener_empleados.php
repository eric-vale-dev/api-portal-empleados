<?php 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include 'conexion.php';
$conexion = new Conexion();
$db = $conexion->obtenerConexion();

// Consulta SQL con INNER JOIN y filtrado por baja lógica 
$query = "SELECT
            e.id,
            e.nombre,
            e.correo,
            e.fecha_registro,
            d.nombre as departamento,
            p.nombre as puesto
           FROM empleados e
           INNER JOIN departamentos d ON e.departamento_id = d.id
           INNER JOIN puestos p ON e.puesto_id = p.id
           WHERE e.estatus_activo = 1";

// Preparar y ejecutar la consulta
$stmt = $db->prepare($query);
$stmt->execute();

$empleados = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    array_push($empleados, $row);
}

// Imprimimos el resultado en formato JSON para que Angular lo entienda
echo json_encode($empleados);
?>