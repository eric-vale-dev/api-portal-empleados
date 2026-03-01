<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once('conexion.php');

$conexion = new Conexion();
$db = $conexion->obtenerConexion();

// Traemos a los empleados donde el estatus es 0 (Baja Lógica)
$query = "SELECT e.id, e.nombre, e.correo, d.nombre as departamento, p.nombre as puesto, e.fecha_registro 
          FROM empleados e 
          INNER JOIN departamentos d ON e.departamento_id = d.id 
          INNER JOIN puestos p ON e.puesto_id = p.id 
          WHERE e.estatus_activo = 0 
          ORDER BY e.id DESC";

$stmt = $db->prepare($query);
$stmt->execute();

$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($empleados);
?>