<?php
header('Content-Type: application/json');
require_once '../../includes/conexion.php'; // Incluir el archivo de conexión

$cuenta_id = $_POST['cuenta_id'] ?? '';
$item_id = $_POST['item_id'] ?? '';

if (!$cuenta_id || !$item_id) {
    echo json_encode(['error' => 'Datos incompletos para la actualización']);
    exit();
}

$sql = "UPDATE items_cuenta SET cocinado = 'true' WHERE cuenta_id = ? AND item_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bindParam(1, $cuenta_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $item_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => 'Pedido marcado como cocinado']);
        } else {
            echo json_encode(['error' => 'No se pudo actualizar el pedido']);
        }
    } else {
        echo json_encode(['error' => 'Error al ejecutar la consulta']);
    }
    $stmt->closeCursor();
} else {
    echo json_encode(['error' => 'Error en la preparación: ' . $conn->errorInfo()]);
}
cerrarConexion(); // Cerrar la conexión después de usarla
?>
