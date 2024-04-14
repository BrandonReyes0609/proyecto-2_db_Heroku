<?php
header('Content-Type: application/json');

// Asume que este archivo contiene los datos de conexión a la base de datos
include '../../includes/conexion.php'; 

$esBebida = $_GET['esBebida'] ?? 'false'; // Capturar el parámetro de la solicitud
$esBebida = filter_var($esBebida, FILTER_VALIDATE_BOOLEAN); // Convierte el string a booleano

// Crear la consulta SQL con un JOIN para unir las dos tablas y filtrar por el tipo de platillo y el estado de cocinado
$sql = "SELECT ic.cuenta_id, ic.item_id, ic.cantidad, ic.fecha_hora, p.nombre as plato_nombre
        FROM items_cuenta ic
        INNER JOIN platos p ON ic.item_id = p.plato_id
        WHERE ic.cocinado = 0 AND p.tipo = ?
        ORDER BY ic.fecha_hora ASC";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Error de preparación de la consulta: ' . $conexion->error]);
    exit();
}

// Preparar y ejecutar la consulta con el parámetro correspondiente
$tipo = $esBebida ? 1 : 0; // Aquí asumimos que en la base de datos, `tipo` es 1 para bebidas y 0 para platillos
$stmt->bind_param("i", $tipo); 
$stmt->execute();

$result = $stmt->get_result();
if (!$result) {
    echo json_encode(['error' => 'Error en la ejecución de la consulta: ' . $conexion->error]);
    exit();
}

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

$stmt->close();
$conexion->close();

echo json_encode(['pedidos' => $pedidos]);
?>
