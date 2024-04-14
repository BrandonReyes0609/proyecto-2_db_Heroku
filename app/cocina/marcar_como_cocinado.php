<?php
header('Content-Type: application/json');
include '../../includes/conexion.php'; // Asume que este archivo contiene los datos de conexión

$conexion = new mysqli("localhost", "usuario", "contraseña", "basededatos");
if ($conexion->connect_error) {
    echo json_encode(['error' => 'Error de conexión: ' . $conexion->connect_error]);
    exit();
}

// Recuperar `cuenta_id` y `item_id` desde POST
$cuenta_id = $_POST['cuenta_id'] ?? '';
$item_id = $_POST['item_id'] ?? '';

if (!$cuenta_id || !$item_id) {
    echo json_encode(['error' => 'Datos incompletos para la actualización']);
    exit();
}

$sql = "UPDATE pedidos SET estado = 'cocinado' WHERE cuenta_id = ? AND item_id = ?";
$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ii", $cuenta_id, $item_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => 'Pedido marcado como cocinado']);
    } else {
        echo json_encode(['error' => 'No se pudo actualizar el pedido']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Error en la preparación: ' . $conexion->error]);
}

$conexion->close();
?>
