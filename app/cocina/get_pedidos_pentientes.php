<?php
include '../../includes/conexion.php';
session_start();

header('Content-Type: application/json');

// Solo los cocineros pueden ver los pedidos pendientes
if (!isset($_SESSION['nombre_usuario']) || $_SESSION['rol'] != 'cocinero') {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT ic.cuenta_id, ic.item_id, p.nombre AS plato_nombre, ic.cantidad, ic.cocinado 
                             FROM items_cuenta ic
                             JOIN platos p ON ic.item_id = p.plato_id
                             WHERE ic.cocinado = false
                             ORDER BY ic.fecha_hora ASC");
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['pedidos' => $pedidos]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
