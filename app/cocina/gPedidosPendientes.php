<?php
header('Content-Type: application/json');

include '../../includes/conexion.php'; 

$esBebida = $_GET['esBebida'] ?? 'false';
$esBebida = filter_var($esBebida, FILTER_VALIDATE_BOOLEAN);

try {
    $sql = "SELECT ic.cuenta_id, ic.item_id, ic.cantidad, ic.fecha_hora, p.nombre as plato_nombre
    FROM items_cuenta ic
    INNER JOIN platos p ON ic.item_id = p.plato_id
    WHERE ic.cocinado = false AND p.tipo = $1
    ORDER BY ic.fecha_hora ASC";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
    echo json_encode(['error' => 'Error de preparación de la consulta: ' . $conexion->error]);
    exit();
    }

    $tipo = $esBebida;
    $stmt->bind_param('bool', $tipo);
    $stmt->execute();

    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception('Error en la ejecución de la consulta: ' . $conexion->error);
    }

    $pedidos = [];
    while ($row = $result->fetch_assoc()) {
        $pedidos[] = $row;
    }

    echo json_encode(['pedidos' => $pedidos]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $conexion->close();
}
?>
