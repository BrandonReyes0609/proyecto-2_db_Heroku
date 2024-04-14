<?php
include '../../includes/conexion.php';
session_start();

header('Content-Type: application/json');

// Solo los cocineros pueden marcar los pedidos como cocinados
if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] != 'cocinero') {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cuenta_id']) && isset($_POST['item_id'])) {
    $cuentaId = $_POST['cuenta_id'];
    $itemId = $_POST['item_id'];

    try {
        $stmt = $conn->prepare("UPDATE items_cuenta SET cocinado = true WHERE cuenta_id = :cuentaId AND item_id = :itemId");
        $stmt->execute([':cuentaId' => $cuentaId, ':itemId' => $itemId]);
        
        echo json_encode(['success' => 'Pedido marcado como cocinado']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Petición inválida']);
}
?>
