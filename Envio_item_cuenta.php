<?php
session_start();

require 'includes/conexion.php'; // Asegúrate de que la ruta esté correcta

$tipo_cuenta = $_REQUEST['tipo_cuenta'];
$tipo_plato = $_REQUEST['tipo_plato'];
$num_platos = $_REQUEST['num_platos'];
$tipo_bbeida = $_REQUEST['tipo_bbeida'];
$num_bebida = $_REQUEST['num_bebida'];

// Usamos la conexión de la base de datos que se creó en conexion.php
$query_platos = "INSERT INTO mesas (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES ($1, $2, $3, NOW(), false)";
$resultado1 = pg_query_params($conn, $query_platos, array($tipo_cuenta, $tipo_plato, $num_platos));

// Usamos la conexión de la base de datos que se creó en conexion.php
$query_bebidas = "INSERT INTO mesas (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES ($1, $2, $3, NOW(), false)";
$resultado2 = pg_query_params($conn, $query_bebidas, array($tipo_cuenta, $tipo_bbeida, $num_bebida));

if ($resultado1) {
    $resultado1 = pg_query_params($conn, $query_mesas, array($tipo_cuenta, $tipo_plato, $num_platos));

} else {
    $_SESSION['user_alert'] = "Error resultado 1: " . pg_last_error($conn);
}


if ($resultado2) {
    $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
} else {
    $_SESSION['user_alert'] = "Error resultado 2: " . pg_last_error($conn);
}

pg_close($conn);

// Almacenar mensaje y redirigir al usuario a la página 'Crear_cuenta.php'
header('Location: Envio_item_cuenta.php');
exit(); // No olvides llamar a exit() después de header()
?>
