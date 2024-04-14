<?php
session_start(); // Continuar la sesión

require '../../includes/conexion.php'; // Incluir el script de conexión

if (!isset($_POST['tipo_cuenta'], $_POST['tipo_plato'], $_POST['tipo_bebida'], $_POST['num_platos'], $_POST['num_bebida'])) {
    $_SESSION['error'] = "Todos los campos son necesarios.";
    header("Location: Agregar_Items_Cuenta.php");
    exit;
}

$cuentaId = $_POST['tipo_cuenta'];
$platoId = $_POST['tipo_plato'];
$bebidaId = $_POST['tipo_bebida'];
$cantidadPlatos = $_POST['num_platos'];
$cantidadBebidas = $_POST['num_bebida'];

try {
    // Insertar ítem de plato a la cuenta
    $stmt = $conn->prepare("INSERT INTO items_cuenta (cuenta_id, item_id, cantidad) VALUES (?, ?, ?)");
    $stmt->execute([$cuentaId, $platoId, $cantidadPlatos]);

    // Insertar ítem de bebida a la cuenta
    $stmt = $conn->prepare("INSERT INTO items_cuenta (cuenta_id, item_id, cantidad) VALUES (?, ?, ?)");
    $stmt->execute([$cuentaId, $bebidaId, $cantidadBebidas]);

    // Establecer mensaje de éxito
    $_SESSION['user_alert'] = "Ítems agregados correctamente a la cuenta.";
    header("Location: Agregar_Items_Cuenta.php");
} catch (PDOException $e) {
    // Manejar error
    $_SESSION['error'] = "Error al agregar ítems: " . $e->getMessage();
    header("Location: error.php");
    exit;
}
?>
