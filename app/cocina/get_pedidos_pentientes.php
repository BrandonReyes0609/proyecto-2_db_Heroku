<?php
header('Content-Type: application/json');

include '../../includes/conexion.php'; // Asume que este archivo contiene los datos de conexión

$conexion = new mysqli("localhost", "usuario", "contraseña", "basededatos");
if ($conexion->connect_error) {
    echo json_encode(['error' => 'Error de conexión: ' . $conexion->connect_error]);
    exit();
}

$result = $conexion->query("SELECT * FROM pedidos WHERE estado = 'pendiente' ORDER BY hora_pedido ASC");
if (!$result) {
    echo json_encode(['error' => 'Error en la consulta: ' . $conexion->error]);
    exit();
}

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

echo json_encode(['pedidos' => $pedidos]);
?>
