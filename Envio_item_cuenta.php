<?php
session_start();

require 'includes/conexion.php'; // Verifica que la ruta es correcta

$tipo_cuenta = $_REQUEST['tipo_cuenta'];
$tipo_plato = $_REQUEST['tipo_plato'];
$num_platos = $_REQUEST['num_platos'];
$tipo_bbeida = $_REQUEST['tipo_bbeida'];
$num_bebida = $_REQUEST['num_bebida'];

// Prepara las consultas SQL para platos y bebidas
$query_platos = "INSERT INTO items_cuenta (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES ($1, $2, $3, NOW(), false)";
$resultado1 = pg_query_params($conn, $query_platos, array($tipo_cuenta, $tipo_plato, $num_platos));


if ($resultado1) {
    $query_bebidas = "INSERT INTO items_cuenta (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES ($1, $2, $3, NOW(), true)";
    $resultado2 = pg_query_params($conn, $query_cuentas, array($tipo_cuenta, $tipo_bbeida, $num_bebida));

    if ($resultado2) {
        $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
    } else {
        $_SESSION['user_alert'] = "Error resultado 2: " . pg_last_error($conn);
    }
} else {
    $_SESSION['user_alert'] = "Error resultado 1: " . pg_last_error($conn);
}
// Cierra la conexión
pg_close($conn);

// Redirecciona si todo fue exitoso
header('Location: Agregar_Items_Cuenta.php');
exit();
?>