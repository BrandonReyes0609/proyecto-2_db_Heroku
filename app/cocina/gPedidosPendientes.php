<?php
// get_pedidos_pendientes.php
header('Content-Type: application/json');

$conexion = new mysqli("localhost", "usuario", "contraseña", "basededatos");
if ($conexion->connect_error) {
    echo json_encode(['error' => 'Error de conexión: ' . $conexion->connect_error]);
    exit();
}

// Cambia 'pendiente' por FALSE en la condición WHERE para que coincida con tu campo 'cocinado'.
$result = $conexion->query("SELECT * FROM items_cuenta WHERE cocinado = FALSE ORDER BY fecha_hora ASC");
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
