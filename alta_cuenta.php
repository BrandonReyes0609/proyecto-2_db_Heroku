<?php
session_start();

require 'includes/conexion.php'; // Asegúrate de que la ruta esté correcta

$tipo_area1 = $_REQUEST['tipo_zona'];
$num_personas = $_REQUEST['num_personas'];
$numero_mesa = $_REQUEST['numero_mesa'];

// Usamos la conexión de la base de datos que se creó en conexion.php
$query_mesas = "INSERT INTO mesas (mesa_id, area_id, capacidad, movilidad) VALUES ($1, $2, $3, false)";
$resultado1 = pg_query_params($conn, $query_mesas, array($numero_mesa, $tipo_area1, $num_personas));

if ($resultado1) {
    $query_cuentas = "INSERT INTO cuentas (mesa_id, fecha_apertura, fecha_cierre, total) VALUES ($1, NOW(), null, 0)";
    $resultado2 = pg_query_params($conn, $query_cuentas, array($numero_mesa));

    if ($resultado2) {
        $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
    } else {
        $_SESSION['user_alert'] = "Error resultado 2: " . pg_last_error($conn);
    }
} else {
    $_SESSION['user_alert'] = "Error resultado 1: " . pg_last_error($conn);
}

pg_close($conn);

// Almacenar mensaje y redirigir al usuario a la página 'Crear_cuenta.php'
header('Location: Crear_cuenta.php');
exit(); // No olvides llamar a exit() después de header()
?>
