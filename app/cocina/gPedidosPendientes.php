<?php
header('Content-Type: application/json');
require_once '../../includes/conexion.php'; // Incluir el archivo de conexión

// Cambia 'pendiente' por FALSE en la condición WHERE para que coincida con tu campo 'cocinado'.
$result = $conn->query("SELECT * FROM items_cuenta WHERE cocinado = 'false' ORDER BY fecha_hora ASC");
if (!$result) {
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->errorInfo()]);
    exit();
}

$pedidos = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $pedidos[] = $row;
}

echo json_encode(['pedidos' => $pedidos]);

cerrarConexion(); // Cerrar la conexión después de usarla
?>
