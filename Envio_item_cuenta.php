<?php
session_start();

require 'includes/conexion.php'; // Verifica que la ruta es correcta

$tipo_cuenta = $_REQUEST['tipo_cuenta'];
$tipo_plato = $_REQUEST['tipo_plato'];
$num_platos = $_REQUEST['num_platos'];
$tipo_bbeida = $_REQUEST['tipo_bbeida'];
$num_bebida = $_REQUEST['num_bebida'];

try {
    // Prepara la consulta SQL para platos
    $query_platos = "INSERT INTO items_cuenta (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES (?, ?, ?, NOW(), false)";
    $stmt = $conn->prepare($query_platos);
    $stmt->execute([$tipo_cuenta, $tipo_plato, $num_platos]);

    // Si el primer INSERT es exitoso, intenta el segundo para bebidas
    if ($stmt) {
        $query_bebidas = "INSERT INTO items_cuenta (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES (?, ?, ?, NOW(), true)";
        $stmt = $conn->prepare($query_bebidas);
        $stmt->execute([$tipo_cuenta, $tipo_bbeida, $num_bebida]);

        $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
    }
} catch (PDOException $e) {
    $_SESSION['user_alert'] = "Error en la base de datos: " . $e->getMessage();
} finally {
    cerrarConexion();
}

// Redirecciona si todo fue exitoso
header('Location: Agregar_Items_Cuenta.php');
exit();
?>
