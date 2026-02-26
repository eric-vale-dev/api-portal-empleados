<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once 'conexion.php';

$conexion = new Conexion();
$db = $conexion->obtenerConexion();

if ($db === null) {
    echo json_encode(["error" => "Error de conexión"]);
    exit;
}

try {
    // KPIs
    $queryKpis = "SELECT 
                    COUNT(*) as total, 
                    SUM(CASE WHEN estatus_activo = 1 THEN 1 ELSE 0 END) as activos, 
                    SUM(CASE WHEN estatus_activo = 0 THEN 1 ELSE 0 END) as inactivos 
                  FROM empleados";
    $stmtKpis = $db->prepare($queryKpis);
    $stmtKpis->execute();
    $kpis = $stmtKpis->fetch(PDO::FETCH_ASSOC);

    $kpis['total'] = $kpis['total'] ?? 0;
    $kpis['activos'] = $kpis['activos'] ?? 0;
    $kpis['inactivos'] = $kpis['inactivos'] ?? 0;

    // Últimos Registros
    $queryUltimos = "SELECT e.nombre, d.nombre as departamento, e.estatus_activo as estado 
                     FROM empleados e 
                     LEFT JOIN departamentos d ON e.departamento_id = d.id 
                     ORDER BY e.id DESC LIMIT 5";
    $stmtUltimos = $db->prepare($queryUltimos);
    $stmtUltimos->execute();
    $ultimos = $stmtUltimos->fetchAll(PDO::FETCH_ASSOC);

    // Gráfica (Con LEFT JOIN por si un departamento no tiene empleados)
    $queryGrafica = "SELECT d.nombre as departamento, COUNT(e.id) as cantidad 
                     FROM departamentos d 
                     INNER JOIN empleados e ON d.id = e.departamento_id 
                     WHERE e.estatus_activo = 1 
                     GROUP BY d.id, d.nombre";
    $stmtGrafica = $db->prepare($queryGrafica);
    $stmtGrafica->execute();
    $grafica = $stmtGrafica->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode([
        "kpis" => $kpis,
        "ultimos" => $ultimos,
        "grafica" => $grafica
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
}
?>