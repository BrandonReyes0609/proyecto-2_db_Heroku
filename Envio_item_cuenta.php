<?php
session_start();

require 'includes/conexion.php'; // Verifica que la ruta es correcta

$tipo_cuenta = $_REQUEST['tipo_cuenta'];
$tipo_plato = $_REQUEST['tipo_plato'];
$num_platos = $_REQUEST['num_platos'];
$tipo_bbeida = $_REQUEST['tipo_bbeida'];
$num_bebida = $_REQUEST['num_bebida'];

// Prepara las consultas SQL para platos y bebidas
$query_platos = "INSERT INTO mesas (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES ($1, $2, $3, NOW(), false)";
$query_bebidas = "INSERT INTO mesas (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES ($1, $2, $3, NOW(), false)";

// Ejecuta la primera consulta
$resultado1 = pg_query_params($conn, $query_platos, array($tipo_cuenta, $tipo_plato, $num_platos));
if (!$resultado1) {
    $_SESSION['user_alert'] = "Error en la inserción de platos: " . pg_last_error($conn);
    header('Location: error.php');
    exit();
}

// Ejecuta la segunda consulta
$resultado2 = pg_query_params($conn, $query_bebidas, array($tipo_cuenta, $tipo_bbeida, $num_bebida));
if (!$resultado2) {
    $_SESSION['user_alert'] = "Error en la inserción de bebidas: " . pg_last_error($conn);
    header('Location: error.php');
    exit();
}

// Cierra la conexión
pg_close($conn);

// Redirecciona si todo fue exitoso
header('Location: Envio_item_cuenta.php');
exit();
?>