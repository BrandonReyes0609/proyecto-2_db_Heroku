<?php
header('Content-Type: application/json');

require '../../includes/conexion.php';  // Asegúrate de que la ruta aquí esté correcta

$esBebida = $_GET['esBebida'] ?? 'false';
$esBebida = filter_var($esBebida, FILTER_VALIDATE_BOOLEAN);

try {
    $tipo = $esBebida ? 'true' : 'false'; // PostgreSQL usa 'true'/'false' para los booleanos
    $sql = "SELECT ic.cuenta_id, ic.item_id, ic.cantidad, ic.fecha_hora, p.nombre as plato_nombre
            FROM items_cuenta ic
            INNER JOIN platos p ON ic.item_id = p.plato_id
            WHERE ic.cocinado = false AND p.tipo = :tipo
            ORDER BY ic.fecha_hora ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_BOOL);
    $stmt->execute();
    
    $pedidos = $stmt->fetchAll();

    echo json_encode(['pedidos' => $pedidos]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    // Cierra la conexión a la base de datos
    cerrarConexion();
}
?>
