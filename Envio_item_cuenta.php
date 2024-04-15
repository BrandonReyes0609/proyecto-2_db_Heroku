<?php
session_start();

require 'includes/conexion.php'; // Incluye la conexión a la base de datos desde 'includes'

// Recoger los datos del formulario
$tipo_cuenta = $_POST['tipo_cuenta'];
$tipo_plato = $_POST['tipo_plato'];
$num_platos = $_POST['num_platos'];
$tipo_bebida = $_POST['tipo_bebida'];
$num_bebida = $_POST['num_bebida'];

// Preparar las consultas SQL para platos y bebidas
$query_platos = "INSERT INTO items_cuenta (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES ($1, $2, $3, NOW(), false)";
$query_bebidas = "INSERT INTO items_cuenta (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES ($1, $2, $3, NOW(), true)";

// Iniciar transacción
pg_query($conn, "BEGIN");
$resultado1 = pg_query_params($conn, $query_platos, array($tipo_cuenta, $tipo_plato, $num_platos));
$resultado2 = pg_query_params($conn, $query_bebidas, array($tipo_cuenta, $tipo_bebida, $num_bebida));

if ($resultado1 && $resultado2) {
    pg_query($conn, "COMMIT"); // Completa la transacción
    $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
    header('Location: Agregar_Items_Cuenta.php');
    exit();
} else {
    pg_query($conn, "ROLLBACK"); // Revierte la transacción
    $_SESSION['user_alert'] = "Error al insertar datos: " . pg_last_error($conn);
    header('Location: Agregar_Items_Cuenta.php');
    exit();
}

// Cierra la conexión
pg_close($conn);
?>
